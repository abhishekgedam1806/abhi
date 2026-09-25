<style>
/* How It Works Section - Minimalist, Multi-Audience & Intuitive */
.demo-hiw-section {
    padding: 56px 0 48px 0;
    background: #FFFFFF;
    width: 100%;
    position: relative;
}

/* Section Header */
.demo-hiw-header {
    text-align: center;
    max-width: 680px;
    margin: 0 auto 32px auto;
}
.demo-hiw-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #F1F5F9;
    color: #475569;
    font-size: 11.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.9px;
    padding: 5px 14px;
    border-radius: 50px;
    margin-bottom: 10px;
    border: 1px solid #E2E8F0;
}
.demo-hiw-title {
    font-size: 28px;
    font-weight: 800;
    color: #0F172A;
    letter-spacing: -0.5px;
    margin: 0 0 8px 0;
}
.demo-hiw-subtitle {
    font-size: 14.5px;
    color: #64748B;
    line-height: 1.5;
    margin: 0;
}

/* Segmented Audience Tabs */
.demo-hiw-tabs {
    display: inline-flex;
    background: #F1F5F9;
    padding: 5px;
    border-radius: 14px;
    gap: 4px;
    margin: 0 auto 36px auto;
    border: 1px solid #E2E8F0;
    max-width: 100%;
}
.demo-hiw-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 20px;
    font-size: 13.5px;
    font-weight: 700;
    color: #64748B;
    background: transparent;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
    outline: none !important;
}
.demo-hiw-tab-btn:hover {
    color: #0F172A;
}
.demo-hiw-tab-btn.active {
    background: #FFFFFF;
    color: #0F172A;
    box-shadow: 0 3px 10px rgba(15, 23, 42, 0.08);
}
.demo-hiw-tab-btn.active.tab-seekers {
    color: #2563EB;
}
.demo-hiw-tab-btn.active.tab-employers {
    color: #059669;
}
.demo-hiw-tab-btn.active.tab-businesses {
    color: #7C3AED;
}

/* Tab Panels */
.demo-hiw-panel {
    display: none;
    animation: hiwFadeIn 0.3s ease;
}
.demo-hiw-panel.active {
    display: block;
}
@keyframes hiwFadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

/* 3-Step Cards Grid */
.demo-hiw-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    position: relative;
    margin-bottom: 34px;
}

/* Step Card */
.demo-hiw-step-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 28px 24px 24px 24px;
    position: relative;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}
.demo-hiw-step-card:hover {
    background: #FFFFFF;
    border-color: #CBD5E1;
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.07);
}

/* Top Step Indicator & Icon */
.demo-hiw-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.demo-hiw-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    transition: transform 0.2s ease;
}
.demo-hiw-step-card:hover .demo-hiw-icon-box {
    transform: scale(1.08);
}
.icon-box-seekers {
    background: #EFF6FF;
    color: #2563EB;
}
.icon-box-employers {
    background: #ECFDF5;
    color: #059669;
}
.icon-box-biz {
    background: #F5F3FF;
    color: #7C3AED;
}

.demo-hiw-step-num {
    font-size: 13px;
    font-weight: 800;
    color: #94A3B8;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    padding: 4px 10px;
    border-radius: 20px;
    letter-spacing: 0.5px;
}

/* Content */
.demo-hiw-step-title {
    font-size: 18px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 8px 0;
    letter-spacing: -0.2px;
}
.demo-hiw-step-desc {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.5;
    margin: 0;
}

/* Call to Action Row */
.demo-hiw-cta-wrap {
    text-align: center;
    margin-top: 10px;
}
.demo-hiw-cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    font-size: 14px;
    font-weight: 700;
    padding: 12px 28px;
    border-radius: 11px;
    text-decoration: none !important;
    transition: all 0.22s ease;
    color: #FFFFFF !important;
}
.demo-hiw-cta-btn i {
    transition: transform 0.2s ease;
}
.demo-hiw-cta-btn:hover i {
    transform: translateX(4px);
}

.btn-cta-seekers {
    background: #2563EB;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
}
.btn-cta-seekers:hover {
    background: #1D4ED8;
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
    transform: translateY(-2px);
}

.btn-cta-employers {
    background: #059669;
    box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25);
}
.btn-cta-employers:hover {
    background: #047857;
    box-shadow: 0 6px 20px rgba(5, 150, 105, 0.35);
    transform: translateY(-2px);
}

.btn-cta-biz {
    background: #7C3AED;
    box-shadow: 0 4px 14px rgba(124, 58, 237, 0.25);
}
.btn-cta-biz:hover {
    background: #6D28D9;
    box-shadow: 0 6px 20px rgba(124, 58, 237, 0.35);
    transform: translateY(-2px);
}

/* Mobile & Tablet Responsiveness */
@media (max-width: 991px) {
    .demo-hiw-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    .demo-hiw-tabs {
        width: 100%;
        overflow-x: auto;
        justify-content: flex-start;
        padding: 4px;
        -webkit-overflow-scrolling: touch;
    }
    .demo-hiw-tab-btn {
        flex: 1 0 auto;
        padding: 8px 14px;
        font-size: 12.5px;
    }
}

@media (max-width: 767px) {
    .demo-hiw-section {
        padding: 34px 0 28px 0 !important;
    }
    .demo-hiw-title {
        font-size: 21px !important;
        margin-bottom: 6px !important;
    }
    .demo-hiw-subtitle {
        font-size: 13px !important;
        line-height: 1.4 !important;
    }
    .demo-hiw-header {
        margin-bottom: 22px !important;
    }
    .demo-hiw-step-card {
        padding: 18px 16px !important;
        border-radius: 14px !important;
    }
    .demo-hiw-card-head {
        margin-bottom: 14px !important;
    }
    .demo-hiw-icon-box {
        width: 44px !important;
        height: 44px !important;
        font-size: 18px !important;
        border-radius: 10px !important;
    }
    .demo-hiw-step-title {
        font-size: 16px !important;
    }
    .demo-hiw-step-desc {
        font-size: 12.5px !important;
    }
    .demo-hiw-cta-btn {
        width: 100% !important;
        justify-content: center !important;
        padding: 11px 20px !important;
        font-size: 13.5px !important;
    }
}
</style>

<div class="demo-hiw-section">
    <div class="container">
        {{-- Section Header --}}
        <div class="demo-hiw-header">
            <div class="demo-hiw-badge">
                <i class="fa fa-lightbulb-o"></i> {{ __('Simple & Seamless') }}
            </div>
            <h2 class="demo-hiw-title">
                {{ __('How JobNBiz Works') }}
            </h2>
            <p class="demo-hiw-subtitle">
                {{ __('Get things done in 3 straightforward steps tailored to your goals.') }}
            </p>
        </div>

        {{-- Segmented Audience Switcher Tabs --}}
        <div class="text-center">
            <div class="demo-hiw-tabs" role="tablist">
                <button type="button" 
                        class="demo-hiw-tab-btn tab-seekers active" 
                        onclick="switchHiwTab('seekers', this)">
                    <i class="fa fa-user"></i> {{ __('For Job Seekers') }}
                </button>
                <button type="button" 
                        class="demo-hiw-tab-btn tab-employers" 
                        onclick="switchHiwTab('employers', this)">
                    <i class="fa fa-briefcase"></i> {{ __('For Employers') }}
                </button>
                <button type="button" 
                        class="demo-hiw-tab-btn tab-businesses" 
                        onclick="switchHiwTab('businesses', this)">
                    <i class="fa fa-building-o"></i> {{ __('For Businesses') }}
                </button>
            </div>
        </div>

        {{-- Panel 1: For Job Seekers --}}
        <div id="hiw-panel-seekers" class="demo-hiw-panel active">
            <div class="demo-hiw-grid">
                {{-- Step 1 --}}
                <div class="demo-hiw-step-card">
                    <div class="demo-hiw-card-head">
                        <div class="demo-hiw-icon-box icon-box-seekers">
                            <i class="fa fa-id-card-o"></i>
                        </div>
                        <span class="demo-hiw-step-num">01</span>
                    </div>
                    <h3 class="demo-hiw-step-title">{{ __('Create Profile') }}</h3>
                    <p class="demo-hiw-step-desc">
                        {{ __('Upload your resume, specify your preferred location, salary, and career domain in under 2 minutes.') }}
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="demo-hiw-step-card">
                    <div class="demo-hiw-card-head">
                        <div class="demo-hiw-icon-box icon-box-seekers">
                            <i class="fa fa-search"></i>
                        </div>
                        <span class="demo-hiw-step-num">02</span>
                    </div>
                    <h3 class="demo-hiw-step-title">{{ __('Discover & Apply') }}</h3>
                    <p class="demo-hiw-step-desc">
                        {{ __('Search thousands of active, verified openings with instant 1-click application directly to employers.') }}
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="demo-hiw-step-card">
                    <div class="demo-hiw-card-head">
                        <div class="demo-hiw-icon-box icon-box-seekers">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <span class="demo-hiw-step-num">03</span>
                    </div>
                    <h3 class="demo-hiw-step-title">{{ __('Get Hired') }}</h3>
                    <p class="demo-hiw-step-desc">
                        {{ __('Connect directly with HR teams, attend interviews, and secure your desired job offer without middlemen.') }}
                    </p>
                </div>
            </div>

            <div class="demo-hiw-cta-wrap">
                <a href="{{ route('job.list') }}" class="demo-hiw-cta-btn btn-cta-seekers">
                    <span>{{ __('Explore Open Jobs') }}</span> <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Panel 2: For Employers --}}
        <div id="hiw-panel-employers" class="demo-hiw-panel">
            <div class="demo-hiw-grid">
                {{-- Step 1 --}}
                <div class="demo-hiw-step-card">
                    <div class="demo-hiw-card-head">
                        <div class="demo-hiw-icon-box icon-box-employers">
                            <i class="fa fa-pencil-square-o"></i>
                        </div>
                        <span class="demo-hiw-step-num">01</span>
                    </div>
                    <h3 class="demo-hiw-step-title">{{ __('Post a Job') }}</h3>
                    <p class="demo-hiw-step-desc">
                        {{ __('Define your hiring requirements, set role parameters, and publish your opening to active talent across India.') }}
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="demo-hiw-step-card">
                    <div class="demo-hiw-card-head">
                        <div class="demo-hiw-icon-box icon-box-employers">
                            <i class="fa fa-filter"></i>
                        </div>
                        <span class="demo-hiw-step-num">02</span>
                    </div>
                    <h3 class="demo-hiw-step-title">{{ __('Review Matches') }}</h3>
                    <p class="demo-hiw-step-desc">
                        {{ __('Receive filtered, verified applicant profiles tailored to your industry skills and experience criteria.') }}
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="demo-hiw-step-card">
                    <div class="demo-hiw-card-head">
                        <div class="demo-hiw-icon-box icon-box-employers">
                            <i class="fa fa-handshake-o"></i>
                        </div>
                        <span class="demo-hiw-step-num">03</span>
                    </div>
                    <h3 class="demo-hiw-step-title">{{ __('Hire Quickly') }}</h3>
                    <p class="demo-hiw-step-desc">
                        {{ __('Contact top candidates directly, schedule interviews, and close positions quickly with high-efficiency hiring.') }}
                    </p>
                </div>
            </div>

            <div class="demo-hiw-cta-wrap">
                <a href="{{ route('post.job') }}" class="demo-hiw-cta-btn btn-cta-employers">
                    <span>{{ __('Post a Vacancy Now') }}</span> <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Panel 3: For Businesses --}}
        <div id="hiw-panel-businesses" class="demo-hiw-panel">
            <div class="demo-hiw-grid">
                {{-- Step 1 --}}
                <div class="demo-hiw-step-card">
                    <div class="demo-hiw-card-head">
                        <div class="demo-hiw-icon-box icon-box-biz">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <span class="demo-hiw-step-num">01</span>
                    </div>
                    <h3 class="demo-hiw-step-title">{{ __('List Your Business') }}</h3>
                    <p class="demo-hiw-step-desc">
                        {{ __('Register your business profile, add location, photos, services, and direct contact numbers in minutes.') }}
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="demo-hiw-step-card">
                    <div class="demo-hiw-card-head">
                        <div class="demo-hiw-icon-box icon-box-biz">
                            <i class="fa fa-bullhorn"></i>
                        </div>
                        <span class="demo-hiw-step-num">02</span>
                    </div>
                    <h3 class="demo-hiw-step-title">{{ __('Gain Local Visibility') }}</h3>
                    <p class="demo-hiw-step-desc">
                        {{ __('Get discovered by local customers actively searching for your services and products in your city.') }}
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="demo-hiw-step-card">
                    <div class="demo-hiw-card-head">
                        <div class="demo-hiw-icon-box icon-box-biz">
                            <i class="fa fa-line-chart"></i>
                        </div>
                        <span class="demo-hiw-step-num">03</span>
                    </div>
                    <h3 class="demo-hiw-step-title">{{ __('Grow Customers') }}</h3>
                    <p class="demo-hiw-step-desc">
                        {{ __('Receive direct calls, WhatsApp inquiries, and in-person footfall to build your reputation and scale revenue.') }}
                    </p>
                </div>
            </div>

            <div class="demo-hiw-cta-wrap">
                <a href="{{ route('business.list') }}" class="demo-hiw-cta-btn btn-cta-biz">
                    <span>{{ __('Discover & List Businesses') }}</span> <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function switchHiwTab(target, btn) {
    // Remove active from all buttons
    var buttons = document.querySelectorAll('.demo-hiw-tab-btn');
    buttons.forEach(function(b) {
        b.classList.remove('active');
    });
    btn.classList.add('active');

    // Hide all panels
    var panels = document.querySelectorAll('.demo-hiw-panel');
    panels.forEach(function(p) {
        p.classList.remove('active');
    });

    // Show target panel
    var targetPanel = document.getElementById('hiw-panel-' + target);
    if (targetPanel) {
        targetPanel.classList.add('active');
    }
}
</script>
