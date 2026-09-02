@extends('admin.layouts.admin_layout')
@section('content')
<style>
.seo-form-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
    padding: 0 0 30px 0;
}
.seo-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #94A3B8;
    margin-bottom: 20px;
    font-weight: 500;
}
.seo-breadcrumb a { color: #64748B; text-decoration: none; }
.seo-breadcrumb a:hover { color: #0284C7; }
.seo-breadcrumb i { font-size: 8px; color: #CBD5E1; }

.seo-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.seo-page-title {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.seo-page-title .title-icon {
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

.seo-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.seo-card-header {
    padding: 16px 24px;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.seo-tab-body {
    padding: 24px;
}

.seo-form-actions {
    padding: 16px 24px;
    background: #F8FAFC;
    border-top: 1px solid #E2E8F0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
.btn-seo-save {
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
    box-shadow: 0 4px 14px rgba(37,99,235,0.3);
    transition: all 0.2s ease;
}
.btn-seo-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(37,99,235,0.4);
}
.btn-seo-back {
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
.btn-seo-back:hover {
    background: #E2E8F0;
}
</style>

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="seo-form-wrapper">

            {{-- Breadcrumb --}}
            <div class="seo-breadcrumb">
                <a href="{{ route('admin.home') }}"><i class="fa fa-home"></i> Home</a>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('list.seo') }}">SEO Management</a>
                <i class="fa fa-angle-right"></i>
                <span>Edit SEO: <strong>{{ strtoupper(str_replace('_', ' ', $seo->page_title)) }}</strong></span>
            </div>

            {{-- Header --}}
            <div class="seo-header-row">
                <div>
                    <h1 class="seo-page-title">
                        <span class="title-icon"><i class="fa fa-pencil"></i></span>
                        Edit SEO Configuration
                    </h1>
                </div>
                <a href="{{ route('list.seo') }}" class="btn-seo-back">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>

            @include('flash::message')

            {{-- Main Form Card --}}
            <div class="seo-card">
                <div class="seo-card-header">
                    <span style="font-size: 14px; font-weight: 700; color: #0F172A;">
                        <i class="fa fa-globe text-primary" style="margin-right: 6px;"></i> Target Page: <strong>{{ strtoupper(str_replace('_', ' ', $seo->page_title)) }}</strong>
                    </span>
                </div>

                {!! Form::model($seo, array('method' => 'put', 'route' => array('update.seo', $seo->id), 'class' => 'form', 'files'=>true)) !!}
                {!! Form::hidden('id', $seo->id) !!}
                <div class="seo-tab-body">
                    @include('admin.seo.forms.form')
                </div>

                <div class="seo-form-actions">
                    <a href="{{ route('list.seo') }}" class="btn-seo-back">Cancel</a>
                    <button type="submit" class="btn-seo-save">
                        <i class="fa fa-check"></i> Save SEO Changes
                    </button>
                </div>
                {!! Form::close() !!}
            </div>

        </div>

    </div>
</div>
@endsection