@extends('admin.layouts.admin_layout')
@section('content')
<style>
.kpi-mini-box {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 16px;
}
.kpi-mini-val {
    font-size: 20px;
    font-weight: 800;
    color: #0F172A;
}
.kpi-mini-lbl {
    font-size: 12px;
    color: #64748B;
    font-weight: 600;
}
.code-pill {
    background: #EFF6FF;
    border: 1px dashed #3B82F6;
    color: #1E40AF;
    font-family: monospace;
    font-size: 12.5px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 4px;
}
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-bar" style="background: transparent; border: none; box-shadow: none; padding: 0 0 16px 0;">
            <ul class="page-breadcrumb" style="padding: 0; margin: 0; font-size: 13px;">
                <li><a href="{{ route('admin.home') }}" style="color: #64748B; text-decoration: none;"><i class="fa fa-home"></i> Home</a> <i class="fa fa-angle-right" style="color: #CBD5E1; margin: 0 6px;"></i></li>
                <li><a href="{{ route('admin.payment.index') }}" style="color: #64748B; text-decoration: none;">Payment Management</a> <i class="fa fa-angle-right" style="color: #CBD5E1; margin: 0 6px;"></i></li>
                <li><a href="{{ route('admin.offers.index') }}" style="color: #64748B; text-decoration: none;">Offers & Coupons</a> <i class="fa fa-angle-right" style="color: #CBD5E1; margin: 0 6px;"></i></li>
                <li><span style="color: #0F172A; font-weight: 700;">Coupon Redemptions</span></li>
            </ul>
        </div>

        <!-- Header Title -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h1 class="page-title" style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0;">
                    <i class="fa fa-history text-primary"></i> Coupon Redemptions & Audit Log
                </h1>
                <p style="font-size: 13px; color: #64748B; margin: 4px 0 0 0;">
                    Permanent financial audit log of all successful customer promo code redemptions.
                </p>
            </div>
            <a href="{{ route('admin.offers.index') }}" class="btn btn-default" style="border-radius: 8px; font-weight: 700; background: #FFFFFF; border: 1px solid #CBD5E1; color: #334155;">
                <i class="fa fa-tags"></i> Manage Offers
            </a>
        </div>

        <!-- Filtered Summary KPI Box -->
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="kpi-mini-box">
                    <div class="kpi-mini-val" style="color: #2563EB;">{{ number_format($totalRedemptions) }}</div>
                    <div class="kpi-mini-lbl">Successful Redemptions</div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="kpi-mini-box">
                    <div class="kpi-mini-val" style="color: #D97706;">₹{{ number_format($totalDiscountGiven, 2) }}</div>
                    <div class="kpi-mini-lbl">Total Discount Applied</div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="kpi-mini-box">
                    <div class="kpi-mini-val" style="color: #059669;">₹{{ number_format($totalRevenue, 2) }}</div>
                    <div class="kpi-mini-lbl">Total Net Revenue Collected</div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="portlet light bordered" style="border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; background: #FFFFFF;">
            <form method="GET" action="{{ route('admin.coupon-redemptions.index') }}" class="form-inline" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                
                <div class="form-group" style="margin: 0;">
                    <select name="offer_id" class="form-control" style="border-radius: 8px; border: 1px solid #CBD5E1;">
                        <option value="">All Campaigns</option>
                        @foreach($offers as $o)
                        <option value="{{ $o->id }}" {{ Request::get('offer_id') == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin: 0;">
                    <input type="text" name="coupon_code" value="{{ Request::get('coupon_code') }}" placeholder="Coupon Code..." class="form-control" style="border-radius: 8px; border: 1px solid #CBD5E1; text-transform: uppercase;">
                </div>

                <div class="form-group" style="margin: 0;">
                    <select name="package_id" class="form-control" style="border-radius: 8px; border: 1px solid #CBD5E1;">
                        <option value="">All Packages</option>
                        @foreach($packages as $p)
                        <option value="{{ $p->id }}" {{ Request::get('package_id') == $p->id ? 'selected' : '' }}>{{ $p->package_title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin: 0;">
                    <input type="date" name="date_from" value="{{ Request::get('date_from') }}" class="form-control" title="From Date" style="border-radius: 8px; border: 1px solid #CBD5E1;">
                </div>

                <div class="form-group" style="margin: 0;">
                    <input type="date" name="date_to" value="{{ Request::get('date_to') }}" class="form-control" title="To Date" style="border-radius: 8px; border: 1px solid #CBD5E1;">
                </div>

                <button type="submit" class="btn btn-default" style="border-radius: 8px; font-weight: 700; background: #F8FAFC; border: 1px solid #CBD5E1;">
                    <i class="fa fa-filter"></i> Filter
                </button>

                @if(Request::filled('offer_id') || Request::filled('coupon_code') || Request::filled('package_id') || Request::filled('date_from') || Request::filled('date_to'))
                <a href="{{ route('admin.coupon-redemptions.index') }}" class="btn btn-link" style="color: #64748B; font-size: 13px;">Clear Filter</a>
                @endif

            </form>
        </div>

        <!-- Redemptions Audit Table -->
        <div class="portlet light bordered" style="border: 1px solid #E2E8F0; border-radius: 12px; padding: 0; overflow: hidden; background: #FFFFFF;">
            <div class="table-responsive">
                <table class="table table-hover" style="margin: 0; vertical-align: middle;">
                    <thead style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                        <tr>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">User / Buyer</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Coupon / Offer</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Package</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Original</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Discount</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Final Paid</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Order Ref</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Redeemed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($redemptions as $r)
                            <tr style="border-bottom: 1px solid #F1F5F9;">
                                <td style="padding: 14px 18px;">
                                    <div style="font-weight: 700; color: #0F172A; font-size: 13.5px;">{{ $r->buyer_name }}</div>
                                    <span style="font-size: 11px; color: #64748B; background: #F1F5F9; padding: 2px 6px; border-radius: 4px;">
                                        {{ class_basename($r->payable_type) }} #{{ $r->payable_id }}
                                    </span>
                                </td>
                                <td style="padding: 14px 18px;">
                                    <span class="code-pill">{{ $r->coupon->code ?? 'CODE' }}</span>
                                    @if($r->offer)
                                    <div style="font-size: 11.5px; color: #64748B; margin-top: 2px;">{{ $r->offer->name }}</div>
                                    @endif
                                </td>
                                <td style="padding: 14px 18px; font-size: 13px; font-weight: 600; color: #334155;">
                                    {{ $r->package->package_title ?? 'Package' }}
                                </td>
                                <td style="padding: 14px 18px; font-size: 13px; color: #64748B;">
                                    ₹{{ number_format($r->original_amount, 2) }}
                                </td>
                                <td style="padding: 14px 18px; font-size: 13px; font-weight: 700; color: #DC2626;">
                                    -₹{{ number_format($r->discount_amount, 2) }}
                                </td>
                                <td style="padding: 14px 18px; font-size: 13.5px; font-weight: 800; color: #059669;">
                                    ₹{{ number_format($r->final_amount, 2) }}
                                </td>
                                <td style="padding: 14px 18px; font-size: 12px; color: #475569;">
                                    <div><strong>{{ $r->order->order_number ?? '#' . $r->order_id }}</strong></div>
                                    @if($r->payment_id)
                                    <div style="font-size: 11px; color: #94A3B8;">{{ Str::limit($r->payment_id, 18) }}</div>
                                    @endif
                                </td>
                                <td style="padding: 14px 18px; font-size: 12px; color: #64748B;">
                                    {{ $r->redeemed_at ? $r->redeemed_at->format('d M Y, h:i A') : $r->created_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px 20px; color: #64748B;">
                                    <i class="fa fa-history" style="font-size: 32px; color: #CBD5E1; margin-bottom: 8px; display: block;"></i>
                                    <div style="font-size: 14px; font-weight: 700; color: #1E293B;">No Coupon Redemptions Yet</div>
                                    <p style="font-size: 12.5px; margin-top: 4px;">When customers apply promo codes and complete payment, permanent records will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($redemptions->hasPages())
            <div style="padding: 14px 18px; border-top: 1px solid #F1F5F9; background: #F8FAFC;">
                {!! $redemptions->appends(Request::all())->links() !!}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
