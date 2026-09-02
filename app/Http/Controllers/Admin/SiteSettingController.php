<?php



namespace App\Http\Controllers\Admin;



use Auth;

use DB;

use Input;

use File;

use Carbon\Carbon;

use ImgUploader;

use Redirect;

use App\Country;

use App\CountryDetail;

use App\SiteSetting;

use App\Http\Requests;

use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use DataTables;

use App\Http\Requests\SiteSettingFormRequest;

use App\Http\Controllers\Controller;

use App\Helpers\DataArrayHelper;



class SiteSettingController extends Controller

{



    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        //

    }



    public function editSiteSetting()

    {

        $id = 1272;

        $countries = DataArrayHelper::defaultCountriesArray();

        $currency_codes = CountryDetail::select('countries_details.code')->orderBy('countries_details.code')->pluck('countries_details.code', 'countries_details.code')->toArray();

        $mail_drivers = [

            'smtp' => 'SMTP',

            'mail' => 'Mail',

            'sendmail' => 'SendMail',

            'mailgun' => 'MailGun',

            'mandrill' => 'Mandrill',

            'ses' => 'Amazon SES',

            'sparkpost' => 'Sparkpost',

            'log' => 'Log'

        ];

        $siteSetting = SiteSetting::findOrFail($id);

        return view('admin.site_setting.edit')

                        ->with('siteSetting', $siteSetting)

                        ->with('mail_drivers', $mail_drivers)

                        ->with('countries', $countries)

                        ->with('currency_codes', $currency_codes);

    }



    public function updateSiteSetting(SiteSettingFormRequest $request)

    {

        $id = 1272;

        $siteSetting = SiteSetting::findOrFail($id);

        if ($request->hasFile('image')) {

            $this->deleteSiteSettingImage($id);

            $image_name = $request->input('site_name');

            $fileName = ImgUploader::UploadImage('sitesetting_images', $request->file('image'), $image_name);

            $siteSetting->site_logo = $fileName;

        }

        if ($request->hasFile('favicon')) {

            $file = $request->file('favicon');

            $file->move(public_path(), 'favicon.ico');

        }

        if ($request->hasFile('hero_image')) {

            $this->deleteHeroImage($id);

            $hero_file_name = 'hero_' . time();

            $fileName = ImgUploader::UploadImage('sitesetting_images', $request->file('hero_image'), $hero_file_name);

            $siteSetting->hero_image = $fileName;

        }

        if ($request->hasFile('page_title_bg_image')) {

            $this->deletePageTitleBgImage($id);

            $banner_file_name = 'page_title_bg_' . time();

            $fileName = ImgUploader::UploadImage('sitesetting_images', $request->file('page_title_bg_image'), $banner_file_name);

            $siteSetting->page_title_bg_image = $fileName;

        }

        $siteSetting->site_name = $request->input('site_name');

        $siteSetting->site_slogan = $request->input('site_slogan');

        $siteSetting->site_phone_primary = $request->input('site_phone_primary');

        $siteSetting->site_phone_secondary = $request->input('site_phone_secondary');

        $siteSetting->mail_from_address = $request->input('mail_from_address');

        $siteSetting->mail_from_name = $request->input('mail_from_name');

        $siteSetting->mail_to_address = $request->input('mail_to_address');

        $siteSetting->mail_to_name = $request->input('mail_to_name');

        
        $siteSetting->is_payu_active = $request->input('is_payu_active');
        $siteSetting->payu_money_key = $request->input('payu_money_key');
        $siteSetting->payu_money_mode = $request->input('payu_money_mode');
        $siteSetting->salt = $request->input('salt');

        $siteSetting->default_country_id = $request->input('default_country_id');

        $siteSetting->country_specific_site = $request->input('country_specific_site');

        $siteSetting->default_currency_code = $request->input('default_currency_code');

        $siteSetting->site_street_address = $request->input('site_street_address');

        $siteSetting->site_google_map = $request->input('site_google_map');

        $siteSetting->mail_driver = $request->input('mail_driver');

        $siteSetting->mail_host = $request->input('mail_host');

        $siteSetting->mail_port = $request->input('mail_port');

        $siteSetting->mail_encryption = $request->input('mail_encryption');

        $siteSetting->mail_username = $request->input('mail_username');

        $siteSetting->mail_password = $request->input('mail_password');

        $siteSetting->mail_sendmail = $request->input('mail_sendmail');

        $siteSetting->mail_pretend = $request->input('mail_pretend');

        $siteSetting->mailgun_domain = $request->input('mailgun_domain');

        $siteSetting->mailgun_secret = $request->input('mailgun_secret');

        $siteSetting->mandrill_secret = $request->input('mandrill_secret');

        $siteSetting->sparkpost_secret = $request->input('sparkpost_secret');

        $siteSetting->ses_key = $request->input('ses_key');

        $siteSetting->ses_secret = $request->input('ses_secret');

        $siteSetting->ses_region = $request->input('ses_region');

        $siteSetting->facebook_address = $request->input('facebook_address');

        $siteSetting->twitter_address = $request->input('twitter_address');

        $siteSetting->google_plus_address = $request->input('google_plus_address');

        $siteSetting->youtube_address = $request->input('youtube_address');

        $siteSetting->instagram_address = $request->input('instagram_address');

        $siteSetting->pinterest_address = $request->input('pinterest_address');

        $siteSetting->linkedin_address = $request->input('linkedin_address');

        $siteSetting->tumblr_address = $request->input('tumblr_address');

        $siteSetting->flickr_address = $request->input('flickr_address');

        $siteSetting->index_page_below_top_employes_ad = $request->input('index_page_below_top_employes_ad');

        $siteSetting->above_footer_ad = $request->input('above_footer_ad');

        $siteSetting->dashboard_page_ad = $request->input('dashboard_page_ad');

        $siteSetting->cms_page_ad = $request->input('cms_page_ad');

        $siteSetting->listing_page_vertical_ad = $request->input('listing_page_vertical_ad');

        $siteSetting->listing_page_horizontal_ad = $request->input('listing_page_horizontal_ad');

        $siteSetting->nocaptcha_sitekey = $request->input('nocaptcha_sitekey');

        $siteSetting->nocaptcha_secret = $request->input('nocaptcha_secret');

        $siteSetting->facebook_app_id = $request->input('facebook_app_id');

        $siteSetting->facebeek_app_secret = $request->input('facebeek_app_secret');

        $siteSetting->google_app_id = $request->input('google_app_id');

        $siteSetting->google_app_secret = $request->input('google_app_secret');

        $siteSetting->twitter_app_id = $request->input('twitter_app_id');

        $siteSetting->twitter_app_secret = $request->input('twitter_app_secret');

        $siteSetting->paypal_account = $request->input('paypal_account');

        $siteSetting->paypal_client_id = $request->input('paypal_client_id');

        $siteSetting->paypal_secret = $request->input('paypal_secret');

        $siteSetting->paypal_live_sandbox = $request->input('paypal_live_sandbox');

        $siteSetting->stripe_key = $request->input('stripe_key');

        $siteSetting->stripe_secret = $request->input('stripe_secret');
        
        $siteSetting->is_paypal_active = $request->input('is_paypal_active');

        $siteSetting->is_stripe_active = $request->input('is_stripe_active');

        $siteSetting->is_razorpay_active = $request->input('is_razorpay_active', 1);
        $siteSetting->razorpay_key = $request->input('razorpay_key');
        if ($request->filled('razorpay_secret')) {
            $siteSetting->razorpay_secret = $request->input('razorpay_secret');
        }
        $siteSetting->razorpay_webhook_secret = $request->input('razorpay_webhook_secret');
        $siteSetting->razorpay_mode = $request->input('razorpay_mode', 'test');

        $siteSetting->is_jobseeker_package_active = $request->input('is_jobseeker_package_active');

		$siteSetting->is_company_package_active = $request->input('is_company_package_active');

        $siteSetting->is_slider_active = $request->input('is_slider_active');

		$siteSetting->mailchimp_api_key = $request->input('mailchimp_api_key');

        $siteSetting->mailchimp_list_name = $request->input('mailchimp_list_name');

		$siteSetting->mailchimp_list_id = $request->input('mailchimp_list_id');

        $siteSetting->hero_badge_text = $request->input('hero_badge_text');
        $siteSetting->hero_title_line1 = $request->input('hero_title_line1');
        $siteSetting->hero_title_line2 = $request->input('hero_title_line2');
        $siteSetting->hero_subtitle = $request->input('hero_subtitle');
        $siteSetting->hero_stat1_number = $request->input('hero_stat1_number');
        $siteSetting->hero_stat1_label = $request->input('hero_stat1_label');
        $siteSetting->hero_stat2_number = $request->input('hero_stat2_number');
        $siteSetting->hero_stat2_label = $request->input('hero_stat2_label');
        $siteSetting->hero_stat3_number = $request->input('hero_stat3_number');
        $siteSetting->hero_stat3_label = $request->input('hero_stat3_label');
        $siteSetting->hero_hired_text = $request->input('hero_hired_text');

        $siteSetting->update();

        flash('Site Setting has been updated!')->success();

        return \Redirect::route('edit.site.setting');

    }



    private function deleteSiteSettingImage($id)

    {

        try {

            $siteSetting = SiteSetting::findOrFail($id);

            $image = $siteSetting->image;

            if (!empty($image)) {

                File::delete(ImgUploader::real_public_path() . 'sitesetting_images/thumb/' . $image);

                File::delete(ImgUploader::real_public_path() . 'sitesetting_images/mid/' . $image);

                File::delete(ImgUploader::real_public_path() . 'sitesetting_images/' . $image);

            }

            return 'ok';

        } catch (ModelNotFoundException $e) {

            return 'notok';

        }

    }



    private function deleteHeroImage($id)
    {
        try {
            $siteSetting = SiteSetting::findOrFail($id);
            $image = $siteSetting->hero_image;
            if (!empty($image)) {
                File::delete(ImgUploader::real_public_path() . 'sitesetting_images/thumb/' . $image);
                File::delete(ImgUploader::real_public_path() . 'sitesetting_images/mid/' . $image);
                File::delete(ImgUploader::real_public_path() . 'sitesetting_images/' . $image);
            }
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    /**
     * Dedicated SMTP Settings View
     */
    public function smtpSettings()
    {
        $siteSetting = SiteSetting::first();
        $mail_drivers = [
            'smtp' => 'SMTP',
            'mail' => 'Mail',
            'sendmail' => 'SendMail',
            'mailgun' => 'MailGun',
            'mandrill' => 'Mandrill',
            'ses' => 'Amazon SES',
            'sparkpost' => 'Sparkpost',
            'log' => 'Log'
        ];

        return view('admin.site_setting.smtp_settings', compact('siteSetting', 'mail_drivers'));
    }

    /**
     * Update SMTP Settings
     */
    public function updateSmtpSettings(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|string',
            'mail_from_name' => 'nullable|string',
        ]);

        $siteSetting = SiteSetting::first();
        if (!$siteSetting) {
            $siteSetting = new SiteSetting();
        }

        $siteSetting->mail_driver = $request->input('mail_driver', 'smtp');
        $siteSetting->mail_host = $request->input('mail_host');
        $siteSetting->mail_port = $request->input('mail_port');
        $siteSetting->mail_username = $request->input('mail_username');
        $siteSetting->mail_password = $request->input('mail_password');
        $siteSetting->mail_encryption = $request->input('mail_encryption');
        $siteSetting->mail_from_address = $request->input('mail_from_address');
        $siteSetting->mail_from_name = $request->input('mail_from_name');
        $siteSetting->save();

        flash('SMTP Mail settings have been updated successfully!')->success();
        return redirect()->back();
    }

    /**
     * Send Live Test SMTP Email from Admin Panel
     */
    public function testSmtpEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email|max:191'
        ]);

        $testEmail = $request->input('test_email');
        $siteSetting = SiteSetting::first();
        $siteName = $siteSetting ? $siteSetting->site_name : config('app.name', 'Jobs Portal');

        try {
            if ($siteSetting && $siteSetting->mail_driver === 'smtp') {
                $transport = new \Swift_SmtpTransport(
                    $siteSetting->mail_host ?: 'smtp.gmail.com',
                    $siteSetting->mail_port ?: 587,
                    $siteSetting->mail_encryption ?: 'tls'
                );
                if (!empty($siteSetting->mail_username)) {
                    $transport->setUsername($siteSetting->mail_username);
                }
                if (!empty($siteSetting->mail_password)) {
                    $transport->setPassword($siteSetting->mail_password);
                }
                $mailer = new \Swift_Mailer($transport);
                \Illuminate\Support\Facades\Mail::setSwiftMailer($mailer);
            }

            $fromAddress = ($siteSetting && $siteSetting->mail_from_address) ? $siteSetting->mail_from_address : (($siteSetting && $siteSetting->mail_username) ? $siteSetting->mail_username : 'no-reply@jobsportal.com');
            $fromName = ($siteSetting && $siteSetting->mail_from_name) ? $siteSetting->mail_from_name : $siteName;

            \Illuminate\Support\Facades\Mail::raw("Hello,\n\nThis is a live test email sent from {$siteName} Admin Panel.\n\nIf you received this message, your SMTP Mail Server settings are configured and functioning 100% properly.\n\nTimestamp: " . \Carbon\Carbon::now()->toDateTimeString(), function ($msg) use ($testEmail, $siteName, $fromAddress, $fromName) {
                $msg->from($fromAddress, $fromName);
                $msg->to($testEmail)->subject("SMTP Configuration Test Success - {$siteName}");
            });

            return response()->json([
                'status' => 'ok',
                'message' => "Test email sent successfully to '{$testEmail}'! Please check your inbox / spam folder."
            ]);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if (strpos($msg, 'Process could not be started') !== false || strpos($msg, 'cannot find the path') !== false) {
                $msg = "Please set 'Type' to 'SMTP', set 'MAIL PORT' to 587 (for TLS) or 465 (for SSL), and click 'Update' first.";
            }
            return response()->json([
                'status' => 'error',
                'message' => 'SMTP Test Failed: ' . $msg
            ], 500);
        }
    }

    /**
     * OTP Security & Anti-Fraud Logs View
     */
    public function otpSecurityLogs(Request $request)
    {
        $query = \App\LoginOtp::orderBy('id', 'desc');

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function($q) use ($s) {
                $q->where('email', 'like', "%{$s}%")
                  ->orWhere('ip_address', 'like', "%{$s}%");
            });
        }

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->input('user_type'));
        }

        $logs = $query->paginate(20);
        $blockedDomains = \App\BlockedEmailDomain::orderBy('id', 'desc')->get();

        $totalOtps = \App\LoginOtp::count();
        $todayOtps = \App\LoginOtp::whereDate('created_at', \Carbon\Carbon::today())->count();
        $failedAttempts = \App\LoginOtp::where('attempts', '>', 0)->count();

        return view('admin.security.otp_logs', compact('logs', 'blockedDomains', 'totalOtps', 'todayOtps', 'failedAttempts'));
    }

    /**
     * Add Custom Blocked Domain
     */
    public function addBlockedDomain(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|max:191|unique:blocked_email_domains,domain',
            'reason' => 'nullable|string|max:191'
        ]);

        $domain = trim(strtolower($request->input('domain')));
        $domain = str_replace(['http://', 'https://', '@', 'www.'], '', $domain);

        \App\BlockedEmailDomain::create([
            'domain' => $domain,
            'reason' => $request->input('reason', 'fraud_prevention'),
            'is_active' => 1
        ]);

        flash("Domain '{$domain}' added to blocked list successfully!")->success();
        return redirect()->back();
    }

    /**
     * Delete Custom Blocked Domain
     */
    public function deleteBlockedDomain($id)
    {
        $domain = \App\BlockedEmailDomain::findOrFail($id);
        $domainName = $domain->domain;
        $domain->delete();

        flash("Domain '{$domainName}' removed from blocked list.")->success();
        return redirect()->back();
    }

    private function deletePageTitleBgImage($id)
    {
        $siteSetting = SiteSetting::findOrFail($id);
        $image = $siteSetting->page_title_bg_image;
        if (!empty($image)) {
            File::delete(public_path('sitesetting_images/' . $image));
            File::delete(public_path('sitesetting_images/thumb/' . $image));
            File::delete(public_path('sitesetting_images/mid/' . $image));
        }
    }
}

