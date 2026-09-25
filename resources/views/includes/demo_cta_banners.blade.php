<style>
/* High-Conversion Integrated CTA Banners Section */
.demo-cta-section {
    padding: 48px 0 44px 0;
    background: #FFFFFF;
    width: 100%;
}
.demo-cta-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

/* Individual CTA Banner */
.demo-cta-card {
    position: relative;
    border-radius: 18px;
    border: 1px solid #E2E8F0;
    padding: 22px 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
    min-height: 200px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
}
.demo-cta-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
}

/* Card 1: Job Seekers (Blue) */
.demo-cta-seekers {
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    border-color: #BFDBFE;
}
.demo-cta-seekers:hover {
    border-color: #2563EB;
}
.demo-cta-icon-blue {
    background: #2563EB;
    color: #FFFFFF;
}
.demo-cta-btn-blue {
    background: #2563EB;
    color: #FFFFFF !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}
.demo-cta-btn-blue:hover {
    background: #1D4ED8;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
}

/* Card 2: Employers (Green) */
.demo-cta-employers {
    background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
    border-color: #A7F3D0;
}
.demo-cta-employers:hover {
    border-color: #059669;
}
.demo-cta-icon-green {
    background: #059669;
    color: #FFFFFF;
}
.demo-cta-btn-green {
    background: #059669;
    color: #FFFFFF !important;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
}
.demo-cta-btn-green:hover {
    background: #047857;
    box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
}

/* Card 3: Businesses (Purple) */
.demo-cta-biz {
    background: linear-gradient(135deg, #FAF5FF 0%, #EDE9FE 100%);
    border-color: #DDD6FE;
}
.demo-cta-biz:hover {
    border-color: #7C3AED;
}
.demo-cta-icon-purple {
    background: #7C3AED;
    color: #FFFFFF;
}
.demo-cta-btn-purple {
    background: #7C3AED;
    color: #FFFFFF !important;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
}
.demo-cta-btn-purple:hover {
    background: #6D28D9;
    box-shadow: 0 6px 16px rgba(124, 58, 237, 0.35);
}

/* Header & Text */
.demo-cta-top {
    position: relative;
    z-index: 2;
    padding-right: 90px;
}
.demo-cta-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: 14px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}
.demo-cta-title {
    font-size: 19px;
    font-weight: 800;
    color: #0F172A;
    letter-spacing: -0.3px;
    margin: 0 0 6px 0;
    line-height: 1.25;
}
.demo-cta-desc {
    font-size: 12.5px;
    color: #475569;
    line-height: 1.45;
    margin: 0 0 18px 0;
}

/* Action Button */
.demo-cta-btn-wrap {
    position: relative;
    z-index: 2;
    margin-top: auto;
}
.demo-cta-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    font-size: 13.5px;
    font-weight: 700;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.demo-cta-btn i {
    transition: transform 0.2s ease;
}
.demo-cta-btn:hover i {
    transform: translateX(3px);
}

/* Floating Right Illustration Image */
.demo-cta-img-wrap {
    position: absolute;
    right: -10px;
    bottom: -5px;
    width: 125px;
    height: 165px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    pointer-events: none;
    z-index: 1;
}
.demo-cta-person-img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
    object-position: bottom right;
    transition: transform 0.25s ease;
}
.demo-cta-card:hover .demo-cta-person-img {
    transform: scale(1.05);
}

/* Tablet & Mobile Responsiveness */
@media (max-width: 991px) {
    .demo-cta-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    .demo-cta-card {
        min-height: 180px;
        padding: 20px 18px;
    }
}

@media (max-width: 767px) {
    .demo-cta-section {
        padding: 30px 0 26px 0 !important;
    }
    .demo-cta-card {
        border-radius: 14px !important;
        padding: 18px 16px !important;
    }
    .demo-cta-top {
        padding-right: 75px !important;
    }
    .demo-cta-icon {
        width: 38px !important;
        height: 38px !important;
        font-size: 15px !important;
        margin-bottom: 10px !important;
    }
    .demo-cta-title {
        font-size: 17px !important;
        margin-bottom: 4px !important;
    }
    .demo-cta-desc {
        font-size: 12px !important;
        margin-bottom: 14px !important;
    }
    .demo-cta-btn {
        width: 100% !important;
        padding: 10px 16px !important;
        font-size: 13px !important;
        min-height: 40px !important;
    }
    .demo-cta-img-wrap {
        width: 100px !important;
        height: 140px !important;
        right: -8px !important;
        bottom: -4px !important;
    }
}
</style>

<div class="demo-cta-section">
    <div class="container">
        <div class="demo-cta-grid">
            {{-- Card 1: Job Seekers --}}
            <div class="demo-cta-card demo-cta-seekers">
                <div class="demo-cta-top">
                    <div class="demo-cta-icon demo-cta-icon-blue">
                        <i class="fa fa-user"></i>
                    </div>
                    <h3 class="demo-cta-title">{{ __('Create Your Profile Today') }}</h3>
                    <p class="demo-cta-desc">
                        {{ __('Get AI-powered job recommendations and stay updated with the latest verified opportunities.') }}
                    </p>
                </div>

                <div class="demo-cta-btn-wrap">
                    <a href="{{ route('register') }}" class="demo-cta-btn demo-cta-btn-blue">
                        <span>{{ __('Create Free Account') }}</span> <i class="fa fa-arrow-right"></i>
                    </a>
                </div>

                <div class="demo-cta-img-wrap">
                    <img src="{{ asset('images/hero-man.png') }}" 
                         alt="Job Seeker Profile" 
                         class="demo-cta-person-img"
                         onerror="this.style.display='none'">
                </div>
            </div>

            {{-- Card 2: Employers --}}
            <div class="demo-cta-card demo-cta-employers">
                <div class="demo-cta-top">
                    <div class="demo-cta-icon demo-cta-icon-green">
                        <i class="fa fa-briefcase"></i>
                    </div>
                    <h3 class="demo-cta-title">{{ __('Start Hiring with JobNBiz') }}</h3>
                    <p class="demo-cta-desc">
                        {{ __('Find the right candidates faster with AI-powered matching tools and direct interviews.') }}
                    </p>
                </div>

                <div class="demo-cta-btn-wrap">
                    <a href="{{ route('post.job') }}" class="demo-cta-btn demo-cta-btn-green">
                        <span>{{ __('Post a Job') }}</span> <i class="fa fa-arrow-right"></i>
                    </a>
                </div>

                <div class="demo-cta-img-wrap">
                    <img src="{{ asset('images/seeker-woman.jpg') }}" 
                         alt="Hire Talent" 
                         class="demo-cta-person-img"
                         style="border-radius: 12px; height: 135px; width: 95px; object-fit: cover;"
                         onerror="this.style.display='none'">
                </div>
            </div>

            {{-- Card 3: Businesses --}}
            <div class="demo-cta-card demo-cta-biz">
                <div class="demo-cta-top">
                    <div class="demo-cta-icon demo-cta-icon-purple">
                        <i class="fa fa-home"></i>
                    </div>
                    <h3 class="demo-cta-title">{{ __('List Your Business') }}</h3>
                    <p class="demo-cta-desc">
                        {{ __('Increase your visibility and reach more local customers actively searching in your city.') }}
                    </p>
                </div>

                <div class="demo-cta-btn-wrap">
                    <a href="{{ route('business.list') }}" class="demo-cta-btn demo-cta-btn-purple">
                        <span>{{ __('List Your Business') }}</span> <i class="fa fa-arrow-right"></i>
                    </a>
                </div>

                <div class="demo-cta-img-wrap">
                    <img src="{{ asset('images/store-business.jpg') }}" 
                         alt="Local Business" 
                         class="demo-cta-person-img"
                         style="border-radius: 12px; height: 135px; width: 95px; object-fit: cover;"
                         onerror="this.style.display='none'">
                </div>
            </div>
        </div>
    </div>
</div>
