@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-bar" style="background: transparent; border: none; box-shadow: none; padding: 0 0 16px 0;">
            <ul class="page-breadcrumb" style="padding: 0; margin: 0; font-size: 13px;">
                <li><a href="{{ route('admin.home') }}" style="color: #64748B; text-decoration: none;"><i class="fa fa-home"></i> Home</a> <i class="fa fa-angle-right" style="color: #CBD5E1; margin: 0 6px;"></i></li>
                <li><a href="{{ route('admin.offers.index') }}" style="color: #64748B; text-decoration: none;">Offers & Coupons</a> <i class="fa fa-angle-right" style="color: #CBD5E1; margin: 0 6px;"></i></li>
                <li><span style="color: #0F172A; font-weight: 700;">Edit Offer: {{ $offer->name }}</span></li>
            </ul>
        </div>

        @include('flash::message')

        <!-- Campaign Analytics Card -->
        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-12">
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 18px 24px; margin-bottom: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                    <div style="font-size: 13px; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 12px;">
                        <i class="fa fa-line-chart text-primary"></i> Live Campaign Analytics
                    </div>
                    <div class="row text-center">
                        <div class="col-md-3 col-6" style="border-right: 1px solid #F1F5F9;">
                            <div style="font-size: 20px; font-weight: 800; color: #0F172A;">{{ number_format($analytics['total_redemptions']) }}</div>
                            <div style="font-size: 12px; color: #64748B;">Total Redemptions</div>
                        </div>
                        <div class="col-md-3 col-6" style="border-right: 1px solid #F1F5F9;">
                            <div style="font-size: 20px; font-weight: 800; color: #D97706;">₹{{ number_format($analytics['total_discount_given'], 0) }}</div>
                            <div style="font-size: 12px; color: #64748B;">Discount Given</div>
                        </div>
                        <div class="col-md-3 col-6" style="border-right: 1px solid #F1F5F9;">
                            <div style="font-size: 20px; font-weight: 800; color: #059669;">₹{{ number_format($analytics['total_revenue'], 0) }}</div>
                            <div style="font-size: 12px; color: #64748B;">Revenue Generated</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div style="font-size: 20px; font-weight: 800; color: #2563EB;">₹{{ number_format($analytics['average_order_value'], 0) }}</div>
                            <div style="font-size: 12px; color: #64748B;">Avg Order Value</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-12">
                <div class="portlet light bordered" style="border: 1px solid #E2E8F0; border-radius: 12px; padding: 24px; background: #FFFFFF; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    
                    <div class="portlet-title" style="border-bottom: 1px solid #F1F5F9; padding-bottom: 16px; margin-bottom: 20px;">
                        <div class="caption" style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                            <div>
                                <h2 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 0;">
                                    <i class="fa fa-pencil text-primary"></i> Edit Promotional Campaign
                                </h2>
                                <span style="font-size: 12.5px; color: #64748B; margin-top: 4px; display: block;">
                                    Created on {{ $offer->created_at->format('d M Y') }} &bull; Status: <strong>{{ $offer->computed_status }}</strong>
                                </span>
                            </div>
                            <a href="{{ route('admin.offers.index') }}" class="btn btn-default btn-sm" style="border-radius: 6px; font-weight: 700;">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <div class="portlet-body form">
                        <form method="POST" action="{{ route('admin.offers.update', $offer->id) }}" class="form-horizontal">
                            @csrf
                            @method('PUT')
                            
                            @include('admin.offer.forms.form')

                            <div class="form-actions" style="border-top: 1px solid #F1F5F9; padding-top: 20px; margin-top: 20px; text-align: right;">
                                <a href="{{ route('admin.offers.index') }}" class="btn btn-default" style="border-radius: 8px; font-weight: 700; margin-right: 8px;">Cancel</a>
                                <button type="submit" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; font-weight: 700; padding: 10px 24px;">
                                    <i class="fa fa-save"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
