<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Validator;
use URL;
use Session;
use Redirect;
use Config;
use App\Package;
use App\User;
use App\Business;
use Carbon\Carbon;
use App\Traits\BusinessPackageTrait;

use Stripe\Stripe;
use Stripe\Charge;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

use Tzsk\Payu\Concerns\Attributes;
use Tzsk\Payu\Concerns\Customer;
use Tzsk\Payu\Concerns\Transaction as PayuTransaction;
use Tzsk\Payu\Facades\Payu;

class BusinessPackageController extends Controller
{
    use BusinessPackageTrait;

    private $_api_context;
    private $redirectTo = 'business.dashboard';

    public function __construct()
    {
        $this->middleware(['auth', 'business.auth']);

        // PayPal Setup
        $paypal_conf = Config::get('paypal');
        if (!empty($paypal_conf['client_id']) && !empty($paypal_conf['secret'])) {
            $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
            $this->_api_context->setConfig($paypal_conf['settings'] ?? []);
        }
    }

    /**
     * Show Business Packages & Pricing comparison page
     */
    public function packages(Request $request)
    {
        $packages = Package::where('package_for', 'business')
            ->orderBy('package_price', 'asc')
            ->get();

        $user = Auth::user();
        $currentPackage = null;
        $remainingDays = 0;
        $isExpired = true;

        if ($user->business_package_id > 0) {
            $currentPackage = Package::find($user->business_package_id);
            if ($user->business_package_end_date) {
                $endDate = Carbon::parse($user->business_package_end_date);
                if ($endDate->isFuture()) {
                    $remainingDays = Carbon::now()->diffInDays($endDate, false);
                    $isExpired = false;
                }
            }
        }

        $usedQuota = Business::where('user_id', $user->id)->count();
        $totalQuota = $user->business_listings_quota ?: 1;

        return view('business.dashboard.packages', compact(
            'packages',
            'currentPackage',
            'remainingDays',
            'isExpired',
            'usedQuota',
            'totalQuota'
        ));
    }

    /**
     * Subscribe to Free Business Package
     */
    public function orderFreePackage($id)
    {
        $package = Package::where('package_for', 'business')->findOrFail($id);

        if ($package->package_price > 0) {
            flash('This is a paid package. Please select a payment method.')->error();
            return redirect()->route('business.packages');
        }

        $user = Auth::user();
        $this->addBusinessPackage($user, $package);

        flash('You have successfully activated the ' . $package->package_title . ' package!')->success();
        return redirect()->route('business.dashboard');
    }

    /**
     * Test / Instant activation in dev mode
     */
    public function orderTestPackage($id)
    {
        $package = Package::where('package_for', 'business')->findOrFail($id);
        $user = Auth::user();
        $this->addBusinessPackage($user, $package);

        flash('Package ' . $package->package_title . ' activated successfully!')->success();
        return redirect()->route('business.dashboard');
    }

    /**
     * Stripe Payment Form
     */
    public function stripeForm($id)
    {
        $package = Package::where('package_for', 'business')->findOrFail($id);
        return view('business.dashboard.pay_stripe', compact('package'));
    }

    /**
     * Process Stripe Payment
     */
    public function stripeOrderPackage(Request $request)
    {
        $request->validate([
            'package_id' => 'required|integer',
            'stripeToken' => 'required',
        ]);

        $package = Package::where('package_for', 'business')->findOrFail($request->package_id);
        $user = Auth::user();
        $order_amount = $package->package_price;

        $description = 'Business Package: ' . $package->package_title . ' - ' . $user->name . ' (' . $user->email . ')';

        Stripe::setApiKey(Config::get('stripe.stripe_secret'));
        try {
            $currency = config('site_setting.default_currency_code', 'USD');
            // Stripe accepts integer smallest currency unit (e.g. cents)
            $charge = Charge::create([
                'amount' => (int)($order_amount * 100),
                'currency' => strtolower($currency) === 'inr' ? 'inr' : 'usd',
                'source' => $request->input('stripeToken'),
                'description' => $description,
            ]);

            if ($charge['status'] == 'succeeded') {
                $this->addBusinessPackage($user, $package);
                flash('Payment successful! You have subscribed to the ' . $package->package_title . ' package.')->success();
                return redirect()->route('business.dashboard');
            } else {
                flash('Payment processing failed. Please try again.')->error();
                return redirect()->route('business.packages');
            }
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->route('business.packages');
        }
    }

    /**
     * PayPal Payment
     */
    public function paypalOrderPackage(Request $request, $package_id)
    {
        $package = Package::where('package_for', 'business')->findOrFail($package_id);
        $user = Auth::user();
        $order_amount = $package->package_price;

        $description = 'Business Package: ' . $package->package_title . ' - ' . $user->name;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName('Business Package: ' . $package->package_title)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($order_amount);

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($order_amount);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($description);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('business.paypal.status', $package->id))
            ->setCancelUrl(route('business.packages'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($this->_api_context);
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    Session::put('paypal_payment_id', $payment->getId());
                    return Redirect::away($link->getHref());
                }
            }
        } catch (\Exception $ex) {
            flash('PayPal error: ' . $ex->getMessage())->error();
            return redirect()->route('business.packages');
        }

        flash('Unknown error occurred with PayPal')->error();
        return redirect()->route('business.packages');
    }

    /**
     * PayPal Status Callback
     */
    public function getPaypalStatus(Request $request, $package_id)
    {
        $payment_id = Session::get('paypal_payment_id');
        Session::forget('paypal_payment_id');

        if (empty($request->input('PayerID')) || empty($request->input('token'))) {
            flash('Payment cancelled or failed')->error();
            return redirect()->route('business.packages');
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));

        try {
            $result = $payment->execute($execution, $this->_api_context);
            if ($result->getState() == 'approved') {
                $package = Package::findOrFail($package_id);
                $user = Auth::user();
                $this->addBusinessPackage($user, $package);

                flash('Payment successful! Package activated.')->success();
                return redirect()->route('business.dashboard');
            }
        } catch (\Exception $e) {
            flash('PayPal execution error: ' . $e->getMessage())->error();
        }

        return redirect()->route('business.packages');
    }

    /**
     * PayU Payment
     */
    public function payuOrderPackage(Request $request)
    {
        $package = Package::where('package_for', 'business')->findOrFail($request->package_id);
        $user = Auth::user();
        $order_amount = $package->package_price;

        $attributes = [
            'txnid' => strtoupper(str_random(8)),
            'amount' => $order_amount,
            'productinfo' => 'Business Package: ' . $package->package_title,
            'firstname' => $user->first_name ?: $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?: '9999999999',
        ];

        return Payu::initiate($attributes)->send();
    }

    /**
     * PayU Callback Status
     */
    public function payuOrderPackageStatus(Request $request)
    {
        $payment = Payu::capture();

        if ($payment->successful()) {
            $package_id = $request->package_id ?? Session::get('payu_package_id');
            if ($package_id) {
                $package = Package::findOrFail($package_id);
                $user = Auth::user();
                $this->addBusinessPackage($user, $package);
            }
            flash('Payment successful via PayU! Package activated.')->success();
            return redirect()->route('business.dashboard');
        }

        flash('PayU Payment failed')->error();
        return redirect()->route('business.packages');
    }
}
