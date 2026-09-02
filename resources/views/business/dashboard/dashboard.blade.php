@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title -->
<div class="pageTitle" style="background: #0F172A; padding: 32px 0; color: #fff;">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 800; color: #FFFFFF; margin: 0;">Business Owner Dashboard</h1>
                <p style="color: #94A3B8; font-size: 13.5px; margin-top: 4px; margin-bottom: 0;">Manage your local business listings, NAP info, customer calls & leads.</p>
            </div>
            <a href="{{ route('add.business') }}" class="btn btn-primary" style="background: #2563EB; border: none; font-weight: 700; border-radius: 10px; padding: 10px 20px;">
                <i class="fa fa-plus-circle"></i> Add New Business
            </a>
        </div>
    </div>
</div>

<style>
.biz-stat-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.15s ease;
}
.biz-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.biz-stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.biz-stat-val {
    font-size: 26px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1;
    margin-bottom: 4px;
}
.biz-stat-label {
    font-size: 12.5px;
    font-weight: 600;
    color: #64748B;
}
</style>

<div class="listpgWraper" style="background: #F8FAFC; padding: 36px 0 60px;">
    <div class="container">
        @include('flash::message')
        <div class="row">
            {{-- Dedicated Business Dashboard Menu --}}
            @include('includes.business_dashboard_menu')

            <div class="col-lg-9 col-md-8">
                
                {{-- SUBSCRIPTION PLAN BANNER --}}
                <div style="background:#fff;border-radius:14px;border:1px solid #E2E8F0;padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:38px;height:38px;border-radius:10px;background:#EFF6FF;color:#2563EB;display:flex;align-items:center;justify-content:center;font-size:18px;">
                            <i class="fa fa-credit-card"></i>
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:800;color:#0F172A;">
                                Plan: <span style="color:#2563EB;">{{ (isset($userPackage) && $userPackage) ? $userPackage->package_title : 'Free Starter' }}</span>
                                @if(isset($userPackage) && $userPackage && $userPackage->is_featured)
                                <span class="badge" style="background:#FEF3C7;color:#D97706;font-size:10.5px;padding:2px 6px;border-radius:8px;margin-left:4px;">Featured Booster</span>
                                @endif
                            </div>
                            <div style="font-size:12px;color:#64748B;">
                                Listing Limit: <strong>{{ $totalBusinesses }} / {{ $totalQuota ?? 1 }}</strong> Used
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('business.packages') }}" class="btn btn-xs btn-primary" style="font-weight:700;border-radius:8px;padding:6px 14px;background:#2563EB;">
                        <i class="fa fa-arrow-circle-up"></i> Upgrade Plan
                    </a>
                </div>

                {{-- STAT METRICS ROW --}}
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="biz-stat-card">
                            <div class="biz-stat-icon" style="background:#EFF6FF;color:#2563EB;">
                                <i class="fa fa-building"></i>
                            </div>
                            <div>
                                <div class="biz-stat-val">{{ $totalBusinesses }}</div>
                                <div class="biz-stat-label">Total Businesses</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="biz-stat-card">
                            <div class="biz-stat-icon" style="background:#ECFDF5;color:#03855c;">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div>
                                <div class="biz-stat-val">{{ $verifiedBusinesses }}</div>
                                <div class="biz-stat-label">Verified Listings</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="biz-stat-card">
                            <div class="biz-stat-icon" style="background:#FFF7ED;color:#EA580C;">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div>
                                <div class="biz-stat-val">{{ $callLeads }}</div>
                                <div class="biz-stat-label">Call Leads</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="biz-stat-card">
                            <div class="biz-stat-icon" style="background:#F0FDF4;color:#16A34A;">
                                <i class="fa fa-whatsapp"></i>
                            </div>
                            <div>
                                <div class="biz-stat-val">{{ $whatsappLeads }}</div>
                                <div class="biz-stat-label">WhatsApp Leads</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MY BUSINESSES QUICK OVERVIEW --}}
                <div style="background:#fff;border-radius:16px;border:1px solid #E2E8F0;padding:24px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,0.03);">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;border-bottom:1px solid #F1F5F9;padding-bottom:12px;">
                        <h3 style="font-size:17px;font-weight:800;color:#0F172A;margin:0;">
                            <i class="fa fa-building text-primary"></i> My Business Listings
                        </h3>
                        <a href="{{ route('my.businesses') }}" style="font-size:13px;font-weight:700;color:#2563EB;text-decoration:none;">
                            View All ({{ $totalBusinesses }}) →
                        </a>
                    </div>

                    @if($businesses->count() > 0)
                    <div class="row">
                        @foreach($businesses as $biz)
                        <div class="col-md-6 mb-3">
                            <div style="border:1px solid #E2E8F0;border-radius:12px;padding:16px;background:#FAFBFC;transition:all 0.15s ease;">
                                <div style="display:flex;align-items:flex-start;gap:12px;">
                                    <div style="width:48px;height:48px;border-radius:10px;background:#fff;border:1px solid #E2E8F0;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                                        @if($biz->logo)
                                        <img src="{{ $biz->getLogoUrl() }}" style="max-width:100%;max-height:100%;object-fit:cover;" alt="{{ $biz->name }}">
                                        @else
                                        <i class="fa fa-building" style="font-size:20px;color:#94A3B8;"></i>
                                        @endif
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <h4 style="font-size:15px;font-weight:700;margin:0;color:#0F172A;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                {{ $biz->name }}
                                            </h4>
                                            @if($biz->verification_status === 'verified')
                                            <i class="fa fa-check-circle text-primary" title="Verified Business"></i>
                                            @endif
                                        </div>
                                        <div style="font-size:12.5px;color:#64748B;margin:3px 0;">
                                            <i class="fa fa-map-marker text-danger"></i> {{ $biz->getLocationLabel() }}
                                        </div>
                                        <div style="display:flex;align-items:center;gap:8px;margin-top:8px;">
                                            <a href="{{ route('edit.business', $biz->id) }}" class="btn btn-xs btn-default" style="font-size:11.5px;font-weight:600;border-radius:6px;">
                                                <i class="fa fa-pencil"></i> Edit
                                            </a>
                                            <a href="{{ route('business.leads', $biz->id) }}" class="btn btn-xs btn-default" style="font-size:11.5px;font-weight:600;border-radius:6px;">
                                                <i class="fa fa-phone"></i> Leads ({{ $biz->leads->count() }})
                                            </a>
                                            <a href="{{ route('business.detail', $biz->slug) }}" target="_blank" class="btn btn-xs btn-default" style="font-size:11.5px;font-weight:600;border-radius:6px;">
                                                <i class="fa fa-external-link"></i> View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div style="text-align:center;padding:30px 10px;">
                        <i class="fa fa-building-o" style="font-size:40px;color:#CBD5E1;margin-bottom:12px;"></i>
                        <h4 style="font-size:16px;font-weight:700;color:#334155;margin:0 0 6px;">No Business Listed Yet</h4>
                        <p style="font-size:13px;color:#94A3B8;margin-bottom:16px;">List your local business now to start receiving customer calls and inquiries.</p>
                        <a href="{{ route('add.business') }}" class="btn btn-primary" style="font-weight:700;border-radius:8px;">
                            <i class="fa fa-plus-circle"></i> Add Your First Business
                        </a>
                    </div>
                    @endif
                </div>

                {{-- RECENT LEADS INBOX --}}
                <div style="background:#fff;border-radius:16px;border:1px solid #E2E8F0;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,0.03);">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;border-bottom:1px solid #F1F5F9;padding-bottom:12px;">
                        <h3 style="font-size:17px;font-weight:800;color:#0F172A;margin:0;">
                            <i class="fa fa-inbox text-primary"></i> Recent Customer Leads
                        </h3>
                        <a href="{{ route('business.all.leads') }}" style="font-size:13px;font-weight:700;color:#2563EB;text-decoration:none;">
                            View All Leads ({{ $totalLeads }}) →
                        </a>
                    </div>

                    @if($recentLeads->count() > 0)
                    <div class="table-responsive">
                        <table class="table" style="margin-bottom:0;font-size:13px;">
                            <thead>
                                <tr style="background:#F8FAFC;color:#64748B;font-size:12px;font-weight:700;text-transform:uppercase;">
                                    <th style="border:none;border-radius:8px 0 0 8px;">Type</th>
                                    <th style="border:none;">Business</th>
                                    <th style="border:none;">Customer Name</th>
                                    <th style="border:none;">Contact</th>
                                    <th style="border:none;">Date / Time</th>
                                    <th style="border:none;border-radius:0 8px 8px 0;">Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLeads as $lead)
                                <tr>
                                    <td>
                                        @if($lead->lead_type === 'call')
                                        <span class="badge" style="background:#EA580C;color:#fff;padding:4px 8px;border-radius:6px;"><i class="fa fa-phone"></i> Call</span>
                                        @elseif($lead->lead_type === 'whatsapp')
                                        <span class="badge" style="background:#16A34A;color:#fff;padding:4px 8px;border-radius:6px;"><i class="fa fa-whatsapp"></i> WhatsApp</span>
                                        @else
                                        <span class="badge" style="background:#2563EB;color:#fff;padding:4px 8px;border-radius:6px;"><i class="fa fa-envelope"></i> Message</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $lead->business ? $lead->business->name : 'N/A' }}</strong></td>
                                    <td>{{ $lead->name ?: 'Direct Click' }}</td>
                                    <td>
                                        @if($lead->phone)
                                        <a href="tel:{{ $lead->phone }}" style="color:#0284C7;font-weight:600;"><i class="fa fa-phone"></i> {{ $lead->phone }}</a>
                                        @else
                                        <span style="color:#94A3B8;">-</span>
                                        @endif
                                    </td>
                                    <td style="color:#64748B;">{{ $lead->created_at->diffForHumans() }}</td>
                                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#475569;">
                                        {{ $lead->message ?: '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div style="text-align:center;padding:24px 10px;color:#94A3B8;font-size:13.5px;">
                        <i class="fa fa-comments-o" style="font-size:32px;color:#CBD5E1;margin-bottom:8px;display:block;"></i>
                        No customer leads received yet. Once users call, WhatsApp, or send inquiries from your business listing, they will appear here.
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection
