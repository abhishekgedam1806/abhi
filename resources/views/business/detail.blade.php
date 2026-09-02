@extends('layouts.app')

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- LocalBusiness JSON-LD Structured Data -->
<script type="application/ld+json">
{!! json_encode($schemaJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
</script>

<style>
/* ==========================================================================
   Business Detail Page — Modern Local Business Profile
   ========================================================================== */
.biz-detail-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
    background: #F8FAFC;
    padding-bottom: 60px;
}

/* Hero Header */
.biz-hero {
    background: #0F172A;
    color: #FFFFFF;
    padding: 40px 0 35px;
    border-bottom: 1px solid #334155;
}
.biz-hero-card {
    display: flex;
    gap: 24px;
    align-items: center;
    flex-wrap: wrap;
}
.biz-hero-logo {
    width: 100px;
    height: 100px;
    background: #FFFFFF;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.25);
    flex-shrink: 0;
}
.biz-hero-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.biz-hero-title {
    font-size: 26px;
    font-weight: 800;
    color: #FFFFFF;
    margin: 0 0 6px;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: -0.4px;
}
.biz-hero-badge-verified {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(16,185,129,0.2);
    color: #34D399;
    border: 1px solid rgba(52,211,153,0.4);
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 12px;
    font-weight: 700;
}
.biz-hero-category {
    font-size: 14px;
    font-weight: 600;
    color: #38BDF8;
    margin-bottom: 8px;
}
.biz-hero-address {
    font-size: 13.5px;
    color: #94A3B8;
    display: flex;
    align-items: center;
    gap: 6px;
}
.biz-hero-actions {
    display: flex;
    gap: 10px;
    margin-top: 18px;
    flex-wrap: wrap;
}
.btn-hero-call {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 13.5px;
    font-weight: 700;
    padding: 9px 20px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.15s ease;
    box-shadow: 0 4px 14px rgba(37,99,235,0.4);
}
.btn-hero-call:hover { background: #1D4ED8; }
.btn-hero-wa {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #03855c !important;
    color: #FFFFFF !important;
    font-size: 13.5px;
    font-weight: 700;
    padding: 9px 20px;
    border-radius: 10px;
    border: none !important;
    text-decoration: none !important;
    box-shadow: none !important;
    transition: none !important;
}
.btn-hero-wa:hover {
    background: #03855c !important;
    color: #FFFFFF !important;
    transform: none !important;
    box-shadow: none !important;
}
.btn-hero-quote {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255,255,255,0.12);
    color: #FFFFFF !important;
    border: 1px solid rgba(255,255,255,0.25);
    font-size: 13.5px;
    font-weight: 600;
    padding: 9px 18px;
    border-radius: 10px;
    text-decoration: none !important;
    cursor: pointer;
}
.btn-hero-quote:hover { background: rgba(255,255,255,0.2); }

/* Main Content Cards */
.biz-box {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}
.biz-box-title {
    font-size: 16px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    border-bottom: 1px solid #F1F5F9;
    padding-bottom: 12px;
}
.biz-box-title i { color: #2563EB; font-size: 16px; }

/* Services Grid */
.biz-service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 12px;
}
.biz-service-item {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.biz-service-item i { color: #03855c; }
.biz-service-item span { font-size: 13.5px; font-weight: 600; color: #334155; }

/* Working Hours Table */
.biz-hours-table {
    width: 100%;
    font-size: 13.5px;
}
.biz-hours-table tr { border-bottom: 1px solid #F1F5F9; }
.biz-hours-table td { padding: 8px 4px; }
.biz-hours-table tr.today { font-weight: 700; color: #2563EB; }

/* NAP Block */
.nap-block {
    background: #F0FDF4;
    border: 1px solid #BBF7D0;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 16px;
}
.nap-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 13px;
    color: #1E293B;
    margin-bottom: 8px;
}
.nap-item:last-child { margin-bottom: 0; }
.nap-item i { color: #03855c; font-size: 14px; margin-top: 3px; }

/* Related Job Card */
.biz-job-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: border-color 0.15s;
}
.biz-job-card:hover { border-color: #2563EB; }
.biz-job-title { font-size: 14px; font-weight: 700; color: #0F172A; text-decoration: none; }
.biz-job-title:hover { color: #2563EB; }

/* ==========================================================================
   MOBILE HERO FIX — Only applies ≤ 767px
   ========================================================================== */
@media (max-width: 767px) {

    /* Hero background padding compact */
    .biz-hero {
        padding: 20px 0 22px !important;
    }

    /* Stack logo + info vertically & centered */
    .biz-hero-card {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 14px !important;
    }

    /* Logo row: logo left + verified badge right on same line */
    .biz-hero-logo-row {
        display: flex;
        align-items: center;
        gap: 14px;
        width: 100%;
    }

    /* Smaller logo on mobile */
    .biz-hero-logo {
        width: 68px !important;
        height: 68px !important;
        border-radius: 12px !important;
        flex-shrink: 0;
    }

    /* Title smaller, no awkward line-break */
    .biz-hero-title {
        font-size: 18px !important;
        font-weight: 800 !important;
        line-height: 1.3 !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 6px !important;
        margin-bottom: 6px !important;
    }

    /* Verified badge inline below name */
    .biz-hero-badge-verified {
        font-size: 11px !important;
        padding: 3px 9px !important;
        align-self: flex-start;
    }

    /* Category & address compact */
    .biz-hero-category {
        font-size: 13px !important;
        margin-bottom: 5px !important;
    }
    .biz-hero-address {
        font-size: 12.5px !important;
        line-height: 1.4 !important;
        align-items: flex-start !important;
    }

    /* Action buttons — full width stacked */
    .biz-hero-actions {
        flex-direction: column !important;
        gap: 8px !important;
        margin-top: 14px !important;
        width: 100%;
    }
    .btn-hero-call,
    .btn-hero-wa,
    .btn-hero-quote {
        width: 100% !important;
        justify-content: center !important;
        font-size: 14px !important;
        padding: 12px 16px !important;
        border-radius: 10px !important;
    }
    /* Call button — blue */
    .btn-hero-call {
        order: 1;
    }
    /* WhatsApp — green */
    .btn-hero-wa {
        order: 2;
    }
    /* Enquiry & Website */
    .btn-hero-quote {
        order: 3;
        background: rgba(255,255,255,0.13) !important;
    }

    /* Breadcrumb — hide on mobile to save space */
    .biz-breadcrumb-bar {
        display: none;
    }

    /* Main content no side margin */
    .biz-detail-wrapper .container {
        padding-left: 12px !important;
        padding-right: 12px !important;
    }
}
</style>

<div class="biz-detail-wrapper">
    {{-- Top Hero Section --}}
    <div class="biz-hero">
        <div class="container">
            <div class="biz-hero-card">

                {{-- MOBILE: logo + verified badge in one row --}}
                <div class="biz-hero-logo-row">
                    <div class="biz-hero-logo">
                        @if($business->logo)
                        <img src="{{ $business->getLogoUrl() }}" alt="{{ $business->name }}">
                        @else
                        <i class="fa fa-building-o" style="font-size: 32px; color: #94A3B8;"></i>
                        @endif
                    </div>

                    {{-- Verified badge next to logo on mobile --}}
                    @if($business->verification_status === 'verified')
                    <span class="biz-hero-badge-verified d-block d-md-none">
                        <i class="fa fa-check-circle"></i> Verified Business
                    </span>
                    @endif
                </div>

                {{-- Info block --}}
                <div style="flex: 1; min-width: 0; width: 100%;">
                    <h1 class="biz-hero-title">
                        {{ $business->name }}
                        {{-- Verified badge next to name on desktop only --}}
                        @if($business->verification_status === 'verified')
                        <span class="biz-hero-badge-verified d-none d-md-inline-flex">
                            <i class="fa fa-check-circle"></i> Verified Business
                        </span>
                        @endif
                    </h1>

                    @if($business->category)
                    <div class="biz-hero-category">
                        <i class="fa {{ $business->category->icon ?: 'fa-folder-o' }}"></i> {{ $business->category->name }}
                    </div>
                    @endif

                    <div class="biz-hero-address">
                        <i class="fa fa-map-marker" style="color: #38BDF8; flex-shrink:0; margin-top:2px;"></i>
                        <span>{{ $business->full_address }}</span>
                    </div>

                    {{-- Actions --}}
                    <div class="biz-hero-actions">
                        <a href="tel:{{ $business->clean_phone }}" onclick="logLead({{ $business->id }}, 'call')" class="btn-hero-call">
                            <i class="fa fa-phone"></i> Call {{ $business->phone }}
                        </a>
                        <a href="{{ $business->whatsapp_url }}" target="_blank" onclick="logLead({{ $business->id }}, 'whatsapp')" class="btn-hero-wa">
                            <i class="fa fa-whatsapp"></i> Chat on WhatsApp
                        </a>
                        <button type="button" class="btn-hero-quote" data-toggle="modal" data-target="#enquiryModal">
                            <i class="fa fa-envelope-o"></i> Send Enquiry / Get Quote
                        </button>
                        @if($business->website)
                        <a href="{{ $business->website }}" target="_blank" rel="nofollow noopener" onclick="logLead({{ $business->id }}, 'website_click')" class="btn-hero-quote">
                            <i class="fa fa-external-link"></i> Website
                        </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Breadcrumb Bar --}}
    <div class="biz-breadcrumb-bar" style="background: #FFFFFF; border-bottom: 1px solid #E2E8F0; padding: 12px 0;">
        <div class="container">
            <div style="font-size: 13px; color: #64748B; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                <a href="{{ url('/') }}" style="color:#64748B;text-decoration:none;"><i class="fa fa-home"></i> Home</a>
                <i class="fa fa-angle-right" style="font-size:10px;color:#CBD5E1;"></i>
                <a href="{{ route('business.list') }}" style="color:#64748B;text-decoration:none;">Businesses</a>
                @if($business->category)
                <i class="fa fa-angle-right" style="font-size:10px;color:#CBD5E1;"></i>
                <a href="{{ route('business.list', ['category' => $business->category->slug]) }}" style="color:#64748B;text-decoration:none;">{{ $business->category->name }}</a>
                @endif
                <i class="fa fa-angle-right" style="font-size:10px;color:#CBD5E1;"></i>
                <span style="color:#0F172A;font-weight:600;">{{ $business->name }}</span>
            </div>
        </div>
    </div>


    {{-- Main Container --}}
    <div class="container" style="margin-top: 24px;">
        <div class="row">
            {{-- LEFT: Main Business Details --}}
            <div class="col-lg-8 col-md-8 col-12">
                {{-- About Business --}}
                <div class="biz-box">
                    <div class="biz-box-title">
                        <i class="fa fa-info-circle"></i> About {{ $business->name }}
                    </div>
                    <div style="font-size: 14px; line-height: 1.7; color: #334155;">
                        @if(!empty($business->description))
                        {!! nl2br(e($business->description)) !!}
                        @elseif(!empty($business->short_description))
                        <p>{{ $business->short_description }}</p>
                        @else
                        <p class="text-muted">No detailed description provided by the business owner yet.</p>
                        @endif
                    </div>

                    {{-- Quick Metadata pills --}}
                    <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-top: 20px; padding-top: 16px; border-top: 1px solid #F1F5F9; font-size: 13px; color: #64748B;">
                        @if($business->year_established)
                        <div><strong style="color:#0F172A;"><i class="fa fa-calendar-check-o"></i> Established:</strong> {{ $business->year_established }}</div>
                        @endif
                        @if($business->business_type)
                        <div><strong style="color:#0F172A;"><i class="fa fa-briefcase"></i> Type:</strong> {{ $business->business_type }}</div>
                        @endif
                    </div>
                </div>

                {{-- Services Offered --}}
                @if($business->services && count($business->services) > 0)
                <div class="biz-box">
                    <div class="biz-box-title">
                        <i class="fa fa-check-square-o"></i> Services & Offerings
                    </div>
                    <div class="biz-service-grid">
                        @foreach($business->services as $srv)
                        <div class="biz-service-item">
                            <i class="fa fa-check-circle"></i>
                            <div>
                                <span>{{ $srv->service_name }}</span>
                                @if($srv->price_starting)
                                <div style="font-size: 11.5px; color: #03855c; font-weight: 600;">Starts from ₹{{ number_format($srv->price_starting) }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Jobs From This Business (Cross-link) --}}
                @if($relatedJobs && count($relatedJobs) > 0)
                <div class="biz-box">
                    <div class="biz-box-title">
                        <i class="fa fa-briefcase"></i> Open Job Openings at {{ $business->name }}
                    </div>
                    <p style="font-size: 13px; color: #64748B; margin-bottom: 14px;">
                        This business is also actively hiring for the following roles:
                    </p>
                    @foreach($relatedJobs as $job)
                    <div class="biz-job-card">
                        <div>
                            <a href="{{ route('job.detail', [$job->slug]) }}" class="biz-job-title">{{ $job->title }}</a>
                            <div style="font-size: 12px; color: #64748B; margin-top: 4px;">
                                <span><i class="fa fa-map-marker"></i> {{ $job->getLocation() }}</span> • 
                                <span><i class="fa fa-clock-o"></i> {{ $job->getJobType('job_type') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('job.detail', [$job->slug]) }}" class="btn btn-sm btn-primary" style="font-size:12px;font-weight:600;border-radius:6px;">
                            Apply Now
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- RIGHT: Sidebar (NAP, Hours, Map, Claim) --}}
            <div class="col-lg-4 col-md-4 col-12">
                {{-- NAP Consistent Identity Card --}}
                <div class="biz-box" style="border-top: 3px solid #03855c;">
                    <div class="biz-box-title" style="color: #03855c;">
                        <i class="fa fa-id-card-o"></i> Business Identity (NAP)
                    </div>
                    <div class="nap-block">
                        <div class="nap-item">
                            <i class="fa fa-building"></i>
                            <div><strong>{{ $business->name }}</strong></div>
                        </div>
                        <div class="nap-item">
                            <i class="fa fa-map-marker"></i>
                            <div>{{ $business->full_address }}</div>
                        </div>
                        <div class="nap-item">
                            <i class="fa fa-phone"></i>
                            <div><a href="tel:{{ $business->clean_phone }}" style="color:#03855c;font-weight:700;text-decoration:none;">{{ $business->phone }}</a></div>
                        </div>
                    </div>
                </div>

                {{-- Working Hours Card --}}
                <div class="biz-box">
                    <div class="biz-box-title">
                        <i class="fa fa-clock-o"></i> Business Hours
                        @if($business->is_open_now === true)
                        <span style="margin-left:auto;font-size:11.5px;background:#ECFDF5;color:#03855c;font-weight:700;padding:2px 8px;border-radius:12px;border:1px solid #A7F3D0;">Open Now</span>
                        @elseif($business->is_open_now === false)
                        <span style="margin-left:auto;font-size:11.5px;background:#FEF2F2;color:#DC2626;font-weight:700;padding:2px 8px;border-radius:12px;border:1px solid #FECACA;">Closed</span>
                        @endif
                    </div>
                    @php $todayDay = Carbon\Carbon::now()->dayOfWeekIso - 1; @endphp
                    <table class="biz-hours-table">
                        @foreach(\App\BusinessHour::dayNames() as $dNum => $dName)
                        @php $daySchedule = $business->workingHours->where('day', $dNum)->first(); @endphp
                        <tr class="{{ $todayDay == $dNum ? 'today' : '' }}">
                            <td>{{ $dName }} @if($todayDay == $dNum) <small>(Today)</small> @endif</td>
                            <td class="text-right">
                                @if(!$daySchedule || $daySchedule->is_closed)
                                <span style="color:#DC2626;font-weight:600;">Closed</span>
                                @elseif($daySchedule->is_24_hours)
                                <span style="color:#03855c;font-weight:600;">Open 24 Hours</span>
                                @elseif($daySchedule->open_time && $daySchedule->close_time)
                                {{ Carbon\Carbon::createFromFormat('H:i:s', $daySchedule->open_time)->format('g:i A') }} - {{ Carbon\Carbon::createFromFormat('H:i:s', $daySchedule->close_time)->format('g:i A') }}
                                @else
                                <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>

                {{-- Map / Directions Card --}}
                @if($business->latitude && $business->longitude)
                <div class="biz-box">
                    <div class="biz-box-title">
                        <i class="fa fa-map"></i> Location & Directions
                    </div>
                    <div style="height: 180px; border-radius: 8px; overflow: hidden; border: 1px solid #E2E8F0; margin-bottom: 12px;">
                        <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ $business->latitude }},{{ $business->longitude }}&hl=en&z=15&amp;output=embed"></iframe>
                    </div>
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $business->latitude }},{{ $business->longitude }}" target="_blank" onclick="logLead({{ $business->id }}, 'directions')" class="btn btn-sm btn-block" style="background:#F8FAFC;border:1px solid #CBD5E1;font-weight:600;color:#1E293B;">
                        <i class="fa fa-location-arrow"></i> Get Driving Directions
                    </a>
                </div>
                @endif

                {{-- Claim This Business Card --}}
                <div class="biz-box" style="text-align: center; background: #FAFBFC;">
                    <i class="fa fa-shield" style="font-size: 28px; color: #94A3B8; margin-bottom: 8px;"></i>
                    <h4 style="font-size: 14px; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Is this your business?</h4>
                    <p style="font-size: 12.5px; color: #64748B; margin-bottom: 12px;">Manage this listing, update services, and respond to customer leads.</p>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-block" data-toggle="modal" data-target="#claimModal" style="font-weight: 600; border-radius: 6px;">
                        Claim This Business
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lead Enquiry Modal --}}
<div class="modal fade" id="enquiryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none;">
            <div class="modal-header" style="background: #2563EB; color: #fff; padding: 18px 22px;">
                <h5 class="modal-title" style="font-weight: 700; font-size: 16px;">
                    <i class="fa fa-envelope-o"></i> Send Enquiry to {{ $business->name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('business.lead', $business->id) }}" method="POST" id="leadForm">
                @csrf
                <input type="hidden" name="lead_type" value="enquiry">
                <div class="modal-body" style="padding: 22px;">
                    <div class="form-group">
                        <label style="font-size:13px;font-weight:600;">Your Name *</label>
                        <input type="text" name="sender_name" class="form-control" required placeholder="Enter your full name" value="{{ Auth::check() ? Auth::user()->name : '' }}">
                    </div>
                    <div class="form-group">
                        <label style="font-size:13px;font-weight:600;">Phone Number *</label>
                        <input type="text" name="sender_phone" class="form-control" required placeholder="Enter mobile number" value="{{ Auth::check() ? Auth::user()->phone : '' }}">
                    </div>
                    <div class="form-group">
                        <label style="font-size:13px;font-weight:600;">Email Address</label>
                        <input type="email" name="sender_email" class="form-control" placeholder="Enter email (optional)" value="{{ Auth::check() ? Auth::user()->email : '' }}">
                    </div>
                    <div class="form-group">
                        <label style="font-size:13px;font-weight:600;">Message / Service Requirement *</label>
                        <textarea name="message" class="form-control" rows="3" required placeholder="Describe what service or quote you are looking for..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 14px 22px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="font-weight:600;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="font-weight:700;background:#2563EB;border-radius:8px;">Send Inquiry</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Claim Business Modal --}}
<div class="modal fade" id="claimModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none;">
            <div class="modal-header" style="background: #0F172A; color: #fff; padding: 18px 22px;">
                <h5 class="modal-title" style="font-weight: 700; font-size: 16px;">
                    <i class="fa fa-shield"></i> Claim Ownership of {{ $business->name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;opacity:0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @if(Auth::check() && Auth::user()->isBusinessUser())
            <form action="{{ route('business.claim', $business->id) }}" method="POST" id="claimForm">
                @csrf
                <div class="modal-body" style="padding: 22px;">
                    <p style="font-size: 13px; color: #64748B; margin-bottom: 16px;">
                        Please provide your official contact information. Our team will verify your relationship with <strong>{{ $business->name }}</strong> before transferring ownership.
                    </p>
                    <div class="form-group">
                        <label style="font-size:13px;font-weight:600;">Your Official Name *</label>
                        <input type="text" name="claimant_name" class="form-control" required value="{{ Auth::user()->name }}">
                    </div>
                    <div class="form-group">
                        <label style="font-size:13px;font-weight:600;">Official Phone Number *</label>
                        <input type="text" name="claimant_phone" class="form-control" required value="{{ Auth::user()->phone }}">
                    </div>
                    <div class="form-group">
                        <label style="font-size:13px;font-weight:600;">Official Email *</label>
                        <input type="email" name="claimant_email" class="form-control" required value="{{ Auth::user()->email }}">
                    </div>
                    <div class="form-group">
                        <label style="font-size:13px;font-weight:600;">Additional Details / Proof (GST, Visiting Card, etc.)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Mention your designation or business proof..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 14px 22px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="font-weight:600;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="font-weight:700;background:#2563EB;border-radius:8px;">Submit Claim Request</button>
                </div>
            </form>
            @elseif(Auth::check() && Auth::user()->isJobSeeker())
            <div class="modal-body text-center" style="padding: 36px 20px;">
                <div style="width:60px;height:60px;border-radius:50%;background:#EFF6FF;color:#2563EB;font-size:26px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px;">
                    <i class="fa fa-briefcase"></i>
                </div>
                <h4 style="font-size: 16px; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Business Account Required</h4>
                <p style="font-size: 13.5px; color: #64748B; margin-bottom: 20px; line-height: 1.5;">
                    You are currently logged in as a <strong>Job Seeker</strong>.<br>
                    Business listings and claims can only be managed through a dedicated <strong>Business Account</strong>.
                </p>
                <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
                    <a href="{{ route('business.login') }}" class="btn btn-primary" style="font-weight: 700; border-radius: 8px; padding: 9px 20px; background:#2563EB;">Login as Business</a>
                    <a href="{{ route('business.register') }}" class="btn btn-outline-primary" style="font-weight: 700; border-radius: 8px; padding: 9px 20px;">Create Business Account</a>
                </div>
            </div>
            @else
            <div class="modal-body text-center" style="padding: 36px 20px;">
                <i class="fa fa-lock" style="font-size: 40px; color: #94A3B8; margin-bottom: 12px;"></i>
                <h4 style="font-size: 16px; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Business Login Required</h4>
                <p style="font-size: 13.5px; color: #64748B; margin-bottom: 20px;">Please login with your Business Account to claim and manage this business listing.</p>
                <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
                    <a href="{{ route('business.login') }}" class="btn btn-primary" style="font-weight: 700; border-radius: 8px; padding: 9px 20px; background:#2563EB;">Login as Business</a>
                    <a href="{{ route('business.register') }}" class="btn btn-outline-primary" style="font-weight: 700; border-radius: 8px; padding: 9px 20px;">Register Business</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@include('includes.footer')
@endsection

@push('scripts')
<script>
    function logLead(bizId, type) {
        $.post("{{ url('business/lead') }}/" + bizId, {
            _token: "{{ csrf_token() }}",
            lead_type: type
        });
    }

    $('#claimForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $.post(form.attr('action'), form.serialize(), function(res) {
            alert(res.message);
            $('#claimModal').modal('hide');
        }).fail(function(xhr) {
            alert(xhr.responseJSON ? xhr.responseJSON.message : 'Error submitting claim.');
        });
    });
</script>
@endpush
