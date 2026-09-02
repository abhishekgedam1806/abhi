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
                <h1 style="font-size: 24px; font-weight: 800; color: #FFFFFF !important; margin: 0;">Customer Leads: {{ $business->name }}</h1>
                <p style="color: #E2E8F0 !important; font-size: 13.5px; margin-top: 4px; margin-bottom: 0;">Direct inquiries, phone clicks, and WhatsApp requests from local customers.</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('my.businesses') }}" class="btn btn-default" style="font-weight: 600; border-radius: 8px;">
                    <i class="fa fa-arrow-left"></i> Back to My Businesses
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
                <div style="background: #fff; border: 1px solid #E2E8F0; border-radius: 14px; padding: 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px; margin-bottom: 20px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: #0F172A; margin: 0;">
                            <i class="fa fa-inbox" style="color:#2563EB;"></i> Inquiries & Leads ({{ $leads->total() }})
                        </h3>
                    </div>

                    @forelse($leads as $lead)
                    <div style="border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px; margin-bottom: 12px; background: #FAFBFC;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
                            <div>
                                <span style="font-size: 15px; font-weight: 700; color: #0F172A;">
                                    {{ $lead->sender_name ?: 'Visitor / Customer' }}
                                </span>
                                @if($lead->lead_type === 'call')
                                <span style="background: #EFF6FF; color: #2563EB; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; margin-left: 6px;"><i class="fa fa-phone"></i> Phone Click</span>
                                @elseif($lead->lead_type === 'whatsapp')
                                <span style="background: #ECFDF5; color: #03855c; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; margin-left: 6px;"><i class="fa fa-whatsapp"></i> WhatsApp Click</span>
                                @elseif($lead->lead_type === 'directions')
                                <span style="background: #F1F5F9; color: #64748B; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; margin-left: 6px;"><i class="fa fa-map-marker"></i> Directions Click</span>
                                @else
                                <span style="background: #FAF5FF; color: #9333EA; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; margin-left: 6px;"><i class="fa fa-envelope-o"></i> Message Inquiry</span>
                                @endif
                            </div>
                            <span style="font-size: 12px; color: #94A3B8;">
                                <i class="fa fa-clock-o"></i> {{ $lead->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <div style="font-size: 13px; color: #475569; display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 8px;">
                            @if($lead->sender_phone)
                            <div><i class="fa fa-phone text-primary"></i> <a href="tel:{{ $lead->sender_phone }}" style="color:#2563EB;font-weight:600;">{{ $lead->sender_phone }}</a></div>
                            @endif
                            @if($lead->sender_email)
                            <div><i class="fa fa-envelope text-muted"></i> {{ $lead->sender_email }}</div>
                            @endif
                        </div>

                        @if($lead->message)
                        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 10px 12px; font-size: 13px; color: #334155; margin-top: 6px;">
                            <strong>Message:</strong> {{ $lead->message }}
                        </div>
                        @endif
                    </div>
                    @empty
                    <div style="text-align: center; padding: 40px 20px;">
                        <i class="fa fa-inbox" style="font-size: 36px; color: #CBD5E1; margin-bottom: 10px;"></i>
                        <h4 style="font-size: 15px; font-weight: 700; color: #334155;">No leads received yet</h4>
                        <p style="color: #94A3B8; font-size: 13px;">Customer calls, WhatsApp inquiries, and messages will show up here.</p>
                    </div>
                    @endforelse

                    <div class="mt-3">
                        {!! $leads->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection
