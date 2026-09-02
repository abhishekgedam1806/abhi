@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title -->
<div class="pageTitle" style="background: #0F172A; padding: 32px 0; color: #FFFFFF !important;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 style="font-size: 24px; font-weight: 800; color: #FFFFFF !important; margin: 0;">My Businesses</h1>
                <p style="color: #E2E8F0 !important; font-size: 13.5px; margin-top: 4px; margin-bottom: 0;">Manage your business listings, verify NAP details, and view customer leads.</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('add.business') }}" class="btn btn-primary" style="font-weight: 700; border-radius: 8px; padding: 9px 18px; background: #2563EB;">
                    <i class="fa fa-plus-circle"></i> Add New Business
                </a>
            </div>
        </div>
    </div>
</div>

<div class="listpgWraper" style="background: #F8FAFC; padding: 40px 0 60px;">
    <div class="container">
        <div class="row">
            @include('includes.business_dashboard_menu')

            <div class="col-lg-9 col-md-8">
                <div class="myads" style="background: #fff; border: 1px solid #E2E8F0; border-radius: 14px; padding: 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 16px; margin-bottom: 20px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: #0F172A; margin: 0;">
                            <i class="fa fa-building-o" style="color:#2563EB;"></i> Listed Businesses ({{ $businesses->total() }})
                        </h3>
                    </div>

                    @include('flash::message')

                    @forelse($businesses as $biz)
                    <div style="border: 1px solid #E2E8F0; border-radius: 12px; padding: 18px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; background: #FAFBFC;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 50px; height: 50px; border-radius: 10px; background: #EEF2FF; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #2563EB; flex-shrink: 0; overflow: hidden; border: 1px solid #E2E8F0;">
                                @if($biz->logo)
                                <img src="{{ $biz->getLogoUrl() }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                <i class="fa fa-building-o"></i>
                                @endif
                            </div>
                            <div>
                                <h4 style="font-size: 16px; font-weight: 700; color: #0F172A; margin: 0 0 4px;">
                                    <a href="{{ route('business.detail', $biz->slug) }}" target="_blank" style="color: #0F172A; text-decoration: none;">{{ $biz->name }}</a>
                                </h4>
                                <div style="font-size: 12.5px; color: #64748B;">
                                    <span><i class="fa fa-folder-o"></i> {{ $biz->category ? $biz->category->name : 'Uncategorized' }}</span> • 
                                    <span><i class="fa fa-map-marker"></i> {{ $biz->getLocationLabel() }}</span>
                                </div>
                                <div style="margin-top: 6px; display: flex; gap: 6px; align-items: center;">
                                    @if($biz->verification_status === 'verified')
                                    <span style="background: #ECFDF5; color: #03855c; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; border: 1px solid #A7F3D0;"><i class="fa fa-check-circle"></i> Verified</span>
                                    @elseif($biz->verification_status === 'pending')
                                    <span style="background: #FEF3C7; color: #D97706; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; border: 1px solid #FDE68A;"><i class="fa fa-clock-o"></i> Pending Review</span>
                                    @else
                                    <span style="background: #F1F5F9; color: #64748B; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 12px; border: 1px solid #CBD5E1;">Unverified</span>
                                    @endif

                                    <span style="font-size: 12px; color: #64748B; margin-left: 6px;">
                                        <i class="fa fa-eye"></i> {{ $biz->views_count }} Views • <i class="fa fa-envelope-o"></i> {{ $biz->leads_count }} Leads
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <a href="{{ route('business.leads', $biz->id) }}" class="btn btn-sm btn-info" style="font-weight: 600; border-radius: 6px; font-size: 12px;">
                                <i class="fa fa-envelope-o"></i> Leads ({{ $biz->leads_count }})
                            </a>
                            <a href="{{ route('edit.business', $biz->id) }}" class="btn btn-sm btn-outline-primary" style="font-weight: 600; border-radius: 6px; font-size: 12px;">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                            <a href="{{ route('business.detail', $biz->slug) }}" target="_blank" class="btn btn-sm btn-default" style="font-weight: 600; border-radius: 6px; font-size: 12px;">
                                <i class="fa fa-external-link"></i> View
                            </a>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 40px 20px;">
                        <i class="fa fa-building-o" style="font-size: 40px; color: #CBD5E1; margin-bottom: 12px;"></i>
                        <h4 style="font-size: 15px; font-weight: 700; color: #334155;">You haven't listed any businesses yet</h4>
                        <p style="color: #94A3B8; font-size: 13px; margin-bottom: 18px;">List your business to get local customers, phone calls, and WhatsApp inquiries.</p>
                        <a href="{{ route('add.business') }}" class="btn btn-primary btn-sm" style="font-weight: 700; border-radius: 6px;">
                            <i class="fa fa-plus"></i> Add Your First Business
                        </a>
                    </div>
                    @endforelse

                    <div class="mt-3">
                        {!! $businesses->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection
