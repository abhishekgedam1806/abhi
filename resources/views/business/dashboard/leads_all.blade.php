@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title -->
<div class="pageTitle" style="background: #0F172A; padding: 32px 0; color: #fff;">
    <div class="container">
        <h1 style="font-size: 24px; font-weight: 800; color: #FFFFFF; margin: 0;">Business Leads Inbox</h1>
        <p style="color: #94A3B8; font-size: 13.5px; margin-top: 4px; margin-bottom: 0;">Track all customer calls, WhatsApp inquiries, and quote requests across your businesses.</p>
    </div>
</div>

<div class="listpgWraper" style="background: #F8FAFC; padding: 36px 0 60px;">
    <div class="container">
        @include('flash::message')
        <div class="row">
            {{-- Dedicated Business Dashboard Menu --}}
            @include('includes.business_dashboard_menu')

            <div class="col-lg-9 col-md-8">
                <div style="background:#fff;border-radius:16px;border:1px solid #E2E8F0;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,0.03);">
                    
                    {{-- Filter Bar --}}
                    <form method="GET" action="{{ route('business.all.leads') }}" class="mb-4">
                        <div class="row" style="align-items:flex-end;">
                            <div class="col-md-5 form-group">
                                <label style="font-size:12.5px;font-weight:700;color:#475569;">Filter by Business</label>
                                <select name="business_id" class="form-control" style="height:40px;border-radius:8px;font-size:13px;" onchange="this.form.submit()">
                                    <option value="">All Businesses</option>
                                    @foreach($myBusinesses as $bId => $bName)
                                    <option value="{{ $bId }}" {{ request('business_id') == $bId ? 'selected' : '' }}>{{ $bName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label style="font-size:12.5px;font-weight:700;color:#475569;">Lead Type</label>
                                <select name="lead_type" class="form-control" style="height:40px;border-radius:8px;font-size:13px;" onchange="this.form.submit()">
                                    <option value="">All Types (Calls, WhatsApp, Messages)</option>
                                    <option value="call" {{ request('lead_type') == 'call' ? 'selected' : '' }}>Phone Calls</option>
                                    <option value="whatsapp" {{ request('lead_type') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                    <option value="inquiry" {{ request('lead_type') == 'inquiry' ? 'selected' : '' }}>Messages / Quotes</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <a href="{{ route('business.all.leads') }}" class="btn btn-default btn-block" style="height:40px;line-height:26px;border-radius:8px;font-weight:600;font-size:13px;">
                                    Reset Filters
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- Leads Table --}}
                    @if($leads->count() > 0)
                    <div class="table-responsive">
                        <table class="table" style="font-size:13px;">
                            <thead>
                                <tr style="background:#F8FAFC;color:#64748B;font-size:12px;font-weight:700;text-transform:uppercase;">
                                    <th style="border:none;border-radius:8px 0 0 8px;">Type</th>
                                    <th style="border:none;">Business</th>
                                    <th style="border:none;">Customer Name</th>
                                    <th style="border:none;">Phone / Contact</th>
                                    <th style="border:none;">Email</th>
                                    <th style="border:none;">Date</th>
                                    <th style="border:none;border-radius:0 8px 8px 0;">Message / Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leads as $lead)
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
                                        <a href="tel:{{ $lead->phone }}" style="color:#0284C7;font-weight:700;"><i class="fa fa-phone"></i> {{ $lead->phone }}</a>
                                        @else
                                        <span style="color:#94A3B8;">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $lead->email ?: '-' }}</td>
                                    <td style="color:#64748B;white-space:nowrap;">{{ $lead->created_at->format('d M Y, h:i A') }}</td>
                                    <td style="color:#475569;max-width:220px;">{{ $lead->message ?: '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $leads->appends(request()->query())->links() }}
                    </div>
                    @else
                    <div style="text-align:center;padding:40px 10px;color:#94A3B8;">
                        <i class="fa fa-inbox" style="font-size:42px;color:#CBD5E1;margin-bottom:10px;display:block;"></i>
                        <h4 style="font-size:16px;font-weight:700;color:#334155;margin:0 0 6px;">No Leads Found</h4>
                        <p style="font-size:13px;margin:0;">No customer leads match the selected criteria.</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection
