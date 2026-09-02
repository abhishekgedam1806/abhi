<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Order;
use App\Payment;
use App\PaymentRefund;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;

class PaymentManagementController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $statusCounts = [
            'total'    => Order::count(),
            'paid'     => Order::where('status', 'paid')->count(),
            'pending'  => Order::where('status', 'pending')->count(),
            'failed'   => Order::where('status', 'failed')->count(),
            'refunded' => Order::where('status', 'refunded')->count(),
            'total_revenue' => Order::where('status', 'paid')->sum('total_amount'),
        ];

        return view('admin.payment.index', compact('statusCounts'));
    }

    public function fetchOrdersData(Request $request)
    {
        $orders = Order::with(['payments', 'package'])
            ->select('orders.*');

        return DataTables::of($orders)
            ->filter(function ($query) use ($request) {
                if ($request->filled('status')) {
                    $query->where('orders.status', $request->get('status'));
                }
                if ($request->filled('gateway')) {
                    $query->where('orders.gateway', $request->get('gateway'));
                }
                if ($request->filled('package_type')) {
                    $query->where('orders.package_type', $request->get('package_type'));
                }
                if ($request->filled('search_term')) {
                    $term = $request->get('search_term');
                    $query->where(function ($q) use ($term) {
                        $q->where('orders.order_number', 'LIKE', "%{$term}%")
                          ->orWhere('orders.gateway_order_id', 'LIKE', "%{$term}%")
                          ->orWhere('orders.package_title', 'LIKE', "%{$term}%");
                    });
                }
            })
            ->addColumn('buyer_info', function ($order) {
                $name = e($order->buyer_name);
                $email = e($order->buyer_email);
                $type = ucfirst($order->package_type);
                return "<strong>{$name}</strong><br><small class='text-muted'>{$email}</small><br><span class='badge badge-info'>{$type}</span>";
            })
            ->addColumn('package_info', function ($order) {
                $pkg = e($order->package_title);
                $amt = '₹' . number_format($order->total_amount, 2);
                return "<strong>{$pkg}</strong><br><span class='text-success font-weight-bold'>{$amt}</span>";
            })
            ->addColumn('gateway_info', function ($order) {
                $gw = strtoupper(e($order->gateway));
                $gid = e($order->gateway_order_id ?: 'N/A');
                return "<span class='badge badge-primary'>{$gw}</span><br><small class='text-muted'>{$gid}</small>";
            })
            ->addColumn('status_badge', function ($order) {
                switch ($order->status) {
                    case 'paid':
                        return '<span class="label label-success" style="background:#10B981;color:#fff;padding:4px 8px;border-radius:4px;font-weight:700;">PAID</span>';
                    case 'pending':
                        return '<span class="label label-warning" style="background:#F59E0B;color:#fff;padding:4px 8px;border-radius:4px;font-weight:700;">PENDING</span>';
                    case 'failed':
                        return '<span class="label label-danger" style="background:#EF4444;color:#fff;padding:4px 8px;border-radius:4px;font-weight:700;">FAILED</span>';
                    case 'refunded':
                        return '<span class="label label-default" style="background:#6B7280;color:#fff;padding:4px 8px;border-radius:4px;font-weight:700;">REFUNDED</span>';
                    default:
                        return '<span class="label label-info">' . strtoupper($order->status) . '</span>';
                }
            })
            ->addColumn('action', function ($order) {
                $viewUrl = route('admin.payment.detail', $order->id);
                $invoiceUrl = route('payment.invoice', $order->order_number);
                return "<div class='btn-group'>
                    <a href='{$viewUrl}' class='btn btn-xs btn-primary'><i class='fa fa-eye'></i> View</a>
                    <a href='{$invoiceUrl}' target='_blank' class='btn btn-xs btn-default'><i class='fa fa-file-text-o'></i> Invoice</a>
                </div>";
            })
            ->rawColumns(['buyer_info', 'package_info', 'gateway_info', 'status_badge', 'action'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
    }

    public function show($id)
    {
        $order = Order::with(['payments.refunds', 'package'])->findOrFail($id);
        return view('admin.payment.detail', compact('order'));
    }

    public function processRefund(Request $request, $order_id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $order = Order::with('payments')->findOrFail($order_id);
        $payment = $order->payments()->where('payment_status', 'paid')->latest()->first();

        if (!$payment) {
            flash(__('No active paid transaction found for this order to refund.'))->error();
            return redirect()->back();
        }

        $amount = floatval($request->input('amount'));
        $reason = $request->input('reason', 'Admin initiated refund');

        $result = $this->paymentService->processRefund($payment, $amount, $reason);

        if ($result['success']) {
            flash(__('Refund processed successfully! Refund ID: ') . ($result['refund_id'] ?? ''))->success();
        } else {
            flash(__('Refund failed: ') . ($result['error'] ?? ''))->error();
        }

        return redirect()->back();
    }
}
