@extends('layouts.app')

@push('styles')
<style>
.support-center-wrap {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background: #F8FAFC;
    padding: 30px 0 60px 0;
}
.support-hero-box {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 32px 36px;
    margin-bottom: 28px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.support-hero-title {
    font-size: 26px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 8px;
    line-height: 1.3;
}
.support-hero-desc {
    font-size: 15px;
    color: #64748B;
    line-height: 1.6;
    margin: 0;
    max-width: 750px;
}

/* 4 Quick Channel Cards */
.quick-channel-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 20px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease;
    text-decoration: none !important;
}
.quick-channel-card:hover {
    border-color: #2563EB;
    box-shadow: 0 6px 18px rgba(37,99,235,0.08);
    transform: translateY(-2px);
}
.channel-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 14px;
}
.channel-icon-whatsapp { background: #DCFCE7; color: #16A34A; }
.channel-icon-phone { background: #EFF6FF; color: #2563EB; }
.channel-icon-email { background: #ECFDF5; color: #03855c; }
.channel-icon-location { background: #FFF7ED; color: #EA580C; }

.channel-title {
    font-size: 15px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 4px;
}
.channel-desc {
    font-size: 13px;
    color: #64748B;
    line-height: 1.4;
    margin-bottom: 12px;
}
.channel-action-link {
    font-size: 12.5px;
    font-weight: 700;
    color: #2563EB;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Contact Form Card */
.support-form-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.support-form-title {
    font-size: 19px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.support-form-desc {
    font-size: 13.5px;
    color: #64748B;
    margin-bottom: 22px;
}

.form-label-styled {
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
    display: block;
}
.form-input-styled {
    width: 100%;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    color: #0F172A;
    background: #FFFFFF;
    outline: none;
    transition: all 0.15s ease;
}
.form-input-styled:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

/* Right Column Cards */
.side-support-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 22px;
    margin-bottom: 22px;
}
.side-support-title {
    font-size: 15px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* FAQ Accordion */
.faq-accordion-box {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 30px;
    margin-top: 30px;
}
.faq-item {
    border-bottom: 1px solid #F1F5F9;
    padding: 16px 0;
}
.faq-item:last-child { border-bottom: none; }
.faq-question {
    font-size: 15px;
    font-weight: 700;
    color: #0F172A;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    user-select: none;
}
.faq-question:hover { color: #2563EB; }
.faq-answer {
    font-size: 14px;
    color: #475569;
    line-height: 1.6;
    margin-top: 10px;
    display: none;
}
</style>
@endpush

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title' => __('Help & Contact Support Center')])
<!-- Inner Page Title end -->

<div class="support-center-wrap">
    <div class="container">

        <!-- Top Hero Box -->
        <div class="support-hero-box">
            <div class="row align-items-center">
                <div class="col-lg-8 col-12">
                    <h1 class="support-hero-title">How can we help you today?</h1>
                    <p class="support-hero-desc">
                        Whether you are a job seeker looking for your next career move, an employer hiring top talent, or need assistance with your account, our customer support team is here to assist you.
                    </p>
                </div>
                <div class="col-lg-4 col-12 text-lg-right mt-3 mt-lg-0">
                    <span style="display: inline-flex; align-items: center; gap: 6px; background: #ECFDF5; border: 1px solid #A7F3D0; color: #03855c; font-size: 13px; font-weight: 700; padding: 6px 14px; border-radius: 20px;">
                        <i class="fa fa-circle text-success" style="font-size: 8px;"></i> Support Desk Online (Mon-Sat)
                    </span>
                </div>
            </div>
        </div>

        <!-- 4 Quick Support Channels -->
        <div class="row mb-4">
            <!-- 1. WhatsApp -->
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                @php
                    $rawPhone = preg_replace('/[^0-9]/', '', $siteSetting->site_phone_primary ?? '7038424139');
                    $waPhone = (strlen($rawPhone) == 10) ? '91' . $rawPhone : $rawPhone;
                @endphp
                <a href="https://api.whatsapp.com/send?phone={{ $waPhone }}&text=Hi%20Jobs%20Portal%20Support,%20I%20need%20assistance" target="_blank" class="quick-channel-card">
                    <div>
                        <div class="channel-icon-wrap channel-icon-whatsapp"><i class="fa fa-whatsapp"></i></div>
                        <div class="channel-title">WhatsApp Support</div>
                        <div class="channel-desc">Chat directly with our support specialists for quick resolutions.</div>
                    </div>
                    <div class="channel-action-link" style="color: #16A34A;">Chat on WhatsApp &rarr;</div>
                </a>
            </div>

            <!-- 2. Phone Call -->
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <a href="tel:{{ $siteSetting->site_phone_primary ?? '7038424139' }}" class="quick-channel-card">
                    <div>
                        <div class="channel-icon-wrap channel-icon-phone"><i class="fa fa-phone"></i></div>
                        <div class="channel-title">Call Helpline</div>
                        <div class="channel-desc">+91 {{ $siteSetting->site_phone_primary ?? '7038424139' }} (Mon-Sat, 9:30 AM - 6:30 PM)</div>
                    </div>
                    <div class="channel-action-link">Call Helpline Now &rarr;</div>
                </a>
            </div>

            <!-- 3. Official Email -->
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <a href="mailto:{{ $siteSetting->mail_to_address ?? 'solocomdigi@gmail.com' }}" class="quick-channel-card">
                    <div>
                        <div class="channel-icon-wrap channel-icon-email"><i class="fa fa-envelope-o"></i></div>
                        <div class="channel-title">Official Email</div>
                        <div class="channel-desc">{{ $siteSetting->mail_to_address ?? 'solocomdigi@gmail.com' }} (Response within 24h)</div>
                    </div>
                    <div class="channel-action-link" style="color: #03855c;">Send an Email &rarr;</div>
                </a>
            </div>

            <!-- 4. Office Location -->
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="quick-channel-card" style="cursor: default;">
                    <div>
                        <div class="channel-icon-wrap channel-icon-location"><i class="fa fa-map-marker"></i></div>
                        <div class="channel-title">Office Location</div>
                        <div class="channel-desc">{{ $siteSetting->site_street_address ?? 'Nagpur, Maharashtra' }}, India</div>
                    </div>
                    <div style="font-size: 12px; font-weight: 600; color: #64748B;"><i class="fa fa-clock-o"></i> 9:30 AM - 6:30 PM IST</div>
                </div>
            </div>
        </div>

        @include('flash::message')

        <!-- Main Form & Info Section -->
        <div class="row">
            
            <!-- LEFT COLUMN: SUPPORT REQUEST FORM -->
            <div class="col-lg-8 col-12 mb-4">
                <div class="support-form-card">
                    <h2 class="support-form-title">
                        <i class="fa fa-paper-plane text-primary"></i> Send Us a Message
                    </h2>
                    <p class="support-form-desc">Fill out the form below with your query and our team will get back to you promptly.</p>

                    <form method="POST" action="{{ route('contact.us') }}" id="supportInquiryForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label-styled">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-input-styled" placeholder="Enter your full name" value="{{ old('full_name', Auth::check() ? Auth::user()->name : '') }}" required>
                                @if($errors->has('full_name'))
                                <span class="text-danger" style="font-size: 12px;">{{ $errors->first('full_name') }}</span>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label-styled">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-input-styled" placeholder="name@example.com" value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" required>
                                @if($errors->has('email'))
                                <span class="text-danger" style="font-size: 12px;">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label-styled">Phone Number</label>
                                <input type="tel" name="phone" class="form-input-styled" placeholder="10-digit mobile number" value="{{ old('phone', Auth::check() ? Auth::user()->phone : '') }}">
                                @if($errors->has('phone'))
                                <span class="text-danger" style="font-size: 12px;">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label-styled">I am a</label>
                                <select name="user_type" class="form-input-styled" style="cursor: pointer;">
                                    <option value="Job Seeker">Job Seeker / Candidate</option>
                                    <option value="Employer / Recruiter">Employer / HR / Recruiter</option>
                                    <option value="Local Business Owner">Local Business Owner</option>
                                    <option value="Other">Other / General Inquiry</option>
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label-styled">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-input-styled" placeholder="e.g. Inquiry regarding job application / employer package" value="{{ old('subject') }}" required>
                                @if($errors->has('subject'))
                                <span class="text-danger" style="font-size: 12px;">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label-styled">Your Message Details <span class="text-danger">*</span></label>
                                <textarea name="message_txt" class="form-input-styled" rows="5" placeholder="Please describe your query or issue in detail..." required>{{ old('message_txt') }}</textarea>
                                @if($errors->has('message_txt'))
                                <span class="text-danger" style="font-size: 12px;">{{ $errors->first('message_txt') }}</span>
                                @endif
                            </div>

                            @if(!empty(config('captcha.sitekey')) && !empty(config('captcha.secret')))
                            <div class="col-12 mb-3">
                                {!! app('captcha')->display() !!}
                                @if ($errors->has('g-recaptcha-response'))
                                <span class="text-danger" style="font-size: 12px;">{{ $errors->first('g-recaptcha-response') }}</span>
                                @endif
                            </div>
                            @endif

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; padding: 12px 28px; border-radius: 8px; font-weight: 700; font-size: 15px; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                                    <i class="fa fa-send"></i> Submit Support Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN: OFFICE & OPERATING HOURS -->
            <div class="col-lg-4 col-12 mb-4">
                
                <!-- Operating Hours Card -->
                <div class="side-support-card">
                    <h3 class="side-support-title">
                        <i class="fa fa-clock-o text-primary"></i> Operating Hours
                    </h3>
                    <div style="font-size: 13.5px; color: #334155; line-height: 1.8;">
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding: 6px 0;">
                            <span>Monday - Friday:</span>
                            <strong>9:30 AM - 6:30 PM</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding: 6px 0;">
                            <span>Saturday:</span>
                            <strong>10:00 AM - 4:00 PM</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 6px 0;">
                            <span>Sunday:</span>
                            <span class="text-muted">Closed (Online Support)</span>
                        </div>
                    </div>
                </div>

                <!-- Google Map Card -->
                <div class="side-support-card" style="padding: 0; overflow: hidden;">
                    <div style="padding: 16px 20px; border-bottom: 1px solid #E2E8F0;">
                        <h3 class="side-support-title" style="margin: 0;">
                            <i class="fa fa-map-marker text-danger"></i> Our Location
                        </h3>
                    </div>
                    <div style="height: 220px; width: 100%;">
                        @if(!empty($siteSetting->site_google_map) && strpos($siteSetting->site_google_map, '<iframe') !== false)
                            {!! str_replace('<iframe', '<iframe style="border:0; width:100%; height:220px;"', $siteSetting->site_google_map) !!}
                        @else
                            <iframe src="https://maps.google.com/maps?q={{ urlencode($siteSetting->site_street_address ?? 'Nagpur, Maharashtra, India') }}&t=&z=13&ie=UTF8&iwloc=&output=embed" style="border:0; width:100%; height:220px;" allowfullscreen="" loading="lazy"></iframe>
                        @endif
                    </div>
                </div>

                <!-- Quick Help Tips -->
                <div class="side-support-card" style="background: #F0FDF4; border-color: #BBF7D0;">
                    <h3 class="side-support-title" style="color: #03855c;">
                        <i class="fa fa-lightbulb-o"></i> Need Immediate Help?
                    </h3>
                    <p style="font-size: 13px; color: #166534; line-height: 1.5; margin-bottom: 10px;">
                        For urgent inquiries or fast job vacancy postings, you can directly connect with our support team on WhatsApp.
                    </p>
                    <a href="https://api.whatsapp.com/send?phone={{ $waPhone }}&text=Hi%20Jobs%20Portal,%20I%20need%20quick%20support" target="_blank" class="btn btn-sm btn-success" style="background: #03855c; border-color: #03855c; font-weight: 700; border-radius: 6px;">
                        <i class="fa fa-whatsapp"></i> Chat with Support
                    </a>
                </div>

            </div>

        </div>

        <!-- FAQ SECTION -->
        <div class="faq-accordion-box">
            <h2 style="font-size: 22px; font-weight: 800; color: #0F172A; margin-bottom: 6px; text-align: center;">
                Frequently Asked Questions
            </h2>
            <p style="font-size: 14px; color: #64748B; text-align: center; margin-bottom: 24px;">
                Find quick answers to common questions about job searches, employer postings, and portal accounts.
            </p>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(1)">
                    <span>1. Is registration and job applying free for candidates?</span>
                    <i class="fa fa-chevron-down" id="faq-icon-1"></i>
                </div>
                <div class="faq-answer" id="faq-ans-1">
                    Yes! Creating a profile, uploading your resume, searching vacancies, and applying for jobs is 100% free for all job seekers across India.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(2)">
                    <span>2. How do employers post job vacancies?</span>
                    <i class="fa fa-chevron-down" id="faq-icon-2"></i>
                </div>
                <div class="faq-answer" id="faq-ans-2">
                    Employers can register an account, choose a suitable hiring package, and post vacancies instantly. Our platform allows direct applicant tracking, resume downloads, and candidate communications.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(3)">
                    <span>3. How can I contact hiring HR or recruiters directly?</span>
                    <i class="fa fa-chevron-down" id="faq-icon-3"></i>
                </div>
                <div class="faq-answer" id="faq-ans-3">
                    When applying for a job, you can view the hiring company details. Verified employer contacts including HR phone and WhatsApp numbers are accessible based on the job listing settings.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(4)">
                    <span>4. How do I update or change my registered email and phone number?</span>
                    <i class="fa fa-chevron-down" id="faq-icon-4"></i>
                </div>
                <div class="faq-answer" id="faq-ans-4">
                    Log in to your Dashboard, navigate to "My Profile" &rarr; "Account Settings", and you can update your contact details, skills, and resume anytime.
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function toggleFaq(id) {
    var $ans = $('#faq-ans-' + id);
    var $icon = $('#faq-icon-' + id);
    $ans.slideToggle(200, function() {
        if ($(this).is(':visible')) {
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        } else {
            $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
    });
}
</script>
@endpush

<!-- Footer start -->
@include('includes.footer')
<!-- Footer end -->
@endsection