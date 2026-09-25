<style>
/* Modern Premium Dark Footer - JobNBiz */
.demo-footer-wrap {
    background: #0B1320;
    color: #94A3B8;
    width: 100%;
    position: relative;
    font-family: inherit;
}

/* Main Top Section */
.df-main-section {
    padding: 60px 0 44px 0;
}
.df-main-grid {
    display: grid;
    grid-template-columns: 1.35fr 0.95fr 1.25fr 1.25fr 1.15fr;
    gap: 32px;
}

/* Column 1: Brand & Logo */
.df-brand-col {
    padding-right: 10px;
}
.df-logo-brand {
    font-size: 32px;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.6px;
    display: inline-block;
    text-decoration: none !important;
    margin-bottom: 6px;
}
.df-logo-jobn {
    color: #FFFFFF;
}
.df-logo-biz {
    color: #0284C7;
}
.df-brand-tagline {
    font-size: 13px;
    color: #94A3B8;
    font-weight: 500;
    margin: 0 0 16px 0;
}
.df-brand-desc {
    font-size: 13.5px;
    color: #94A3B8;
    line-height: 1.55;
    margin: 0 0 22px 0;
    max-width: 270px;
}

/* Social Buttons */
.df-social-row {
    display: flex;
    align-items: center;
    gap: 10px;
}
.df-social-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #1E293B;
    color: #CBD5E1 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    text-decoration: none !important;
    transition: all 0.22s ease;
}
.df-social-btn:hover {
    background: #0284C7;
    color: #FFFFFF !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
}

/* Column Headings */
.df-col-title {
    font-size: 16px;
    font-weight: 700;
    color: #FFFFFF;
    margin: 0 0 20px 0;
    letter-spacing: -0.2px;
}

/* Link Lists */
.df-link-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.df-link-list li {
    margin-bottom: 10px;
}
.df-link-list a {
    color: #94A3B8;
    font-size: 13.5px;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}
.df-link-list a .df-chevron {
    font-size: 11px;
    color: #64748B;
    transition: transform 0.2s ease, color 0.2s ease;
}
.df-link-list a:hover {
    color: #38BDF8;
    transform: translateX(3px);
}
.df-link-list a:hover .df-chevron {
    color: #38BDF8;
}

/* Column 5: Contact Us */
.df-contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
    font-size: 13.5px;
    color: #CBD5E1;
}
.df-contact-item i {
    width: 18px;
    font-size: 15px;
    color: #94A3B8;
    text-align: center;
}
.df-contact-item a {
    color: #CBD5E1;
    text-decoration: none !important;
    transition: color 0.2s ease;
}
.df-contact-item a:hover {
    color: #38BDF8;
}
.df-phone-txt {
    font-weight: 700;
    color: #FFFFFF !important;
    font-size: 15px;
    letter-spacing: 0.3px;
}

/* Question Callout */
.df-question-box {
    margin-top: 24px;
}
.df-q-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #FFFFFF;
    margin: 0 0 2px 0;
}
.df-q-sub {
    font-size: 12px;
    color: #94A3B8;
    margin: 0 0 12px 0;
}
.df-q-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    border: 1.5px solid #0284C7;
    color: #38BDF8 !important;
    padding: 7px 18px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none !important;
    transition: all 0.22s ease;
}
.df-q-btn:hover {
    background: #0284C7;
    color: #FFFFFF !important;
    box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
}

/* Middle Sub-footer Bar (Popular Cities + Legal Links) */
.df-mid-bar {
    border-top: 1px solid #1E293B;
    padding: 18px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px 20px;
    font-size: 12.5px;
}
.df-mid-left {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px 12px;
}
.df-mid-pin {
    color: #0284C7;
    font-size: 15px;
    margin-right: -2px;
}
.df-mid-label {
    color: #FFFFFF;
    font-weight: 700;
    font-size: 13px;
}
.df-mid-left a {
    color: #94A3B8;
    text-decoration: none !important;
    transition: color 0.2s ease;
}
.df-mid-left a:hover {
    color: #38BDF8;
}
.df-divider {
    color: #334155;
    user-select: none;
}

.df-mid-right {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-left: auto;
}
.df-mid-right a {
    color: #94A3B8;
    text-decoration: none !important;
    transition: color 0.2s ease;
}
.df-mid-right a:hover {
    color: #38BDF8;
}

/* Bottom Bar (Copyright + Secure Payments) */
.df-bottom-bar {
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    padding: 18px 0 28px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 12.5px;
}
.df-copyright {
    color: #94A3B8;
}

/* Payment Logos */
.df-payments {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #94A3B8;
    font-size: 12px;
}
.df-payments i {
    color: #94A3B8;
}
.df-paypal-logo {
    font-weight: 800;
    font-size: 16px;
    color: #0079C1;
    letter-spacing: -0.5px;
    font-style: italic;
}
.df-paypal-logo span {
    color: #00457C;
}
.df-stripe-logo {
    font-weight: 800;
    font-size: 17px;
    color: #635BFF;
    letter-spacing: -0.5px;
    text-transform: lowercase;
}

/* Mobile & Tablet Responsiveness */
@media (max-width: 1199px) {
    .df-main-grid {
        grid-template-columns: 1.2fr 1fr 1.2fr 1.2fr 1.1fr;
        gap: 24px;
    }
}

@media (max-width: 991px) {
    .df-main-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 28px;
    }
    .df-brand-col {
        grid-column: span 2;
        max-width: 100%;
    }
    .df-brand-desc {
        max-width: 100%;
    }
    .df-mid-right {
        margin-left: 0;
    }
}

@media (max-width: 767px) {
    .df-main-section {
        padding: 40px 0 28px 0 !important;
    }
    .df-main-grid {
        grid-template-columns: 1fr !important;
        gap: 26px !important;
    }
    .df-brand-col {
        grid-column: span 1 !important;
    }
    .df-mid-bar {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 12px !important;
        padding: 16px 0 !important;
    }
    .df-mid-right {
        width: 100% !important;
        justify-content: flex-start !important;
        flex-wrap: wrap !important;
    }
    .df-bottom-bar {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 12px !important;
        padding: 16px 0 24px 0 !important;
    }
}
</style>

<footer class="demo-footer-wrap">
    <div class="container">
        {{-- 5 Columns Main Footer --}}
        <div class="df-main-section">
            <div class="df-main-grid">
                
                {{-- Column 1: Brand / Logo --}}
                <div class="df-brand-col">
                    <a href="{{ route('index') }}" class="df-logo-brand">
                        <span class="df-logo-jobn">JobN</span><span class="df-logo-biz">Biz</span>
                    </a>
                    <div class="df-brand-tagline">
                        {{ __('Jobs. People. Business. Together.') }}
                    </div>
                    <p class="df-brand-desc">
                        {{ __('Find jobs, hire talent and discover businesses in one trusted platform.') }}
                    </p>
                    <div class="df-social-row">
                        <a href="https://linkedin.com" target="_blank" rel="noopener" class="df-social-btn" title="LinkedIn">
                            <i class="fa fa-linkedin"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" rel="noopener" class="df-social-btn" title="Instagram">
                            <i class="fa fa-instagram"></i>
                        </a>
                        <a href="https://wa.me/917038424139" target="_blank" rel="noopener" class="df-social-btn" title="WhatsApp">
                            <i class="fa fa-whatsapp"></i>
                        </a>
                        <a href="https://youtube.com" target="_blank" rel="noopener" class="df-social-btn" title="YouTube">
                            <i class="fa fa-youtube-play"></i>
                        </a>
                    </div>
                </div>

                {{-- Column 2: Quick Links --}}
                <div>
                    <h4 class="df-col-title">{{ __('Quick Links') }}</h4>
                    <ul class="df-link-list">
                        <li><a href="{{ route('index') }}"><span class="df-chevron">&gt;</span> {{ __('Home') }}</a></li>
                        <li><a href="{{ route('job.list') }}"><span class="df-chevron">&gt;</span> {{ __('Jobs') }}</a></li>
                        <li><a href="{{ url('/companies') }}"><span class="df-chevron">&gt;</span> {{ __('Companies') }}</a></li>
                        <li><a href="{{ route('business.list') }}"><span class="df-chevron">&gt;</span> {{ __('Businesses') }}</a></li>
                        <li><a href="{{ route('blogs') }}"><span class="df-chevron">&gt;</span> {{ __('Blog') }}</a></li>
                        <li><a href="{{ route('contact.us') }}"><span class="df-chevron">&gt;</span> {{ __('Contact Us') }}</a></li>
                        <li><a href="{{ route('faq') }}"><span class="df-chevron">&gt;</span> {{ __('FAQs') }}</a></li>
                        <li><a href="{{ url('/about-us') }}"><span class="df-chevron">&gt;</span> {{ __('About Us') }}</a></li>
                    </ul>
                </div>

                {{-- Column 3: Jobs By Functional Area --}}
                <div>
                    <h4 class="df-col-title">{{ __('Jobs By Functional Area') }}</h4>
                    <ul class="df-link-list">
                        <li><a href="{{ route('job.list', ['search' => 'Sales & Marketing']) }}"><span class="df-chevron">&gt;</span> {{ __('Sales & Marketing') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Accounts & Finance']) }}"><span class="df-chevron">&gt;</span> {{ __('Accounts & Finance') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Human Resources']) }}"><span class="df-chevron">&gt;</span> {{ __('Human Resources (HR)') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Customer Support']) }}"><span class="df-chevron">&gt;</span> {{ __('Customer Support / BPO') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Digital Marketing']) }}"><span class="df-chevron">&gt;</span> {{ __('Digital Marketing') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Design']) }}"><span class="df-chevron">&gt;</span> {{ __('Graphic & UI/UX Design') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Logistics']) }}"><span class="df-chevron">&gt;</span> {{ __('Operations & Logistics') }}</a></li>
                    </ul>
                </div>

                {{-- Column 4: Jobs By Industry --}}
                <div>
                    <h4 class="df-col-title">{{ __('Jobs By Industry') }}</h4>
                    <ul class="df-link-list">
                        <li><a href="{{ route('job.list', ['search' => 'Information Technology']) }}"><span class="df-chevron">&gt;</span> {{ __('Information Technology') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Banking & Financial']) }}"><span class="df-chevron">&gt;</span> {{ __('Banking & Financial') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Healthcare']) }}"><span class="df-chevron">&gt;</span> {{ __('Healthcare & Pharma') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Manufacturing']) }}"><span class="df-chevron">&gt;</span> {{ __('Manufacturing & Engineering') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Education']) }}"><span class="df-chevron">&gt;</span> {{ __('Education & Training') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Retail']) }}"><span class="df-chevron">&gt;</span> {{ __('Retail & E-commerce') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Real Estate']) }}"><span class="df-chevron">&gt;</span> {{ __('Real Estate & Construction') }}</a></li>
                        <li><a href="{{ route('job.list', ['search' => 'Automobile']) }}"><span class="df-chevron">&gt;</span> {{ __('Automobile & Aviation') }}</a></li>
                    </ul>
                </div>

                {{-- Column 5: Contact Us --}}
                <div>
                    <h4 class="df-col-title">{{ __('Contact Us') }}</h4>
                    
                    <div class="df-contact-item">
                        <i class="fa fa-map-marker"></i>
                        <span>{{ __('Nagpur') }}</span>
                    </div>

                    <div class="df-contact-item">
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:solocomdigi@gmail.com">solocomdigi@gmail.com</a>
                    </div>

                    <div class="df-contact-item">
                        <i class="fa fa-phone"></i>
                        <a href="tel:7038424139" class="df-phone-txt">7038424139</a>
                    </div>

                    <div class="df-question-box">
                        <div class="df-q-title">{{ __('Have a question?') }}</div>
                        <div class="df-q-sub">{{ __("We're here to help.") }}</div>
                        <a href="{{ route('contact.us') }}" class="df-q-btn">
                            <span>{{ __('Get in Touch') }}</span> <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Middle Sub-footer Bar (Popular Cities + Legal Links) --}}
        <div class="df-mid-bar">
            <div class="df-mid-left">
                <i class="fa fa-map-marker df-mid-pin"></i>
                <span class="df-mid-label">{{ __('Popular Cities:') }}</span>
                <a href="{{ url('/jobs-in-nagpur') }}">{{ __('Jobs in Nagpur') }}</a>
                <span class="df-divider">|</span>
                <a href="{{ url('/jobs-in-pune') }}">{{ __('Jobs in Pune') }}</a>
                <span class="df-divider">|</span>
                <a href="{{ url('/jobs-in-mumbai') }}">{{ __('Jobs in Mumbai') }}</a>
                <span class="df-divider">|</span>
                <a href="{{ url('/jobs-in-bangalore') }}">{{ __('Jobs in Bangalore') }}</a>
            </div>

            <div class="df-mid-right">
                <a href="{{ route('cms', 'privacy-policy') }}">{{ __('Privacy Policy') }}</a>
                <span class="df-divider">|</span>
                <a href="{{ route('cms', 'terms-of-use') }}">{{ __('Terms of Use') }}</a>
                <span class="df-divider">|</span>
                <a href="{{ url('/sitemap') }}">{{ __('Sitemap') }}</a>
            </div>
        </div>

        {{-- Bottom Bar (Copyright + Secure Payments) --}}
        <div class="df-bottom-bar">
            <div class="df-copyright">
                &copy; {{ date('Y') }} {{ __('JobNBiz. All rights reserved.') }}
            </div>

            <div class="df-payments">
                <i class="fa fa-lock"></i>
                <span>{{ __('Secure Payments:') }}</span>
                <span class="df-paypal-logo">Pay<span>Pal</span></span>
                <span class="df-divider">|</span>
                <span class="df-stripe-logo">stripe</span>
            </div>
        </div>
    </div>
</footer>
