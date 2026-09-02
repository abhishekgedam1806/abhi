@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 

<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Transaction History & Invoices')]) 
<!-- Inner Page Title end -->

<div class="listpgWraper" style="background: #F8FAFC; padding: 40px 0;">
    <div class="container">
        <div class="row">
            @include('includes.company_dashboard_menu')

            <div class="col-md-9 col-sm-8"> 
                <div class="myads" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 28px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #F1F5F9; padding-bottom: 16px;">
                        <div>
                            <h3 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0;">
                                <i class="fa fa-credit-card" style="color: #2563EB;"></i> {{ __('Payment History & Invoices') }}
                            </h3>
                            <p style="color: #64748B; font-size: 13.5px; margin: 4px 0 0 0;">
                                {{ __('View all your package purchases, active subscriptions and download invoices.') }}
                            </p>
                        </div>
                    </div>

                    @if(isset($orders) && count($orders))
                    <div class="table-responsive">
                        <table class="table" style="width: 100%; border-collapse: separate; border-spacing: 0 8px;">
                            <thead>
                                <tr style="background: #F8FAFC; color: #475569; font-size: 12.5px; text-transform: uppercase;">
                                    <th style="padding: 12px; border: none; border-radius: 8px 0 0 8px;">{{ __('Order Details') }}</th>
                                    <th style="padding: 12px; border: none;">{{ __('Package') }}</th>
                                    <th style="padding: 12px; border: none;">{{ __('Amount') }}</th>
                                    <th style="padding: 12px; border: none;">{{ __('Status') }}</th>
                                    <th style="padding: 12px; border: none;">{{ __('Date') }}</th>
                                    <th style="padding: 12px; border: none; text-align: right; border-radius: 0 8px 8px 0;">{{ __('Invoice') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                @php $payment = $order->latestPayment; @endphp
                                <tr style="background: #FFFFFF; box-shadow: 0 1px 3px rgba(0,0,0,0.03); border: 1px solid #E2E8F0;">
                                    <td style="padding: 14px 12px; vertical-align: middle; border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9;">
                                        <div style="font-weight: 700; color: #0F172A; font-size: 14px;">{{ $order->order_number }}</div>
                                        <div style="font-size: 11.5px; color: #64748B;">
                                            {{ strtoupper($order->gateway) }} 
                                            @if($payment && $payment->gateway_payment_id)
                                                &bull; {{ $payment->gateway_payment_id }}
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 14px 12px; vertical-align: middle; border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9;">
                                        <span style="font-weight: 700; color: #2563EB; font-size: 13.5px;">{{ $order->package_title }}</span>
                                        <div style="font-size: 11.5px; color: #64748B;">{{ ucfirst($order->package_type) }}</div>
                                    </td>
                                    <td style="padding: 14px 12px; vertical-align: middle; border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9; font-weight: 800; color: #03855c; font-size: 14px;">
                                        ₹{{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td style="padding: 14px 12px; vertical-align: middle; border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9;">
                                        @if($order->status == 'paid')
                                            <span class="badge" style="background: #ECFDF5; color: #03855c; border: 1px solid #A7F3D0; font-weight: 700; padding: 5px 10px; border-radius: 6px;">
                                                <i class="fa fa-check"></i> PAID
                                            </span>
                                        @elseif($order->status == 'pending')
                                            <span class="badge" style="background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; font-weight: 700; padding: 5px 10px; border-radius: 6px;">
                                                <i class="fa fa-clock-o"></i> PENDING
                                            </span>
                                        @elseif($order->status == 'failed')
                                            <span class="badge" style="background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; font-weight: 700; padding: 5px 10px; border-radius: 6px;">
                                                <i class="fa fa-times"></i> FAILED
                                            </span>
                                        @else
                                            <span class="badge" style="background: #F1F5F9; color: #475569; padding: 5px 10px; border-radius: 6px;">
                                                {{ strtoupper($order->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 14px 12px; vertical-align: middle; border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9; font-size: 12.5px; color: #64748B;">
                                        {{ date('d M Y', strtotime($order->created_at)) }}
                                    </td>
                                    <td style="padding: 14px 12px; vertical-align: middle; border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9; text-align: right;">
                                        @if($order->status == 'paid')
                                            <a href="{{ route('payment.invoice', ['order_number' => $order->order_number]) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="font-size: 12px; font-weight: 700; border-radius: 6px; padding: 4px 10px;">
                                                <i class="fa fa-download"></i> Invoice
                                            </a>
                                        @elseif($order->status == 'pending' || $order->status == 'failed')
                                            <a href="{{ route('payment.checkout', ['id' => $order->package_id]) }}" class="btn btn-sm btn-primary" style="font-size: 12px; font-weight: 700; border-radius: 6px; padding: 4px 10px; background: #2563EB;">
                                                <i class="fa fa-refresh"></i> Pay Now
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        {{ $orders->links() }}
                    </div>

                    @else
                    <div style="text-align: center; padding: 50px 20px;">
                        <i class="fa fa-credit-card-alt fa-3x" style="color: #CBD5E1; margin-bottom: 16px;"></i>
                        <h4 style="color: #334155; font-weight: 700;">{{ __('No Transactions Found') }}</h4>
                        <p style="color: #64748B; font-size: 14px;">{{ __('You have not made any package purchases yet.') }}</p>
                        <a href="{{ route('company.home') }}#packages" class="btn btn-primary" style="background: #2563EB; font-weight: 700; border-radius: 8px; margin-top: 10px;">
                            {{ __('Explore Packages') }}
                        </a>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection
