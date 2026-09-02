@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        
        <!-- BEGIN PAGE HEADER-->
        <h1 class="page-title">
            <i class="fa fa-file-text-o text-primary"></i> Order Details: {{ $order->order_number }}
        </h1>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><a href="{{ route('admin.home') }}">Home</a><i class="fa fa-angle-right"></i></li>
                <li><a href="{{ route('admin.payment.index') }}">Payments</a><i class="fa fa-angle-right"></i></li>
                <li><span>{{ $order->order_number }}</span></li>
            </ul>
        </div>
        <!-- END PAGE HEADER-->

        @include('flash::message')

        <div class="row">
            {{-- Left Column: Order & Package Info --}}
            <div class="col-md-7">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-basket font-dark"></i>
                            <span class="caption-subject bold uppercase">Order & Package Summary</span>
                        </div>
                        <div class="actions">
                            <a href="{{ route('payment.invoice', $order->order_number) }}" target="_blank" class="btn btn-sm btn-default">
                                <i class="fa fa-print"></i> View / Print Invoice
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 35%;">Order Number</th>
                                <td><strong>{{ $order->order_number }}</strong></td>
                            </tr>
                            <tr>
                                <th>Order Status</th>
                                <td>
                                    @if($order->status == 'paid')
                                        <span class="label label-success" style="background:#10B981;color:#fff;padding:4px 8px;font-weight:700;">PAID</span>
                                    @elseif($order->status == 'pending')
                                        <span class="label label-warning" style="background:#F59E0B;color:#fff;padding:4px 8px;font-weight:700;">PENDING</span>
                                    @elseif($order->status == 'failed')
                                        <span class="label label-danger" style="background:#EF4444;color:#fff;padding:4px 8px;font-weight:700;">FAILED</span>
                                    @elseif($order->status == 'refunded')
                                        <span class="label label-default" style="background:#6B7280;color:#fff;padding:4px 8px;font-weight:700;">REFUNDED</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Package Title</th>
                                <td><strong>{{ $order->package_title }}</strong> ({{ ucfirst($order->package_type) }})</td>
                            </tr>
                            <tr>
                                <th>Package Price</th>
                                <td>₹{{ number_format($order->package_price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Discount</th>
                                <td>-₹{{ number_format($order->discount_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Tax / GST</th>
                                <td>+₹{{ number_format($order->tax_amount, 2) }}</td>
                            </tr>
                            <tr class="active">
                                <th>Total Paid Amount</th>
                                <td style="font-size: 16px; font-weight: 800; color: #2563EB;">
                                    ₹{{ number_format($order->total_amount, 2) }} {{ $order->currency }}
                                </td>
                            </tr>
                            <tr>
                                <th>Gateway Used</th>
                                <td><span class="badge badge-primary">{{ strtoupper($order->gateway) }}</span></td>
                            </tr>
                            <tr>
                                <th>Gateway Order ID</th>
                                <td><code>{{ $order->gateway_order_id ?: 'N/A' }}</code></td>
                            </tr>
                            <tr>
                                <th>Created Date</th>
                                <td>{{ date('d M Y, h:i A', strtotime($order->created_at)) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Buyer / Employer Details --}}
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-user font-dark"></i>
                            <span class="caption-subject bold uppercase">Customer / Employer Information</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 35%;">Customer Type</th>
                                <td><span class="label label-info">{{ class_basename($order->payable_type) }}</span></td>
                            </tr>
                            <tr>
                                <th>Name / Company</th>
                                <td><strong>{{ $order->buyer_name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Email Address</th>
                                <td><a href="mailto:{{ $order->buyer_email }}">{{ $order->buyer_email }}</a></td>
                            </tr>
                            <tr>
                                <th>Contact Phone</th>
                                <td>{{ $order->buyer_phone ?: 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right Column: Payment & Refund Details --}}
            <div class="col-md-5">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-credit-card font-dark"></i>
                            <span class="caption-subject bold uppercase">Transaction Details</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        @if($order->payments && count($order->payments))
                            @foreach($order->payments as $payment)
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 14px; margin-bottom: 14px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                    <span style="font-weight: 700; font-size: 13px;">Payment ID:</span>
                                    <code>{{ $payment->gateway_payment_id ?: 'N/A' }}</code>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                    <span>Method:</span>
                                    <strong>{{ strtoupper($payment->payment_method ?: 'ONLINE / UPI') }}</strong>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                    <span>Amount:</span>
                                    <strong>₹{{ number_format($payment->amount, 2) }}</strong>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                    <span>Status:</span>
                                    <span class="label {{ $payment->payment_status == 'paid' ? 'label-success' : 'label-danger' }}">{{ strtoupper($payment->payment_status) }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Paid Date:</span>
                                    <span>{{ $payment->paid_at ? date('d M Y, h:i A', strtotime($payment->paid_at)) : 'N/A' }}</span>
                                </div>
                            </div>
                            @endforeach

                            {{-- Refund Button if order is paid --}}
                            @if($order->status === 'paid' && APAuthHelp::check(['SUP_ADM']))
                                <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#refundModal" style="margin-top: 15px; font-weight: 700;">
                                    <i class="fa fa-undo"></i> Initiate Refund via Gateway
                                </button>
                            @endif

                        @else
                            <div class="alert alert-warning">No completed payment record logged for this order.</div>
                        @endif
                    </div>
                </div>

                {{-- Raw Gateway JSON --}}
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-code font-dark"></i>
                            <span class="caption-subject bold uppercase">Gateway Audit Log</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        @php $latestPay = $order->latestPayment; @endphp
                        @if($latestPay && $latestPay->raw_response)
                            <pre style="max-height: 250px; overflow-y: auto; font-size: 11px;">{{ json_encode(json_decode($latestPay->raw_response), JSON_PRETTY_PRINT) }}</pre>
                        @else
                            <p class="text-muted">No raw gateway response recorded.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Refund Modal --}}
@if($order->status === 'paid')
<div class="modal fade" id="refundModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('admin.payment.refund', $order->id) }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title font-red-sunglo"><i class="fa fa-warning"></i> Confirm Payment Refund</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>Warning:</strong> Refunding this transaction will call Razorpay API to return the funds directly to the customer's payment method (UPI / Bank / Card).
                    </div>
                    <div class="form-group">
                        <label>Refund Amount (INR):</label>
                        <input type="number" step="0.01" max="{{ $order->total_amount }}" name="amount" class="form-control" value="{{ $order->total_amount }}" required>
                    </div>
                    <div class="form-group">
                        <label>Reason for Refund:</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="e.g. Customer cancellation request"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> Process Gateway Refund</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
