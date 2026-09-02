{{-- Pure Job Search Hero Section for Main Homepage --}}

<style>
/* Hero pill chips */
.naukri-hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 50px;
    padding: 5px 12px 5px 7px;
    font-size: 12.5px;
    font-weight: 600;
    color: #1E293B;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.naukri-hero-pill:hover {
    background: #F8FAFC;
    border-color: #CBD5E1;
    transform: translateY(-1px);
    color: #2563EB !important;
}
.naukri-hero-pill .pill-ico {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10.5px;
}

/* Floating stat cards on right */
.hero-stat-float {
    position: absolute;
    background: #FFFFFF;
    border-radius: 14px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 8px 24px rgba(15,23,42,0.12);
    border: 1px solid #E2E8F0;
    min-width: 140px;
    animation: floatBob 3s ease-in-out infinite;
}
.hero-stat-float:nth-child(2) { animation-delay: 1s; }
.hero-stat-float:nth-child(3) { animation-delay: 2s; }
@keyframes floatBob {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-6px); }
}
.hero-stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.hero-stat-num {
    font-size: 17px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.1;
}
.hero-stat-label {
    font-size: 11.5px;
    font-weight: 500;
    color: #64748B;
    margin-top: 1px;
}

/* Hero right image wrapper - Apna.co style */
.hero-img-wrap {
    position: relative;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    height: 100%;
    min-height: 0;
    overflow: visible;
}

/* ── Search form: align all dropdowns to bottom so 2-line labels don't misalign ── */
.searchbar .srcsubfld .row {
    align-items: flex-end !important;
}
.searchbar .srcsubfld .row > div {
    display: flex !important;
    flex-direction: column !important;
    justify-content: flex-end !important;
}
.searchbar .srcsubfld label {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 4px;
    min-height: 1.2em;
}

/* Hero Section Typography & Responsive Balance */
.apna-hero-section {
    position: relative;
    background: #F8FAFC;
    padding: 36px 0 0 0;
    overflow: hidden;
}
.apna-hero-badge {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    background: #2563EB !important;
    color: #FFFFFF !important;
    font-size: 11px !important;
    font-weight: 800 !important;
    letter-spacing: 0.8px !important;
    padding: 5px 14px !important;
    border-radius: 50px !important;
    margin-bottom: 14px !important;
    text-transform: uppercase !important;
    box-shadow: 0 2px 8px rgba(37,99,235,0.2) !important;
}
.apna-hero-title {
    font-family: 'Poppins', sans-serif !important;
    font-size: 42px !important;
    font-weight: 800 !important;
    color: #0F172A !important;
    line-height: 1.2 !important;
    margin-bottom: 12px !important;
    letter-spacing: -0.5px !important;
}
.apna-hero-highlight {
    color: #1B4FD8 !important;
    position: relative !important;
    display: inline !important;
}
.apna-hero-highlight::after {
    content: '' !important;
    position: absolute !important;
    bottom: -2px !important;
    left: 0 !important;
    width: 100% !important;
    height: 3.5px !important;
    background: #FF6B00 !important;
    border-radius: 2px !important;
}
.apna-hero-sub {
    font-size: 15.5px !important;
    color: #475569 !important;
    line-height: 1.5 !important;
    margin-bottom: 20px !important;
    max-width: 580px !important;
}

/* Tablet responsive (768px - 991px) */
@media (max-width: 991px) and (min-width: 768px) {
    .apna-hero-title {
        font-size: 32px !important;
        line-height: 1.22 !important;
    }
    .apna-hero-sub {
        font-size: 14.5px !important;
        margin-bottom: 16px !important;
    }
}

/* Mobile responsive (< 768px) */
@media (max-width: 767px) {
    .apna-hero-section {
        padding: 20px 0 16px 0 !important;
    }
    .apna-hero-left {
        padding-left: 16px !important;
        padding-right: 16px !important;
    }
    .apna-hero-badge {
        font-size: 10px !important;
        padding: 4px 11px !important;
        margin-bottom: 10px !important;
        letter-spacing: 0.5px !important;
    }
    .apna-hero-title {
        font-size: 24px !important;
        line-height: 1.25 !important;
        margin-bottom: 8px !important;
        letter-spacing: -0.3px !important;
    }
    .apna-hero-highlight::after {
        height: 2.5px !important;
        bottom: -1px !important;
    }
    .apna-hero-sub {
        font-size: 13px !important;
        line-height: 1.45 !important;
        margin-bottom: 14px !important;
        color: #64748B !important;
    }
    .searchbar .srcsubfld label {
        white-space: normal;
    }
    .apna-trending-chips {
        gap: 6px !important;
        margin-top: 10px !important;
    }
    .naukri-hero-pill {
        font-size: 11px !important;
        padding: 3px 9px 3px 5px !important;
    }
    .naukri-hero-pill .pill-ico {
        width: 18px !important;
        height: 18px !important;
        font-size: 9px !important;
    }
}

</style>

<div class="apna-hero-section">
    <div class="container">
        <div class="row align-items-center">

            {{-- LEFT: Text + Job Search --}}
            <div class="col-lg-7 col-md-7 col-12 apna-hero-left">
                @if(!empty($siteSetting->hero_badge_text))
                <div class="apna-hero-badge">
                    {{ $siteSetting->hero_badge_text }}
                </div>
                @else
                <div class="apna-hero-badge">
                    INDIA'S #1 JOB PLATFORM
                </div>
                @endif

                <h1 class="apna-hero-title">
                    {{ $siteSetting->hero_title_line1 ?: 'Find the right job.' }}<br class="d-none d-md-inline"> 
                    <span class="apna-hero-highlight">{{ $siteSetting->hero_title_line2 ?: 'Build your next opportunity.' }}</span>
                </h1>
                <p class="apna-hero-sub">
                    {{ $siteSetting->hero_subtitle ?: 'Search top jobs, connect with trusted employers and discover local businesses near you.' }}
                </p>

                {{-- JOB SEARCH FORM --}}
                <div class="apna-search-wrap" id="jobSearchContainer">
                    @include('includes.search_form')
                </div>

                {{-- Popular Job Chips --}}
                <div class="apna-trending-chips" style="margin-top: 14px; display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                    <span style="font-size: 12.5px; font-weight: 700; color: #334155; display: inline-flex; align-items: center; gap: 4px;"><i class="fa fa-bolt" style="color:#EAB308;"></i> Popular:</span>
                    <a href="{{ route('job.list', ['search' => 'Remote']) }}" class="naukri-hero-pill"><span class="pill-ico" style="background:#EEF2FF;color:#4F46E5;"><i class="fa fa-home"></i></span> Work From Home</a>
                    <a href="{{ route('job.list', ['search' => 'IT']) }}" class="naukri-hero-pill"><span class="pill-ico" style="background:#EFF6FF;color:#2563EB;"><i class="fa fa-desktop"></i></span> IT Jobs</a>
                    <a href="{{ route('job.list', ['search' => 'Fresher']) }}" class="naukri-hero-pill"><span class="pill-ico" style="background:#F0FDF4;color:#16A34A;"><i class="fa fa-graduation-cap"></i></span> Fresher</a>
                    <a href="{{ route('job.list', ['search' => 'Sales']) }}" class="naukri-hero-pill"><span class="pill-ico" style="background:#ECFDF5;color:#03855c;"><i class="fa fa-briefcase"></i></span> Sales</a>
                </div>
            </div>

            {{-- RIGHT: Man Image - full body, bottom-aligned --}}
            <div class="col-lg-5 col-md-5 col-12 d-none d-md-flex" style="align-items:flex-end; padding:0;">
                <div class="hero-img-wrap" style="width:100%;">
                    <img src="{{ asset('images/hero-man.png') }}"
                         alt="Jobs Portal"
                         class="hero-man-img"
                         width="450"
                         height="380"
                         loading="lazy"
                         decoding="async" />
                </div>
            </div>

        </div>
    </div>
</div>