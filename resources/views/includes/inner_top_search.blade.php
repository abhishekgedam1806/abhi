<div class="apna-top-search-banner">
    <div class="container">
        <form action="{{route('job.list')}}" method="get" class="apna-top-search-form" id="topJobSearchForm">
            {{-- Keep existing filters if set --}}
            @if(Request::filled('country_id'))
                <input type="hidden" name="country_id[]" value="{{ is_array(Request::get('country_id')) ? Request::get('country_id')[0] : Request::get('country_id') }}" />
            @endif
            @if(Request::filled('state_id'))
                <input type="hidden" name="state_id[]" value="{{ is_array(Request::get('state_id')) ? Request::get('state_id')[0] : Request::get('state_id') }}" />
            @endif
            @if(Request::filled('city_id'))
                <input type="hidden" name="city_id[]" value="{{ is_array(Request::get('city_id')) ? Request::get('city_id')[0] : Request::get('city_id') }}" />
            @endif

            <div class="apna-search-box-card">
                <div class="apna-search-input-wrap">
                    <i class="fa fa-search apna-search-icon"></i>
                    <input type="text" 
                           name="search" 
                           id="topJobSearchInput"
                           value="{{Request::get('search', '')}}" 
                           class="apna-search-input" 
                           placeholder="{{__('Search by job title, skills, keyword or company...')}}" 
                           autocomplete="off" />
                    @if(Request::filled('search'))
                        <a href="{{ route('job.list') }}" class="apna-search-clear-btn" title="Clear search">
                            <i class="fa fa-times-circle"></i>
                        </a>
                    @endif
                </div>

                <div class="apna-search-btn-wrap">
                    <button type="submit" class="apna-search-submit-btn">
                        <i class="fa fa-search"></i>
                        <span>{{__('Search Jobs')}}</span>
                    </button>
                </div>
            </div>

            {{-- Quick Trending Search Tags --}}
            <div class="apna-popular-tags-row">
                <span class="apna-popular-label"><i class="fa fa-bolt"></i> Popular:</span>
                <div class="apna-tags-scroll-wrap">
                    <a href="{{ route('job.list', ['search' => 'Work From Home']) }}" class="apna-pop-tag {{ Request::get('search') == 'Work From Home' ? 'active' : '' }}">Work From Home</a>
                    <a href="{{ route('job.list', ['search' => 'Full Stack Developer']) }}" class="apna-pop-tag {{ Request::get('search') == 'Full Stack Developer' ? 'active' : '' }}">Developer</a>
                    <a href="{{ route('job.list', ['search' => 'Telecaller']) }}" class="apna-pop-tag {{ Request::get('search') == 'Telecaller' ? 'active' : '' }}">Telecaller</a>
                    <a href="{{ route('job.list', ['search' => 'Accountant']) }}" class="apna-pop-tag {{ Request::get('search') == 'Accountant' ? 'active' : '' }}">Accountant</a>
                    <a href="{{ route('job.list', ['search' => 'Sales Executive']) }}" class="apna-pop-tag {{ Request::get('search') == 'Sales Executive' ? 'active' : '' }}">Sales Executive</a>
                    <a href="{{ route('job.list', ['search' => 'Fresher']) }}" class="apna-pop-tag {{ Request::get('search') == 'Fresher' ? 'active' : '' }}">Fresher Jobs</a>
                    <a href="{{ route('job.list', ['search' => 'Digital Marketing']) }}" class="apna-pop-tag {{ Request::get('search') == 'Digital Marketing' ? 'active' : '' }}">Digital Marketing</a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* ------------------------------------------------------------- */
/* APNA TOP SEARCH BAR COMPONENT                                 */
/* ------------------------------------------------------------- */
.apna-top-search-banner {
    background: #FFFFFF;
    border-bottom: 1px solid #E2E8F0;
    padding: 20px 0 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    margin-bottom: 24px;
}
.apna-top-search-form {
    width: 100%;
}
.apna-search-box-card {
    background: #FFFFFF;
    border: 2px solid #E2E8F0;
    border-radius: 14px;
    display: flex;
    align-items: center;
    padding: 6px 8px 6px 16px;
    gap: 12px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
    transition: all 0.2s ease;
}
.apna-search-box-card:focus-within {
    border-color: #2563EB;
    box-shadow: 0 4px 20px rgba(37, 99, 235, 0.15);
}
.apna-search-input-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    min-width: 0;
    position: relative;
}
.apna-search-icon {
    color: #2563EB;
    font-size: 18px;
    flex-shrink: 0;
}
.apna-search-input {
    width: 100%;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    background: transparent !important;
    font-size: 15px !important;
    color: #0F172A !important;
    font-weight: 500 !important;
    padding: 8px 0 !important;
    line-height: 1.5;
}
.apna-search-input::placeholder {
    color: #94A3B8 !important;
    font-weight: 400;
}
.apna-search-clear-btn {
    color: #94A3B8;
    font-size: 16px;
    text-decoration: none !important;
    padding: 4px;
    flex-shrink: 0;
    transition: color 0.15s ease;
}
.apna-search-clear-btn:hover {
    color: #EF4444;
}
.apna-search-btn-wrap {
    flex-shrink: 0;
}
.apna-search-submit-btn {
    background: #2563EB;
    color: #FFFFFF !important;
    border: none;
    border-radius: 10px;
    padding: 11px 26px;
    font-size: 14.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    transition: all 0.2s ease;
    letter-spacing: 0.2px;
}
.apna-search-submit-btn:hover {
    background: #1D4ED8;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
}
.apna-search-submit-btn:active {
    transform: translateY(0);
}

/* Popular Tags Row */
.apna-popular-tags-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 14px;
    flex-wrap: wrap;
}
.apna-popular-label {
    font-size: 12.5px;
    font-weight: 700;
    color: #64748B;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
}
.apna-popular-label i {
    color: #F59E0B;
}
.apna-tags-scroll-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.apna-pop-tag {
    background: #F1F5F9;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 50px;
    text-decoration: none !important;
    border: 1px solid transparent;
    transition: all 0.15s ease;
    white-space: nowrap;
}
.apna-pop-tag:hover {
    background: #EFF6FF;
    color: #2563EB;
    border-color: #BFDBFE;
}
.apna-pop-tag.active {
    background: #2563EB;
    color: #FFFFFF;
    border-color: #2563EB;
}

/* =====================================================
   RESPONSIVE OPTIMIZATIONS (Tablet & Mobile)
   ===================================================== */
@media (max-width: 991px) {
    .apna-top-search-banner {
        padding: 16px 0 12px;
        margin-bottom: 18px;
    }
}

@media (max-width: 767px) {
    .apna-top-search-banner {
        padding: 12px 0 10px;
        margin-bottom: 14px;
        border-bottom: 1px solid #E2E8F0;
    }
    .apna-search-box-card {
        padding: 4px 4px 4px 12px;
        border-radius: 12px;
        gap: 8px;
    }
    .apna-search-icon {
        font-size: 15px;
    }
    .apna-search-input {
        font-size: 13.5px !important;
        padding: 6px 0 !important;
    }
    .apna-search-submit-btn {
        padding: 9px 16px;
        font-size: 13px;
        border-radius: 8px;
        gap: 6px;
    }
    .apna-popular-tags-row {
        margin-top: 10px;
        gap: 6px;
    }
    .apna-popular-label {
        font-size: 11.5px;
    }
    .apna-tags-scroll-wrap {
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 4px;
        max-width: 100%;
    }
    .apna-tags-scroll-wrap::-webkit-scrollbar {
        display: none;
    }
    .apna-pop-tag {
        font-size: 11px;
        padding: 3px 10px;
        flex-shrink: 0;
    }
}
</style>
