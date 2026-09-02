@extends('admin.layouts.admin_layout')
@section('content')
<style>
.cms-form-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
    padding: 24px 28px 40px;
}
.cms-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #94A3B8;
    margin-bottom: 20px;
    font-weight: 500;
}
.cms-breadcrumb a { color: #64748B; text-decoration: none; }
.cms-breadcrumb a:hover { color: #3B82F6; }
.cms-breadcrumb i { font-size: 8px; color: #CBD5E1; }

.cms-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.cms-page-title {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.cms-page-title .title-icon {
    width: 38px;
    height: 38px;
    background: #2563EB;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 16px;
}

.cms-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.cms-nav-tabs {
    display: flex;
    border-bottom: 1px solid #E2E8F0;
    background: #F8FAFC;
    padding: 0 20px;
    margin: 0;
    list-style: none;
}
.cms-nav-tabs li a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 14px 18px;
    font-size: 13.5px;
    font-weight: 600;
    color: #64748B;
    border-bottom: 2px solid transparent;
    text-decoration: none !important;
    transition: all 0.15s;
}
.cms-nav-tabs li.active a,
.cms-nav-tabs li a:hover {
    color: #3B82F6;
    border-bottom-color: #3B82F6;
    background: transparent;
}
.cms-tab-body {
    padding: 24px;
}

/* Form Styles */
.cms-form-group {
    margin-bottom: 20px;
}
.cms-form-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}
.cms-input {
    width: 100%;
    height: 42px;
    border: 1px solid #E2E8F0;
    border-radius: 9px;
    padding: 0 14px;
    font-size: 13.5px;
    color: #1E293B;
    background: #FFFFFF;
    transition: all 0.15s;
    outline: none;
}
.cms-input:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
.cms-textarea {
    width: 100%;
    border: 1px solid #E2E8F0;
    border-radius: 9px;
    padding: 10px 14px;
    font-size: 13.5px;
    color: #1E293B;
    background: #FFFFFF;
    transition: all 0.15s;
    outline: none;
    resize: vertical;
    min-height: 80px;
}
.cms-textarea:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
.cms-radio-group {
    display: flex;
    gap: 16px;
    align-items: center;
    margin-top: 4px;
}
.cms-radio-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13.5px;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
}
.cms-form-actions {
    padding: 16px 24px;
    background: #F8FAFC;
    border-top: 1px solid #E2E8F0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
.btn-cms-save {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #2563EB;
    color: #fff !important;
    font-size: 14px;
    font-weight: 700;
    padding: 10px 24px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(99,102,241,0.3);
    transition: all 0.2s ease;
}
.btn-cms-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(99,102,241,0.4);
}
.btn-cms-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #F1F5F9;
    color: #475569 !important;
    border: 1px solid #CBD5E1;
    font-size: 13.5px;
    font-weight: 600;
    padding: 9px 18px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.15s;
}
.btn-cms-back:hover {
    background: #E2E8F0;
}
</style>

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="cms-form-wrapper">

            {{-- Breadcrumb --}}
            <div class="cms-breadcrumb">
                <a href="{{ route('admin.home') }}"><i class="fa fa-home"></i> Home</a>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('list.cms') }}">CMS Pages</a>
                <i class="fa fa-angle-right"></i>
                <span>Add CMS Page</span>
            </div>

            {{-- Header --}}
            <div class="cms-header-row">
                <div>
                    <h1 class="cms-page-title">
                        <span class="title-icon"><i class="fa fa-plus"></i></span>
                        Add New CMS Page
                    </h1>
                </div>
                <a href="{{ route('list.cms') }}" class="btn-cms-back">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>

            @include('flash::message')

            {{-- Main Form Card --}}
            <div class="cms-card">
                <ul class="cms-nav-tabs">
                    <li class="active"><a href="#Details" data-toggle="tab"><i class="fa fa-info-circle"></i> Page Details</a></li>
                    <li><a href="#seo" data-toggle="tab"><i class="fa fa-globe"></i> SEO Meta Tags</a></li>
                </ul>

                {!! Form::open(array('method' => 'post', 'route' => 'store.cms', 'class' => 'form', 'files'=>true)) !!}
                <div class="tab-content cms-tab-body">
                    <div class="tab-pane fade active in" id="Details">
                        @include('admin.cms.forms.form')
                    </div>
                    <div class="tab-pane fade" id="seo">
                        @include('admin.cms.forms.seo_form')
                    </div>
                </div>

                <div class="cms-form-actions">
                    <a href="{{ route('list.cms') }}" class="btn-cms-back">Cancel</a>
                    <button type="submit" class="btn-cms-save">
                        <i class="fa fa-check"></i> Save CMS Page
                    </button>
                </div>
                {!! Form::close() !!}
            </div>

        </div>

    </div>
</div>
@endsection