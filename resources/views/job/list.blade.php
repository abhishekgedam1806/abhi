@extends('layouts.app')

@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 

@php
// Dynamic Title matching screenshot e.g. "Work From Home Jobs - 453 Verified Vacancies"
$titlePrefix = 'All';
if (!empty(Request::get('search'))) {
    $titlePrefix = ucfirst(Request::get('search'));
} elseif (!empty(Request::get('job_type_id'))) {
    $firstJt = App\JobType::where('job_type_id', ((array)Request::get('job_type_id'))[0])->lang()->first();
    if ($firstJt) $titlePrefix = $firstJt->job_type;
} elseif (!empty(Request::get('functional_area_id'))) {
    $firstFa = App\FunctionalArea::where('functional_area_id', ((array)Request::get('functional_area_id'))[0])->lang()->first();
    if ($firstFa) $titlePrefix = $firstFa->functional_area;
} elseif (!empty(Request::get('city_id'))) {
    $firstCity = App\City::getCityById(((array)Request::get('city_id'))[0]);
    if ($firstCity) $titlePrefix = $firstCity->city;
}
@endphp

<style>
/* Modern Expert-Level Jobs Page Styles (Apna.co / Naukri Style) */
.apna-jobs-page-wrapper {
    background-color: #F8FAFC;
    padding: 30px 0 60px;
    min-height: 85vh;
}
.apna-page-title-row {
    margin-bottom: 22px;
}
.apna-main-heading {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.apna-verified-badge {
    color: #03855c;
    font-weight: 700;
}

/* Sidebar Styles */
.apna-filter-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 20px 18px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    position: sticky;
    top: 20px;
}
.apna-filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 14px;
    border-bottom: 1px solid #F1F5F9;
    margin-bottom: 12px;
}
.filter-header-left {
    font-size: 15px;
    font-weight: 700;
    color: #0F172A;
    display: flex;
    align-items: center;
    gap: 7px;
}
.filter-header-left i {
    color: #4F46E5;
    font-size: 14px;
}
.apna-clear-all {
    font-size: 13px;
    font-weight: 600;
    color: #03855c !important;
    text-decoration: none !important;
    cursor: pointer;
}
.apna-clear-all:hover {
    text-decoration: underline !important;
}

/* Active Chips */
.apna-active-tags-row {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px solid #F1F5F9;
}
.apna-active-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #EEF2FF;
    border: 1px solid #C7D2FE;
    color: #3730A3 !important;
    font-size: 12px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 50px;
    text-decoration: none !important;
    transition: all 0.15s ease;
}
.apna-active-chip:hover {
    background: #E0E7FF;
    color: #1E1B4B !important;
}
.apna-active-chip i {
    font-size: 10px;
}

/* Filter Groups & Accordions */
.apna-filter-group {
    padding: 14px 0;
    border-bottom: 1px solid #F1F5F9;
}
.apna-filter-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    font-weight: 700;
    color: #1E293B;
    cursor: pointer;
    user-select: none;
}
.apna-filter-title .group-count-badge {
    background: #03855c;
    color: #FFF;
    font-size: 10px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 50px;
    margin-left: 4px;
}
.apna-filter-title .toggle-ico {
    font-size: 11px;
    color: #94A3B8;
    transition: transform 0.2s ease;
}
.apna-filter-title.collapsed .toggle-ico {
    transform: rotate(180deg);
}

.apna-filter-options {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.scrollable-options {
    max-height: 210px;
    overflow-y: auto;
    padding-right: 4px;
}
.scrollable-options::-webkit-scrollbar {
    width: 4px;
}
.scrollable-options::-webkit-scrollbar-thumb {
    background: #CBD5E1;
    border-radius: 4px;
}

/* Checkbox & Radio Labels */
.apna-checkbox-label, .apna-radio-label {
    display: flex;
    align-items: center;
    font-size: 13px;
    font-weight: 500;
    color: #334155;
    cursor: pointer;
    margin: 0;
    user-select: none;
    position: relative;
    line-height: 1.4;
}
.apna-checkbox-label input, .apna-radio-label input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}
.custom-checkbox {
    width: 17px;
    height: 17px;
    border: 1.5px solid #CBD5E1;
    border-radius: 4px;
    margin-right: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #FFF;
    flex-shrink: 0;
    transition: all 0.15s ease;
}
.apna-checkbox-label input:checked ~ .custom-checkbox {
    background: #03855c;
    border-color: #03855c;
}
.apna-checkbox-label input:checked ~ .custom-checkbox::after {
    content: "✓";
    color: #FFF;
    font-size: 11px;
    font-weight: 900;
}
.custom-radio {
    width: 17px;
    height: 17px;
    border: 1.5px solid #CBD5E1;
    border-radius: 50%;
    margin-right: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #FFF;
    flex-shrink: 0;
    transition: all 0.15s ease;
}
.apna-radio-label input:checked ~ .custom-radio {
    border-color: #03855c;
}
.apna-radio-label input:checked ~ .custom-radio::after {
    content: "";
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #03855c;
}
.apna-checkbox-label .opt-text, .apna-radio-label .opt-text {
    flex-grow: 1;
}
.apna-checkbox-label .opt-count {
    font-size: 11px;
    color: #94A3B8;
    margin-left: 6px;
}

/* Salary Slider */
.salary-sublabel {
    font-size: 12px;
    color: #64748B;
    margin-top: 5px;
    margin-bottom: 8px;
}
.salary-range-slider {
    width: 100%;
    -webkit-appearance: none;
    height: 6px;
    border-radius: 4px;
    background: #E2E8F0;
    outline: none;
}
.salary-range-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #03855c;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(3, 133, 92, 0.4);
    border: 2px solid #FFFFFF;
}
.salary-range-labels {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    margin-top: 6px;
}

/* Search input inside filters */
.filter-search-box {
    position: relative;
    margin-top: 8px;
    margin-bottom: 8px;
}
.filter-search-box i {
    position: absolute;
    left: 10px;
    top: 9px;
    color: #94A3B8;
    font-size: 12px;
}
.filter-search-box input {
    width: 100%;
    height: 32px;
    padding-left: 28px;
    padding-right: 8px;
    font-size: 12px;
    border: 1px solid #E2E8F0;
    border-radius: 6px;
    outline: none;
}
.filter-search-box input:focus {
    border-color: #03855c;
}

/* ------------------------------------------------------------- */
/* CENTER JOB CARDS (Apna.co / WorkIndia / Naukri Exact Design) */
/* ------------------------------------------------------------- */
.apna-job-card {
    display: block;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 18px 22px;
    margin-bottom: 14px;
    text-decoration: none !important;
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    position: relative;
}
.apna-job-card:hover {
    border-color: #03855c;
    box-shadow: 0 8px 24px -4px rgba(3, 133, 92, 0.12);
    transform: translateY(-2px);
    background: #FFFFFF;
}

/* Top Section: Logo + Title + Arrow */
.apna-job-top-row {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 10px;
}
.apna-comp-logo-box {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    border: 1px solid #E2E8F0;
    background: #F8FAFC;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
}
.apna-comp-logo-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.apna-comp-logo-box .placeholder-icon {
    font-size: 20px;
    color: #94A3B8;
}
.apna-job-title-area {
    flex-grow: 1;
}
.apna-job-title {
    font-size: 16px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 2px 0;
    line-height: 1.35;
    transition: color 0.15s ease;
}
.apna-job-card:hover .apna-job-title {
    color: #03855c;
}
.apna-job-company {
    font-size: 13px;
    color: #64748B;
    font-weight: 500;
}
.apna-card-chevron {
    color: #03855c;
    font-size: 17px;
    margin-top: 4px;
    transition: transform 0.2s ease;
}
.apna-job-card:hover .apna-card-chevron {
    transform: translateX(4px);
}

/* Middle Section: Location + Salary */
.apna-job-meta-line {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 14px;
    margin-bottom: 12px;
    font-size: 13.5px;
}
.apna-meta-loc {
    color: #475569;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.apna-meta-loc i {
    color: #64748B;
    font-size: 13px;
}
.apna-meta-sal {
    font-weight: 700;
    color: #0F172A;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.apna-meta-sal i {
    color: #03855c;
}

/* Bottom Tags Row */
.apna-job-pills-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}
.apna-pill-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #F1F5F9;
    color: #475569;
    font-size: 12px;
    font-weight: 500;
    padding: 3px 9px;
    border-radius: 6px;
}
.apna-pill-tag i {
    font-size: 11px;
    color: #64748B;
}
.apna-pill-verified {
    background: #ECFDF5;
    color: #03855c;
    font-weight: 600;
}
.apna-pill-verified i {
    color: #03855c;
}
.apna-pill-featured {
    background: #FEF3C7;
    color: #D97706;
    font-weight: 600;
}
.apna-pill-featured i {
    color: #D97706;
}

/* ------------------------------------------------------------- */
/* RIGHT PROMO CARD (Direct HR Contact / Free Apply Card)        */
/* ------------------------------------------------------------- */
.apna-right-promo-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 22px 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    position: sticky;
    top: 20px;
}
.apna-promo-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #ECFDF5;
    color: #03855c;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 50px;
    margin-bottom: 12px;
}
.apna-promo-title {
    font-size: 17px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 12px;
    line-height: 1.35;
}
.apna-promo-list {
    list-style: none;
    padding: 0;
    margin: 0 0 18px 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.apna-promo-list li {
    font-size: 13px;
    color: #334155;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.4;
}
.apna-promo-list li i {
    color: #03855c;
    font-size: 14px;
    margin-top: 2px;
    flex-shrink: 0;
}
.apna-promo-btn {
    display: block;
    width: 100%;
    background: #03855c;
    color: #FFFFFF !important;
    text-align: center;
    font-size: 14px;
    font-weight: 700;
    padding: 10px 16px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(3, 133, 92, 0.25);
}
.apna-promo-btn:hover {
    background: #047857;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(3, 133, 92, 0.35);
}

/* =====================================================
   MOBILE ONLY — Compact Filter Bar + Bottom Sheet
   ===================================================== */
@media (max-width: 767px) {

    /* Hide desktop sidebar on mobile */
    .apna-filter-sidebar-col {
        display: none !important;
    }

    /* Full width job cards column on mobile */
    .apna-jobs-page-wrapper .col-md-8.col-lg-6 {
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    /* Reduce top padding on mobile */
    .apna-jobs-page-wrapper {
        padding: 10px 0 80px !important;
    }

    /* Compact page title */
    .apna-page-title-row {
        margin-bottom: 8px !important;
    }
    .apna-main-heading {
        font-size: 15px !important;
        gap: 4px !important;
    }

    /* Compact job card on mobile */
    .apna-job-card {
        padding: 14px 14px !important;
        border-radius: 12px !important;
        margin-bottom: 10px !important;
    }
    .apna-job-title {
        font-size: 14px !important;
    }
    .apna-job-company {
        font-size: 12px !important;
    }
    .apna-job-meta-line {
        gap: 8px !important;
        font-size: 12.5px !important;
        margin-bottom: 8px !important;
    }
    .apna-comp-logo-box {
        width: 36px !important;
        height: 36px !important;
    }

    /* Search bar compact */
    .inner-top-search-wrapper {
        padding: 10px 0 !important;
    }
}

/* =====================================================
   MOBILE FILTER BAR — Horizontal Scrollable Chips
   ===================================================== */
.mob-filter-bar {
    display: none;
}
@media (max-width: 767px) {
    .mob-filter-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding: 8px 10px 6px;
        background: #fff;
        border-bottom: 1px solid #E2E8F0;
        margin-bottom: 10px;
        position: sticky;
        top: 0;
        z-index: 100;
    }
    .mob-filter-bar::-webkit-scrollbar { display: none; }

    .mob-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        padding: 7px 13px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid #E2E8F0;
        background: #fff;
        color: #334155;
        flex-shrink: 0;
        transition: all 0.15s ease;
        -webkit-tap-highlight-color: transparent;
    }
    .mob-chip:active {
        transform: scale(0.96);
    }
    .mob-chip.mob-chip-primary {
        background: #03855c;
        color: #fff;
        border-color: #03855c;
    }
    .mob-chip.mob-chip-active {
        background: #EEF2FF;
        color: #3730A3;
        border-color: #C7D2FE;
    }
    .mob-chip i {
        font-size: 12px;
    }
    .mob-filter-count-badge {
        background: #DC2626;
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 2px;
    }
}

/* Job count bar */
.mob-job-count-bar {
    display: none;
}
@media (max-width: 767px) {
    .mob-job-count-bar {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        padding: 0 10px 8px;
    }
    .mob-job-count-bar span {
        color: #03855c;
    }
}

/* =====================================================
   MOBILE BOTTOM SHEET DRAWER
   ===================================================== */
.mob-drawer-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 1050;
    opacity: 0;
    transition: opacity 0.25s ease;
}
.mob-drawer-overlay.open {
    display: block;
    opacity: 1;
}
.mob-drawer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 20px 20px 0 0;
    z-index: 1051;
    max-height: 88vh;
    display: flex;
    flex-direction: column;
    transform: translateY(100%);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 -4px 30px rgba(0,0,0,0.15);
}
.mob-drawer.open {
    transform: translateY(0);
}
.mob-drawer-handle {
    width: 40px;
    height: 4px;
    background: #CBD5E1;
    border-radius: 4px;
    margin: 12px auto 0;
    flex-shrink: 0;
}
.mob-drawer-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px 12px;
    border-bottom: 1px solid #F1F5F9;
    flex-shrink: 0;
}
.mob-drawer-title {
    font-size: 16px;
    font-weight: 800;
    color: #0F172A;
    display: flex;
    align-items: center;
    gap: 8px;
}
.mob-drawer-close {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #F1F5F9;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #475569;
    font-size: 15px;
}
.mob-drawer-body {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    flex: 1;
    padding: 0 18px 20px;
}
.mob-drawer-footer {
    padding: 12px 18px;
    border-top: 1px solid #F1F5F9;
    display: flex;
    gap: 10px;
    flex-shrink: 0;
    background: #fff;
}
.mob-drawer-footer .btn-apply {
    flex: 1;
    background: #03855c;
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    padding: 12px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}
.mob-drawer-footer .btn-clear {
    padding: 12px 20px;
    background: #F1F5F9;
    color: #475569;
    font-weight: 600;
    font-size: 13px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
/* Sort drawer specific */
.mob-sort-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 0;
    border-bottom: 1px solid #F8FAFC;
    cursor: pointer;
}
.mob-sort-option:last-child { border-bottom: none; }
.mob-sort-dot {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid #CBD5E1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: border-color 0.15s;
}
.mob-sort-option.selected .mob-sort-dot {
    border-color: #03855c;
}
.mob-sort-option.selected .mob-sort-dot::after {
    content: "";
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #03855c;
    display: block;
}

/* =====================================================
   DESKTOP — unchanged (>768px hides mobile elements)
   ===================================================== */
@media (min-width: 768px) {
    .mob-filter-bar,
    .mob-job-count-bar,
    .mob-drawer,
    .mob-drawer-overlay { display: none !important; }
}

/* Pagination Styling */
.apna-pagination-wrap {
    margin-top: 25px;
    padding: 16px 20px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.apna-pagi-info {
    font-size: 13.5px;
    color: #64748B;
    font-weight: 500;
}
.apna-pagi-links .pagination {
    margin: 0;
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 8px;
    gap: 4px;
}
.apna-pagi-links .page-item .page-link {
    position: relative;
    display: block;
    padding: 6px 12px;
    color: #334155;
    background-color: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
}
.apna-pagi-links .page-item.active .page-link {
    z-index: 3;
    color: #FFFFFF;
    background-color: #03855c;
    border-color: #03855c;
    box-shadow: 0 2px 6px rgba(3, 133, 92, 0.3);
}
.apna-pagi-links .page-item.disabled .page-link {
    color: #94A3B8;
    pointer-events: none;
    background-color: #F8FAFC;
    border-color: #E2E8F0;
}
.apna-pagi-links .page-item:not(.active):not(.disabled) .page-link:hover {
    background-color: #F1F5F9;
    color: #0F172A;
    border-color: #94A3B8;
}
</style>

{{-- Main Search Bar Relocated to Main Job Section --}}
@include('includes.inner_top_search')

<div class="apna-jobs-page-wrapper">
    <div class="container">

        {{-- Top Title Row matching screenshot --}}
        <div class="row apna-page-title-row">
            <div class="col-12">
                @if(Request::filled('seo_city_name') || (Request::filled('city_id') && count((array)Request::get('city_id')) == 1))
                    @php
                        $activeCityName = Request::get('seo_city_name');
                        if (!$activeCityName && !empty(Request::get('city_id'))) {
                            $cObj = App\City::getCityById(((array)Request::get('city_id'))[0]);
                            if ($cObj) $activeCityName = $cObj->city;
                        }
                    @endphp
                    @if($activeCityName)
                        <div class="city-hub-banner" style="background: #0F172A; border-radius: 16px; padding: 22px 28px; margin-bottom: 24px; color: #FFF; box-shadow: 0 4px 20px rgba(15,23,42,0.12);">
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                                <div>
                                    <div style="font-size: 12px; font-weight: 700; color: #34D399; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">
                                        <i class="fa fa-map-marker"></i> City Job Hub
                                    </div>
                                    <h1 style="font-size: 24px; font-weight: 800; color: #FFFFFF; margin: 0 0 6px 0;">
                                        Jobs in {{ $activeCityName }} <span style="font-size: 15px; font-weight: 600; color: #94A3B8; margin-left: 8px;">({{ $jobs->total() }} Open Vacancies)</span>
                                    </h1>
                                    <p style="font-size: 13.5px; color: #CBD5E1; margin: 0;">
                                        Apply to verified full-time, part-time & fresher job openings across {{ $activeCityName }} with direct employer contact.
                                    </p>
                                </div>
                                <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                                    @php
                                        $currCat = Request::segment(2) ?? '';
                                        $citySlug = \Illuminate\Support\Str::slug($activeCityName);
                                    @endphp
                                    <a href="{{ url('jobs-in-' . $citySlug . '/it-software') }}" style="{{ strpos($currCat, 'it') !== false || strpos($currCat, 'software') !== false ? 'background: #2563EB; border-color: #3B82F6;' : 'background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);' }} border: 1px solid; color: #FFF; padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s ease;">IT & Software</a>
                                    <a href="{{ url('jobs-in-' . $citySlug . '/accounts') }}" style="{{ strpos($currCat, 'account') !== false ? 'background: #2563EB; border-color: #3B82F6;' : 'background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);' }} border: 1px solid; color: #FFF; padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s ease;">Accounts</a>
                                    <a href="{{ url('jobs-in-' . $citySlug . '/sales') }}" style="{{ strpos($currCat, 'sale') !== false ? 'background: #2563EB; border-color: #3B82F6;' : 'background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);' }} border: 1px solid; color: #FFF; padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s ease;">Sales & Marketing</a>
                                    <a href="{{ url('jobs-in-' . $citySlug . '/customer-support') }}" style="{{ strpos($currCat, 'customer') !== false || strpos($currCat, 'support') !== false ? 'background: #2563EB; border-color: #3B82F6;' : 'background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);' }} border: 1px solid; color: #FFF; padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s ease;">Customer Support</a>
                                    <a href="{{ url('jobs-in-' . $citySlug) }}?is_freelance=1" style="{{ Request::get('is_freelance') == 1 ? 'background: #10B981; border-color: #10B981; color: #FFF;' : 'background: rgba(3,133,92,0.25); border-color: #10B981; color: #34D399;' }} border: 1px solid; padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s ease;">Remote / WFH</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <h1 class="apna-main-heading">
                        {{ $titlePrefix }} Jobs - <span class="apna-verified-badge">{{ $jobs->total() }} Verified Vacancies</span>
                    </h1>
                @endif
            </div>
        </div>

        {{-- ===============================================
             MOBILE COMPACT FILTER BAR (mobile only)
             =============================================== --}}
        @php
        $mActiveCount = 0;
        if (!empty(Request::get('search'))) $mActiveCount++;
        if (!empty(Request::get('job_type_id'))) $mActiveCount += count((array)Request::get('job_type_id'));
        if (!empty(Request::get('functional_area_id'))) $mActiveCount += count((array)Request::get('functional_area_id'));
        if (!empty(Request::get('city_id'))) $mActiveCount += count((array)Request::get('city_id'));
        if (!empty(Request::get('job_experience_id'))) $mActiveCount += count((array)Request::get('job_experience_id'));
        if (!empty(Request::get('job_shift_id'))) $mActiveCount += count((array)Request::get('job_shift_id'));
        if (!empty(Request::get('salary_from')) && Request::get('salary_from') > 0) $mActiveCount++;
        if (!empty(Request::get('date_posted'))) $mActiveCount++;
        $mSortLabel = 'Sort by';
        if (Request::get('order_by') == 'salary') $mSortLabel = 'High Salary';
        elseif (Request::get('order_by') == 'new') $mSortLabel = 'Newest';
        @endphp

        <div class="mob-filter-bar" id="mobFilterBar">
            {{-- Filters Button --}}
            <button type="button" class="mob-chip mob-chip-primary" onclick="openMobDrawer('filterDrawer')">
                <i class="fa fa-sliders"></i>
                Filters
                @if($mActiveCount > 0)
                    <span class="mob-filter-count-badge">{{ $mActiveCount }}</span>
                @endif
            </button>

            {{-- Sort by --}}
            <button type="button" class="mob-chip {{ Request::get('order_by') ? 'mob-chip-active' : '' }}" onclick="openMobDrawer('sortDrawer')">
                <i class="fa fa-sort-amount-asc"></i>
                {{ $mSortLabel }}
            </button>

            {{-- Job Type quick chips --}}
            @if(isset($jobTypeIdsArray) && count($jobTypeIdsArray))
                @foreach($jobTypeIdsArray as $jt_id)
                    @php
                        $qtJobType = App\JobType::where('job_type_id', $jt_id)->lang()->active()->first();
                        $qtChecked = $qtJobType && in_array($jt_id, (array)Request::get('job_type_id', []));
                    @endphp
                    @if($qtJobType)
                        <button type="button"
                            class="mob-chip {{ $qtChecked ? 'mob-chip-active' : '' }}"
                            onclick="toggleMobFilter('job_type_id[]', '{{ $jt_id }}')"
                            data-filter-name="job_type_id[]"
                            data-filter-value="{{ $jt_id }}">
                            {{ $qtJobType->job_type }}
                        </button>
                    @endif
                @endforeach
            @endif

            {{-- Clear all if filters active --}}
            @if($mActiveCount > 0)
                <a href="{{ route('job.list') }}" class="mob-chip" style="color: #DC2626; border-color: #FCA5A5;">
                    <i class="fa fa-times"></i> Clear
                </a>
            @endif
        </div>

        {{-- Mobile Job Count Bar --}}
        <div class="mob-job-count-bar">
            <span>{{ $jobs->total() }}</span> Jobs Available
        </div>

        {{-- ===============================================
             FILTER BOTTOM SHEET DRAWER
             =============================================== --}}
        <div class="mob-drawer-overlay" id="filterOverlay" onclick="closeMobDrawer('filterDrawer', 'filterOverlay')"></div>
        <div class="mob-drawer" id="filterDrawer">
            <div class="mob-drawer-handle"></div>
            <div class="mob-drawer-header">
                <div class="mob-drawer-title">
                    <i class="fa fa-sliders" style="color:#03855c;"></i>
                    Filters
                    @if($mActiveCount > 0)
                        <span style="background:#03855c;color:#fff;font-size:11px;font-weight:800;padding:2px 8px;border-radius:50px;">{{ $mActiveCount }} Active</span>
                    @endif
                </div>
                <button class="mob-drawer-close" onclick="closeMobDrawer('filterDrawer', 'filterOverlay')">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <form action="{{ route('job.list') }}" method="get" id="mob-filter-form">
                <input type="hidden" name="search" value="{{ Request::get('search', '') }}">
                <input type="hidden" name="order_by" value="{{ Request::get('order_by', '') }}">
                <div class="mob-drawer-body">
                    {{-- Reuse full sidebar filter content inside drawer --}}
                    @include('includes.job_list_side_bar_drawer')
                </div>
                <div class="mob-drawer-footer">
                    <a href="{{ route('job.list') }}" class="btn-clear"><i class="fa fa-trash-o" style="margin-right: 4px;"></i> Clear all</a>
                    <button type="submit" class="btn-apply" id="drawerApplyBtn">
                        <i class="fa fa-check"></i> Apply Filters @if($mActiveCount > 0)({{ $mActiveCount }})@endif
                    </button>
                </div>
            </form>
        </div>

        {{-- SORT BOTTOM SHEET DRAWER --}}
        <div class="mob-drawer-overlay" id="sortOverlay" onclick="closeMobDrawer('sortDrawer', 'sortOverlay')"></div>
        <div class="mob-drawer" id="sortDrawer">
            <div class="mob-drawer-handle"></div>
            <div class="mob-drawer-header">
                <div class="mob-drawer-title"><i class="fa fa-sort-amount-asc" style="color:#03855c;"></i> Sort By</div>
                <button class="mob-drawer-close" onclick="closeMobDrawer('sortDrawer', 'sortOverlay')"><i class="fa fa-times"></i></button>
            </div>
            <div class="mob-drawer-body">
                <form action="{{ route('job.list') }}" method="get" id="mob-sort-form">
                    <input type="hidden" name="search" value="{{ Request::get('search', '') }}">
                    {{-- preserve all other filters --}}
                    @foreach(request()->except(['order_by']) as $qk => $qv)
                        @if(is_array($qv))
                            @foreach($qv as $qvi)
                                <input type="hidden" name="{{ $qk }}[]" value="{{ $qvi }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $qk }}" value="{{ $qv }}">
                        @endif
                    @endforeach
                    @foreach([
                        ['value' => 'id',     'label' => 'Most Relevant',        'icon' => 'fa-star'],
                        ['value' => 'new',    'label' => 'Date Posted - Newest', 'icon' => 'fa-calendar'],
                        ['value' => 'salary', 'label' => 'Salary - High to Low', 'icon' => 'fa-inr'],
                    ] as $sortOpt)
                        @php $selClass = (Request::get('order_by', 'id') == $sortOpt['value']) ? 'selected' : ''; @endphp
                        <label class="mob-sort-option {{ $selClass }}" style="cursor:pointer;">
                            <input type="radio" name="order_by" value="{{ $sortOpt['value'] }}" style="display:none;" {{ $selClass ? 'checked' : '' }} onchange="this.form.submit()">
                            <span class="mob-sort-dot"></span>
                            <span style="font-size:14px;font-weight:600;color:#1E293B;flex:1;"><i class="fa {{ $sortOpt['icon'] }}" style="width:18px;color:#64748B;"></i> {{ $sortOpt['label'] }}</span>
                        </label>
                    @endforeach
                </form>
            </div>
        </div>
        {{-- END MOBILE UI --}}

        <form action="{{ route('job.list') }}" method="get" id="search-job-list">
            <div class="row">
                {{-- LEFT COLUMN: Modern Filters Sidebar (desktop only) --}}
                @include('includes.job_list_side_bar')

                {{-- CENTER COLUMN: Modern Job Cards List --}}
                <div class="col-lg-6 col-md-8 col-12">
                    {{-- 🏷️ INTERACTIVE ACTIVE FILTER CHIPS BAR (1-Tap Deselect) --}}
                    @if($mActiveCount > 0)
                    <div class="apna-active-chips-container" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 10px 14px; margin-bottom: 14px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
                        <span style="font-size: 12px; font-weight: 700; color: #475569; display: inline-flex; align-items: center; gap: 4px; margin-right: 4px;">
                            <i class="fa fa-filter text-primary"></i> Active:
                        </span>

                        @if(!empty(Request::get('search')))
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="apna-active-chip" title="Remove search filter">
                                <span>"{{ Request::get('search') }}"</span> <i class="fa fa-times"></i>
                            </a>
                        @endif

                        @if(!empty(Request::get('city_id')))
                            @foreach((array)Request::get('city_id') as $cId)
                                @php $cObj = App\City::getCityById($cId); @endphp
                                @if($cObj)
                                    <a href="{{ request()->fullUrlWithQuery(['city_id' => array_values(array_diff((array)Request::get('city_id'), [$cId]))]) }}" class="apna-active-chip" title="Remove {{ $cObj->city }}">
                                        <span><i class="fa fa-map-marker" style="font-size: 10px;"></i> {{ $cObj->city }}</span> <i class="fa fa-times"></i>
                                    </a>
                                @endif
                            @endforeach
                        @endif

                        @if(!empty(Request::get('area_id')))
                            @foreach((array)Request::get('area_id') as $aId)
                                @php $aObj = App\Area::find($aId); @endphp
                                @if($aObj)
                                    <a href="{{ request()->fullUrlWithQuery(['area_id' => array_values(array_diff((array)Request::get('area_id'), [$aId]))]) }}" class="apna-active-chip" title="Remove {{ $aObj->area_name }}">
                                        <span><i class="fa fa-crosshairs" style="font-size: 10px;"></i> {{ $aObj->area_name }}</span> <i class="fa fa-times"></i>
                                    </a>
                                @endif
                            @endforeach
                        @endif

                        @if(!empty(Request::get('job_type_id')))
                            @foreach((array)Request::get('job_type_id') as $jtId)
                                @php $jtObj = App\JobType::where('job_type_id', $jtId)->lang()->first(); @endphp
                                @if($jtObj)
                                    <a href="{{ request()->fullUrlWithQuery(['job_type_id' => array_values(array_diff((array)Request::get('job_type_id'), [$jtId]))]) }}" class="apna-active-chip" title="Remove {{ $jtObj->job_type }}">
                                        <span>{{ $jtObj->job_type }}</span> <i class="fa fa-times"></i>
                                    </a>
                                @endif
                            @endforeach
                        @endif

                        @if(!empty(Request::get('functional_area_id')))
                            @foreach((array)Request::get('functional_area_id') as $faId)
                                @php $faObj = App\FunctionalArea::where('functional_area_id', $faId)->lang()->first(); @endphp
                                @if($faObj)
                                    <a href="{{ request()->fullUrlWithQuery(['functional_area_id' => array_values(array_diff((array)Request::get('functional_area_id'), [$faId]))]) }}" class="apna-active-chip" title="Remove {{ $faObj->functional_area }}">
                                        <span>{{ $faObj->functional_area }}</span> <i class="fa fa-times"></i>
                                    </a>
                                @endif
                            @endforeach
                        @endif

                        @if(!empty(Request::get('job_experience_id')))
                            @foreach((array)Request::get('job_experience_id') as $expId)
                                @php $expObj = App\JobExperience::where('job_experience_id', $expId)->lang()->first(); @endphp
                                @if($expObj)
                                    <a href="{{ request()->fullUrlWithQuery(['job_experience_id' => array_values(array_diff((array)Request::get('job_experience_id'), [$expId]))]) }}" class="apna-active-chip" title="Remove {{ $expObj->job_experience }}">
                                        <span>Exp: {{ $expObj->job_experience }}</span> <i class="fa fa-times"></i>
                                    </a>
                                @endif
                            @endforeach
                        @endif

                        @if(!empty(Request::get('salary_from')) && Request::get('salary_from') > 0)
                            <a href="{{ request()->fullUrlWithQuery(['salary_from' => null]) }}" class="apna-active-chip" title="Remove salary filter">
                                <span>₹ {{ number_format(Request::get('salary_from')) }}+</span> <i class="fa fa-times"></i>
                            </a>
                        @endif

                        @if(!empty(Request::get('date_posted')))
                            <a href="{{ request()->fullUrlWithQuery(['date_posted' => null]) }}" class="apna-active-chip" title="Remove date filter">
                                <span>{{ Request::get('date_posted') == '1' ? 'Last 24h' : 'Last ' . Request::get('date_posted') . ' days' }}</span> <i class="fa fa-times"></i>
                            </a>
                        @endif

                        @if(Request::get('is_freelance') == '1')
                            <a href="{{ request()->fullUrlWithQuery(['is_freelance' => null]) }}" class="apna-active-chip" title="Remove remote filter">
                                <span>Remote / WFH</span> <i class="fa fa-times"></i>
                            </a>
                        @endif

                        <a href="{{ route('job.list') }}" style="font-size: 11.5px; font-weight: 700; color: #DC2626; text-decoration: none; margin-left: auto; padding: 3px 8px; background: #FEE2E2; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fa fa-trash-o"></i> Clear all
                        </a>
                    </div>
                    @endif

                    @if(isset($jobs) && count($jobs))
                        @foreach($jobs as $job)
                            @php
                                $company = $job->getCompany();
                                $locationText = $job->getCity('city') ? $job->getCity('city') : 'Work From Home';
                                if ($job->getState('state') && $job->getCity('city')) {
                                    $locationText .= ', ' . $job->getState('state');
                                }
                            @endphp
                            @if(isset($company))
                                <a href="{{ route('job.detail', [$job->slug]) }}" class="apna-job-card">
                                    {{-- Top Row --}}
                                    <div class="apna-job-top-row">
                                        <div class="apna-comp-logo-box">
                                            @if(!empty($company->logo))
                                                <img src="{{ asset('company_logos/'.$company->logo) }}" alt="{{ $company->name }}" width="48" height="48" loading="lazy" decoding="async">
                                            @else
                                                <span class="placeholder-icon"><i class="fa fa-building-o"></i></span>
                                            @endif
                                        </div>
                                        <div class="apna-job-title-area">
                                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                                <h2 class="apna-job-title">{{ $job->title }}</h2>
                                                @if(!empty($job->relevance_score) && (int)$job->relevance_score >= 60)
                                                    <span style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #03855c; font-size: 11.5px; font-weight: 800; padding: 2px 8px; border-radius: 12px; white-space: nowrap; flex-shrink: 0;">
                                                        {{ $job->relevance_score }}% Match
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="apna-job-company">{{ $company->name }}</div>
                                        </div>
                                        <div class="apna-card-chevron">
                                            <i class="fa fa-angle-right"></i>
                                        </div>
                                    </div>

                                    {{-- Meta Row: Location & Salary --}}
                                    <div class="apna-job-meta-line">
                                        <span class="apna-meta-loc">
                                            <i class="fa fa-map-marker text-danger"></i> {{ $locationText }}
                                        </span>
                                        <span class="apna-meta-sal">
                                            <i class="fa fa-inr"></i>
                                            @if(!$job->hide_salary && ($job->salary_from > 0 || $job->salary_to > 0))
                                                ₹{{ number_format($job->salary_from) }} - ₹{{ number_format($job->salary_to) }}
                                            @else
                                                ₹25,000 - ₹50,000 / month
                                            @endif
                                        </span>
                                    </div>

                                    {{-- Why This Job Insights (Smart Engine) --}}
                                    @if(!empty($job->match_reasons) && count($job->match_reasons))
                                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 6px 10px; margin-bottom: 10px; font-size: 12px; color: #334155; display: flex; flex-wrap: wrap; gap: 8px;">
                                            @foreach(array_slice($job->match_reasons, 0, 2) as $reason)
                                                <span style="display: inline-flex; align-items: center; gap: 4px;">
                                                    <i class="fa fa-check text-success" style="font-size: 11px;"></i> {{ $reason }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Bottom Tags Row matching screenshot --}}
                                    <div class="apna-job-pills-row">
                                        <span class="apna-pill-tag">
                                            <i class="fa fa-clock-o"></i> {{ $job->getJobType('job_type') ?: 'Full Time' }}
                                        </span>
                                        <span class="apna-pill-tag">
                                            <i class="fa fa-briefcase"></i> 
                                            @if($job->getJobExperience('job_experience'))
                                                {{ $job->getJobExperience('job_experience') }}
                                            @else
                                                Min. 1 year
                                            @endif
                                        </span>
                                        @if($job->getJobShift('job_shift'))
                                        <span class="apna-pill-tag">
                                            <i class="fa fa-sun-o"></i> {{ $job->getJobShift('job_shift') }}
                                        </span>
                                        @endif
                                        @if($job->is_freelance)
                                        <span class="apna-pill-tag" style="background: #EFF6FF; color: #1D4ED8;">
                                            <i class="fa fa-home"></i> Work From Home
                                        </span>
                                        @endif
                                        @if($job->is_featured)
                                        <span class="apna-pill-tag apna-pill-featured">
                                            <i class="fa fa-bolt"></i> Urgent Hiring
                                        </span>
                                        @endif
                                        <span class="apna-pill-tag apna-pill-verified">
                                            <i class="fa fa-check-circle"></i> Verified
                                        </span>
                                    </div>
                                </a>
                            @endif
                        @endforeach

                        {{-- Pagination Bar --}}
                        <div class="apna-pagination-wrap">
                            <div class="apna-pagi-info">
                                Showing {{ $jobs->firstItem() }} - {{ $jobs->lastItem() }} of {{ $jobs->total() }} Jobs
                            </div>
                            <div class="apna-pagi-links">
                                {{ $jobs->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>

                    @else
                        {{-- No Relevant Jobs Found State (Smart Engine Safe Fallback) --}}
                        <div class="apna-job-card text-center" style="padding: 48px 24px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: #EFF6FF; color: #2563EB; font-size: 24px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                                <i class="fa fa-search"></i>
                            </div>
                            <h3 style="font-size: 18px; font-weight: 800; color: #0F172A; margin-bottom: 6px;">
                                {{__('No Highly Relevant Jobs Found')}}
                            </h3>
                            <p style="font-size: 13.5px; color: #64748B; max-width: 440px; margin: 0 auto 16px;">
                                @if(Request::get('search'))
                                    {{__('We could not find active vacancies directly matching')}} "<strong>{{ Request::get('search') }}</strong>".
                                @else
                                    {{__('No jobs match your selected filters.')}}
                                @endif
                            </p>

                            <!-- Suggested Related Searches -->
                            <div style="margin-bottom: 20px;">
                                <div style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 8px;">
                                    {{__('Popular Related Searches')}}:
                                </div>
                                <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                    <a href="{{ route('job.list', ['search' => 'SEO']) }}" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 16px; text-decoration: none;">
                                        SEO Specialist
                                    </a>
                                    <a href="{{ route('job.list', ['search' => 'Website']) }}" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 16px; text-decoration: none;">
                                        Website Designer
                                    </a>
                                    <a href="{{ route('job.list', ['search' => 'Digital Marketing']) }}" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 16px; text-decoration: none;">
                                        Digital Marketing
                                    </a>
                                    <a href="{{ route('job.list', ['search' => 'PHP']) }}" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 16px; text-decoration: none;">
                                        PHP Developer
                                    </a>
                                </div>
                            </div>

                            <a href="{{ route('job.list') }}" class="btn btn-sm btn-success" style="background:#03855c; border-color:#03855c; font-weight:700; padding:8px 20px; border-radius:8px; text-decoration: none;">
                                <i class="fa fa-refresh"></i> {{__('Reset All Filters & View All Jobs')}}
                            </a>
                        </div>
                    @endif
                </div>

                {{-- RIGHT COLUMN: Direct HR & Verified Vacancies Info Card --}}
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="apna-right-promo-card">
                        <div class="apna-promo-badge">
                            <i class="fa fa-shield"></i> 100% Verified
                        </div>
                        <h3 class="apna-promo-title">
                            Get Direct HR Contact & Fast-Track Interviews
                        </h3>
                        <ul class="apna-promo-list">
                            <li>
                                <i class="fa fa-check-circle"></i>
                                <span><strong>Direct HR WhatsApp:</strong> Connect directly without middlemen</span>
                            </li>
                            <li>
                                <i class="fa fa-check-circle"></i>
                                <span><strong>100% Free Apply:</strong> No registration or application fees</span>
                            </li>
                            <li>
                                <i class="fa fa-check-circle"></i>
                                <span><strong>Daily Job Alerts:</strong> Get instant notifications for matched roles</span>
                            </li>
                            <li>
                                <i class="fa fa-check-circle"></i>
                                <span><strong>Verified Companies:</strong> TCS, Kotak, Infosys, Zomato & more</span>
                            </li>
                        </ul>
                        @if(Auth::check())
                            <a href="{{ route('home') }}" class="apna-promo-btn">
                                <i class="fa fa-user"></i> View Profile & CV
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="apna-promo-btn">
                                <i class="fa fa-bolt"></i> Create Free Profile
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

@include('includes.footer')

@endsection

@push('scripts')
<script>
function updateSalaryLabel(val) {
    var minSpan = document.getElementById('salaryMinVal');
    if (minSpan) {
        if (val == 0) {
            minSpan.innerText = '₹ 0';
        } else {
            minSpan.innerText = '₹ ' + Number(val).toLocaleString('en-IN');
        }
    }
}

function updateSalaryLabelDrawer(val) {
    var minSpan = document.getElementById('salaryMinValDrawer');
    if (minSpan) {
        if (val == 0) {
            minSpan.innerText = '₹ 0';
        } else {
            minSpan.innerText = '₹ ' + Number(val).toLocaleString('en-IN');
        }
    }
}

function filterCheckboxList(input, containerId) {
    var filter = input.value.toLowerCase();
    var container = document.getElementById(containerId);
    if (!container) return;
    var items = container.getElementsByClassName('filter-item-row');
    for (var i = 0; i < items.length; i++) {
        var text = items[i].innerText.toLowerCase();
        if (text.indexOf(filter) > -1) {
            items[i].style.display = "flex";
        } else {
            items[i].style.display = "none";
        }
    }
}

$(document).ready(function () {
    // Accordion toggle icon update
    $('.apna-filter-group .collapse').on('show.bs.collapse', function () {
        $(this).prev('.apna-filter-title').find('.toggle-ico').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    }).on('hide.bs.collapse', function () {
        $(this).prev('.apna-filter-title').find('.toggle-ico').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    });
});

// =============================================
// MOBILE DRAWER FUNCTIONS
// =============================================
function openMobDrawer(drawerId) {
    var drawer = document.getElementById(drawerId);
    var overlayId = drawerId === 'filterDrawer' ? 'filterOverlay' : 'sortOverlay';
    var overlay = document.getElementById(overlayId);
    if (!drawer) return;
    document.body.style.overflow = 'hidden';
    overlay.style.display = 'block';
    setTimeout(function() {
        overlay.classList.add('open');
        drawer.classList.add('open');
    }, 10);
}

function closeMobDrawer(drawerId, overlayId) {
    var drawer = document.getElementById(drawerId);
    var overlay = document.getElementById(overlayId);
    if (!drawer) return;
    drawer.classList.remove('open');
    overlay.classList.remove('open');
    setTimeout(function() {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }, 300);
}

// Quick chip toggle filter (for horizontal job type chips)
function toggleMobFilter(name, value) {
    var form = document.createElement('form');
    form.method = 'get';
    form.action = '{{ route("job.list") }}';

    // Preserve existing query params except the toggled one
    var params = new URLSearchParams(window.location.search);
    var existing = params.getAll(name);
    var idx = existing.indexOf(value);
    // Remove old values for this key
    params.delete(name);
    if (idx === -1) {
        existing.push(value);
    } else {
        existing.splice(idx, 1);
    }
    existing.forEach(function(v) { params.append(name, v); });

    params.forEach(function(v, k) {
        var inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = k;
        inp.value = v;
        form.appendChild(inp);
    });

    document.body.appendChild(form);
    form.submit();
}

// Salary label update in drawer
function updateSalaryLabelDrawer(val) {
    var span = document.getElementById('salaryMinValDrawer');
    if (span) span.innerText = val == 0 ? '₹ 0' : '₹ ' + Number(val).toLocaleString('en-IN');
}
</script>
@endpush