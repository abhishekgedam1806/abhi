@extends('layouts.app')

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Company Detail')])
<!-- Inner Page Title end -->

@push('styles')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": {!! json_encode($company->name) !!},
  "url": "{{ route('company.detail', $company->slug) }}",
  "logo": "{{ !empty($company->logo) ? asset('company_logos/' . $company->logo) : asset('sitesetting_images/thumb/' . ($siteSetting->site_logo ?? '')) }}",
  "description": {!! json_encode(strip_tags($company->description ?? $company->name)) !!},
  "address": {
    "@type": "PostalAddress",
    "addressLocality": {!! json_encode($company->getLocation() ?: 'India') !!}
  }
}
</script>
<style type="text/css">
    .company-detail-redesign {
        background: #F8FAFC;
        padding: 36px 0 60px;
        min-height: 85vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .company-detail-card {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 18px;
        padding: 26px 28px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        margin-bottom: 24px;
    }
    .company-stat-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #F1F5F9;
        font-size: 13.5px;
    }
    .company-stat-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .company-job-item {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }
    .company-job-item:hover {
        border-color: #93C5FD;
        box-shadow: 0 6px 20px rgba(37,99,235,0.06);
        transform: translateY(-2px);
    }
</style>
@endpush

<div class="company-detail-redesign">
    <div class="container" style="max-width: 1320px;">
        @include('flash::message')

        <div id="alert_messages"></div>

        <!-- 1. Top Executive Company Header Card -->
        <div class="company-detail-card" style="padding: 32px;">
            <div class="row align-items-center" style="display: flex; flex-wrap: wrap; align-items: center;">
                <div class="col-lg-8 col-md-7">
                    <div style="display: flex; align-items: center; gap: 22px; flex-wrap: wrap;">
                        <!-- Company Avatar -->
                        <div style="width: 88px; height: 88px; border-radius: 18px; overflow: hidden; border: 2px solid #E2E8F0; background: #FFFFFF; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(0,0,0,0.05); flex-shrink: 0;">
                            @if(!empty($company->logo) && file_exists(public_path('company_logos/' . $company->logo)))
                                <img src="{{ asset('company_logos/' . $company->logo) }}" alt="{{ $company->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; background: #EFF6FF; color: #2563EB; font-size: 32px; font-weight: 800; display: flex; align-items: center; justify-content: center;">
                                    {{ strtoupper(substr($company->name ?: 'C', 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Company Main Info -->
                        <div>
                            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 4px;">
                                <h1 style="font-size: 24px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">{{ $company->name }}</h1>
                                @if((bool)$company->verified)
                                    <span style="background: #ECFDF5; color: #03855c; border: 1px solid #A7F3D0; font-size: 11.5px; font-weight: 700; padding: 2px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa fa-check-circle"></i> {{__('Verified Employer')}}
                                    </span>
                                @endif
                            </div>

                            @if($company->getIndustry('industry'))
                                <span style="font-size: 14px; font-weight: 600; color: #2563EB; display: block; margin-bottom: 8px;">
                                    {{ $company->getIndustry('industry') }}
                                </span>
                            @endif

                            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; font-size: 13px; color: #64748B;">
                                <span><i class="fa fa-calendar-check-o" style="color: #64748B; margin-right: 4px;"></i> {{__('Member Since')}} {{ $company->created_at->format('M Y') }}</span>
                                @if(!empty($company->location))
                                    <span><i class="fa fa-map-marker text-danger" style="margin-right: 4px;"></i> {{ $company->location }}</span>
                                @elseif($company->getLocation())
                                    <span><i class="fa fa-map-marker text-danger" style="margin-right: 4px;"></i> {{ $company->getLocation() }}</span>
                                @endif
                                @if($company->countNumJobs('company_id', $company->id) > 0)
                                    <span style="color: #03855c; font-weight: 700;"><i class="fa fa-briefcase" style="margin-right: 4px;"></i> {{ $company->countNumJobs('company_id', $company->id) }} {{__('Open Positions')}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Action & Connect Buttons -->
                <div class="col-lg-4 col-md-5" style="margin-top: 18px;">
                    <div style="display: flex; flex-direction: column; gap: 10px; align-items: flex-end;">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: flex-end; width: 100%;">
                            @if(Auth::check() && Auth::user()->isFavouriteCompany($company->slug))
                                <a href="{{route('remove.from.favourite.company', $company->slug)}}" style="background: #FEF2F2; color: #EF4444; border: 1.5px solid #FECACA; font-size: 13px; font-weight: 700; padding: 10px 16px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fa fa-heart"></i> {{__('Saved')}}
                                </a>
                            @else
                                <a href="{{route('add.to.favourite.company', $company->slug)}}" style="background: #FFFFFF; color: #475569; border: 1.5px solid #CBD5E1; font-size: 13px; font-weight: 700; padding: 10px 16px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.15s ease;">
                                    <i class="fa fa-heart-o"></i> {{__('Save')}}
                                </a>
                            @endif

                            <button type="button" onclick="send_message()" style="background: #2563EB; color: #FFFFFF; font-size: 13px; font-weight: 700; padding: 10px 18px; border-radius: 10px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                                <i class="fa fa-envelope"></i> {{__('Send Message')}}
                            </button>

                            <a href="{{route('report.abuse.company', $company->slug)}}" style="background: #FFFFFF; color: #DC2626; border: 1.5px solid #FCA5A5; font-size: 12.5px; font-weight: 600; padding: 10px 12px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa fa-flag-o"></i>
                            </a>
                        </div>

                        <!-- Direct HR Contact Buttons if allowed & logged in -->
                        @if(Auth::check() || Auth::guard('company')->check())
                            <div style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end; margin-top: 6px; width: 100%;">
                                @if(!empty($company->phone) && $company->allow_phone_contact)
                                    <a href="tel:{{ $company->phone }}" style="background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; font-size: 12.5px; font-weight: 700; padding: 7px 14px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa fa-phone"></i> {{ $company->phone }}
                                    </a>
                                @endif
                                @if(!empty($company->whatsapp_number) && $company->allow_whatsapp_contact)
                                    @php
                                        $cleanWa = preg_replace('/[^0-9]/', '', $company->whatsapp_number);
                                    @endphp
                                    <a href="https://api.whatsapp.com/send?phone={{ $cleanWa }}&text=Hello%20{{ urlencode($company->name) }}%2C%20I%20saw%20your%20company%20profile%20on%20Jobs%20Portal." target="_blank" style="background: #03855c; color: #FFFFFF; font-size: 12.5px; font-weight: 700; padding: 7px 14px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa fa-whatsapp"></i> {{__('WhatsApp HR')}}
                                    </a>
                                @endif
                            </div>
                        @else
                            <div style="margin-top: 6px;">
                                <a href="{{ route('login') }}" style="font-size: 12px; font-weight: 600; color: #2563EB; text-decoration: none;">
                                    <i class="fa fa-lock" style="margin-right: 4px;"></i> {{__('Login to view recruiter phone & email')}}
                                </a>
                            </div>
                        @endif

                        <!-- Social Media Links -->
                        @if($company->getSocialNetworkHtml())
                            <div style="display: flex; gap: 8px; margin-top: 8px;">
                                {!! $company->getSocialNetworkHtml() !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Main Content Grid (Left Column: About & Jobs, Right Column: Stats & Map) -->
        <div class="row">
            <!-- Left Column: About & Open Jobs -->
            <div class="col-lg-8 col-md-7">
                <!-- About Company Card -->
                <div class="company-detail-card">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 18px; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px;">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                            <i class="fa fa-building-o"></i>
                        </div>
                        <div>
                            <h2 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('About')}} {{ $company->name }}</h2>
                            <span style="font-size: 12.5px; color: #64748B;">{{__('Company background, mission, and working culture')}}</span>
                        </div>
                    </div>

                    <div style="font-size: 14.5px; line-height: 1.7; color: #334155;">
                        @if(!empty($company->description))
                            {!! $company->description !!}
                        @else
                            <p style="color: #94A3B8; font-style: italic; margin: 0;">{{__('No detailed description provided by the employer yet.')}}</p>
                        @endif
                    </div>
                </div>

                <!-- Active Job Openings Card -->
                <div class="company-detail-card">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 38px; height: 38px; border-radius: 10px; background: #ECFDF5; color: #03855c; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                                <i class="fa fa-briefcase"></i>
                            </div>
                            <div>
                                <h2 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Current Job Openings')}}</h2>
                                <span style="font-size: 12.5px; color: #64748B;">{{ count($company->jobs) }} {{__('available job opportunities')}}</span>
                            </div>
                        </div>
                    </div>

                    @if(isset($company->jobs) && count($company->jobs))
                        @foreach($company->jobs as $companyJob)
                            <div class="company-job-item">
                                <div class="row align-items-center" style="display: flex; flex-wrap: wrap; align-items: center;">
                                    <div class="col-md-8 col-sm-8">
                                        <h3 style="font-size: 16px; font-weight: 800; margin: 0 0 6px 0;">
                                            <a href="{{ route('job.detail', [$companyJob->slug]) }}" style="color: #0F172A; text-decoration: none; transition: color 0.15s ease;">
                                                {{ $companyJob->title }}
                                            </a>
                                        </h3>

                                        <!-- Meta Chips -->
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 10px;">
                                            @if($companyJob->getJobType('job_type'))
                                                <span style="background: #EFF6FF; color: #2563EB; font-size: 12px; font-weight: 700; padding: 2px 10px; border-radius: 6px;">
                                                    {{ $companyJob->getJobType('job_type') }}
                                                </span>
                                            @endif
                                            @if($companyJob->getJobShift('job_shift'))
                                                <span style="background: #FEF3C7; color: #D97706; font-size: 12px; font-weight: 700; padding: 2px 10px; border-radius: 6px;">
                                                    {{ $companyJob->getJobShift('job_shift') }}
                                                </span>
                                            @endif
                                            @if($companyJob->getCity('city'))
                                                <span style="color: #64748B; font-size: 12.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                                    <i class="fa fa-map-marker text-danger"></i> {{ $companyJob->getCity('city') }}
                                                </span>
                                            @endif
                                        </div>

                                        <p style="font-size: 13px; color: #64748B; margin: 0; line-height: 1.5;">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($companyJob->description), 130, '...') }}
                                        </p>
                                    </div>

                                    <div class="col-md-4 col-sm-4 text-right" style="margin-top: 10px;">
                                        <a href="{{ route('job.detail', [$companyJob->slug]) }}" style="background: #2563EB; color: #FFFFFF; font-size: 13px; font-weight: 700; padding: 9px 20px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                                            {{__('View Job')}} <i class="fa fa-arrow-right" style="font-size: 11px;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div style="padding: 30px; text-align: center; background: #F8FAFC; border-radius: 12px; border: 1px dashed #CBD5E1;">
                            <i class="fa fa-briefcase" style="font-size: 32px; color: #94A3B8; margin-bottom: 8px;"></i>
                            <h4 style="font-size: 15px; font-weight: 700; color: #475569; margin: 0 0 4px 0;">{{__('No Active Job Postings')}}</h4>
                            <p style="font-size: 13px; color: #64748B; margin: 0;">{{__('This employer currently does not have any active vacancies.')}}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Company Overview Matrix & Google Map -->
            <div class="col-lg-4 col-md-5">
                <!-- Company Stats & Attributes -->
                <div class="company-detail-card">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px;">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: #F3E8FF; color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                            <i class="fa fa-id-card-o"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 16.5px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Company Overview')}}</h3>
                            <span style="font-size: 12px; color: #64748B;">{{__('Key operational attributes')}}</span>
                        </div>
                    </div>

                    <div class="company-stat-row">
                        <span style="color: #64748B; font-weight: 600;">{{__('Email Verification')}}</span>
                        <span>
                            @if((bool)$company->verified)
                                <span style="background: #ECFDF5; color: #03855c; font-weight: 700; font-size: 12px; padding: 2px 8px; border-radius: 6px;"><i class="fa fa-check"></i> {{__('Verified')}}</span>
                            @else
                                <span style="background: #FEF2F2; color: #DC2626; font-weight: 700; font-size: 12px; padding: 2px 8px; border-radius: 6px;">{{__('Unverified')}}</span>
                            @endif
                        </span>
                    </div>

                    @if(!empty($company->no_of_employees))
                        <div class="company-stat-row">
                            <span style="color: #64748B; font-weight: 600;">{{__('Total Employees')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{ $company->no_of_employees }}</span>
                        </div>
                    @endif

                    @if(!empty($company->established_in))
                        <div class="company-stat-row">
                            <span style="color: #64748B; font-weight: 600;">{{__('Established In')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{ $company->established_in }}</span>
                        </div>
                    @endif

                    @if(!empty($company->ownership_type_id))
                        <div class="company-stat-row">
                            <span style="color: #64748B; font-weight: 600;">{{__('Ownership')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{ $company->getOwnershipType('ownership_type') }}</span>
                        </div>
                    @endif

                    @if(!empty($company->no_of_offices))
                        <div class="company-stat-row">
                            <span style="color: #64748B; font-weight: 600;">{{__('No. of Offices')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{ $company->no_of_offices }}</span>
                        </div>
                    @endif

                    @if(!empty($company->website))
                        <div class="company-stat-row">
                            <span style="color: #64748B; font-weight: 600;">{{__('Website')}}</span>
                            <span>
                                <a href="{{ $company->website }}" target="_blank" style="color: #2563EB; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                    {{ parse_url($company->website, PHP_URL_HOST) ?: 'Visit Site' }} <i class="fa fa-external-link" style="font-size: 11px;"></i>
                                </a>
                            </span>
                        </div>
                    @endif

                    <div class="company-stat-row">
                        <span style="color: #64748B; font-weight: 600;">{{__('Active Jobs')}}</span>
                        <span style="font-weight: 800; color: #03855c;">{{ $company->countNumJobs('company_id', $company->id) }}</span>
                    </div>
                </div>

                <!-- Google Map Widget -->
                <div class="company-detail-card" style="padding: 22px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                        <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fa fa-map-marker text-danger" style="font-size: 17px;"></i> {{__('Office Location')}}
                        </h3>
                        <span style="font-size: 12px; color: #64748B; font-weight: 600;">{{ $company->getCity('city') ?: 'India' }}</span>
                    </div>
                    <div style="border-radius: 12px; overflow: hidden; border: 1px solid #E2E8F0; height: 230px; background: #F8FAFC;">
                        <iframe src="{{ $company->getGoogleMapEmbedUrl() }}" width="100%" height="230" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="sendmessage" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px; border: 1.5px solid #E2E8F0; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.12);">
            <form action="" id="send-form">
                @csrf
                <input type="hidden" name="company_id" id="company_id" value="{{$company->id}}">
                <div class="modal-header" style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; padding: 18px 24px;">
                    <h4 class="modal-title" style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0;">
                        <i class="fa fa-envelope-o" style="color: #2563EB; margin-right: 6px;"></i> {{__('Send Message to')}} {{ $company->name }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="font-size: 24px; color: #64748B;">&times;</button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group" style="margin: 0;">
                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">{{__('Your Message')}} <span style="color: #EF4444;">*</span></label>
                        <textarea class="form-control" name="message" id="message" cols="10" rows="6" placeholder="{{__('Write your message or inquiry for this employer...')}}" style="border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 12px; font-size: 13.5px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 14px 24px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border: 1.5px solid #CBD5E1; border-radius: 10px; font-weight: 700; font-size: 13px; padding: 8px 18px;">{{__('Cancel')}}</button>
                    <button type="submit" class="btn btn-primary" style="background: #2563EB; border: none; border-radius: 10px; font-weight: 800; font-size: 13px; padding: 8px 22px; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">{{__('Submit Message')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection

@push('scripts')
<script type="text/javascript">
function send_message() {
    @if(Auth::check())
        $('#sendmessage').modal('show');
    @else
        swal({
            title: "{{__('Login Required')}}",
            text: "{{__('Please log in as a candidate to send messages to employers.')}}",
            icon: "info",
            button: "{{__('OK')}}",
        });
    @endif
}

if ($("#send-form").length > 0) {
    $("#send-form").validate({
        validateHiddenInputs: true,
        ignore: "",
        rules: {
            message: {
                required: true,
                maxlength: 5000
            },
        },
        messages: {
            message: {
                required: "{{__('Message is required')}}",
            }
        },
        submitHandler: function(form) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            @if(null !== (Auth::user()))
            $.ajax({
                url: "{{route('submit-message')}}",
                type: "POST",
                data: $('#send-form').serialize(),
                success: function(response) {
                    $("#send-form").trigger("reset");
                    $('#sendmessage').modal('hide');
                    swal({
                        title: "{{__('Success')}}",
                        text: response["msg"],
                        icon: "success",
                        button: "{{__('OK')}}",
                    });
                }
            });
            @endif
        }
    });
}
</script>
@endpush