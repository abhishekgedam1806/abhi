@extends('admin.layouts.admin_layout')

@section('content')
<style>
    /* SaaS Dashboard Global Tokens */
    :root {
        --saas-primary: #2563EB;
        --saas-primary-hover: #1D4ED8;
        --saas-primary-light: #EFF6FF;
        --saas-success: #03855c;
        --saas-success-light: #ECFDF5;
        --saas-warning: #D97706;
        --saas-warning-light: #FFFBEB;
        --saas-danger: #DC2626;
        --saas-danger-light: #FEF2F2;
        --saas-purple: #7C3AED;
        --saas-purple-light: #F5F3FF;
        --saas-border: #E2E8F0;
        --saas-card-bg: #FFFFFF;
        --saas-text-main: #0F172A;
        --saas-text-muted: #64748B;
        --saas-text-light: #94A3B8;
    }

    .pipeline-dashboard-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: var(--saas-text-main);
    }

    /* KPI Metric Cards */
    .kpi-card {
        background: var(--saas-card-bg);
        border: 1px solid var(--saas-border);
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease-in-out;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.08);
        border-color: #CBD5E1;
    }
    .kpi-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--saas-text-muted);
        margin-bottom: 4px;
    }
    .kpi-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--saas-text-main);
        line-height: 1.1;
    }
    .kpi-sub {
        font-size: 12px;
        color: var(--saas-text-muted);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .kpi-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    /* Modern SaaS Control Panel */
    .saas-settings-panel {
        background: #FFFFFF;
        border: 1px solid var(--saas-border);
        border-radius: 14px;
        padding: 20px 22px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    }
    .saas-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #F1F5F9;
        padding-bottom: 14px;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .saas-title-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .saas-title-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--saas-primary-light);
        color: var(--saas-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .status-pulse-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #F0FDF4;
        color: #166534;
        border: 1px solid #BBF7D0;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: 0.2px;
    }
    .pulse-dot-green {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10B981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseGreen 2s infinite;
    }
    @keyframes pulseGreen {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Modern Stepper Input */
    .saas-stepper {
        display: inline-flex;
        align-items: center;
        border: 1.5px solid var(--saas-border);
        border-radius: 8px;
        overflow: hidden;
        background: #F8FAFC;
    }
    .saas-stepper button {
        background: transparent;
        border: none;
        width: 36px;
        height: 36px;
        font-size: 18px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s, color 0.15s;
    }
    .saas-stepper button:hover {
        background: #E2E8F0;
        color: var(--saas-text-main);
    }
    .saas-stepper input {
        width: 50px;
        height: 36px;
        border: none;
        border-left: 1px solid var(--saas-border);
        border-right: 1px solid var(--saas-border);
        text-align: center;
        font-weight: 800;
        font-size: 15px;
        color: var(--saas-text-main);
        background: #FFFFFF;
        outline: none;
    }

    /* SaaS Interactive Toggle Switch */
    .saas-toggle-btn {
        width: 44px;
        height: 24px;
        background-color: #CBD5E1;
        border-radius: 24px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.25s ease;
        display: inline-block;
        flex-shrink: 0;
    }
    .saas-toggle-btn.is-active {
        background-color: var(--saas-success) !important;
    }
    .saas-toggle-thumb {
        width: 18px;
        height: 18px;
        background-color: #FFFFFF;
        border-radius: 50%;
        position: absolute;
        top: 3px;
        left: 3px;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .saas-toggle-btn.is-active .saas-toggle-thumb {
        transform: translateX(20px);
    }

    /* SaaS Navigation Tabs */
    .saas-tabs-wrap {
        background: #FFFFFF;
        border: 1px solid var(--saas-border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    }
    .saas-nav-tabs {
        display: flex;
        background: #F8FAFC;
        border-bottom: 1px solid var(--saas-border);
        padding: 6px 12px 0 12px;
        gap: 6px;
        list-style: none;
        margin: 0;
    }
    .saas-nav-tabs li {
        margin: 0;
    }
    .saas-nav-tabs li a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 18px;
        font-weight: 700;
        font-size: 13px;
        color: var(--saas-text-muted);
        text-decoration: none;
        border-radius: 8px 8px 0 0;
        border: 1px solid transparent;
        border-bottom: none;
        transition: all 0.15s;
    }
    .saas-nav-tabs li.active a {
        background: #FFFFFF;
        color: var(--saas-primary);
        border-color: var(--saas-border);
        border-bottom-color: #FFFFFF;
        margin-bottom: -1px;
    }
    .saas-nav-tabs li a:hover:not(.active) {
        color: var(--saas-text-main);
        background: #F1F5F9;
    }
    .tab-counter-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 12px;
        background: #E2E8F0;
        color: #475569;
    }
    .saas-nav-tabs li.active .tab-counter-badge {
        background: var(--saas-primary-light);
        color: var(--saas-primary);
    }

    /* SaaS Table Styling */
    .saas-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }
    .saas-table th {
        background: #F8FAFC !important;
        color: #475569 !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 12px 16px !important;
        border-top: none !important;
        border-bottom: 1px solid var(--saas-border) !important;
    }
    .saas-table td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #F1F5F9;
        font-size: 13px;
        color: #334155;
    }
    .saas-table tbody tr:hover {
        background-color: #F8FAFC;
    }

    /* Compact Skill Badges */
    .skill-chip {
        display: inline-block;
        background: #F1F5F9;
        color: #334155;
        border: 1px solid #E2E8F0;
        font-size: 10px;
        font-weight: 600;
        padding: 1.5px 6px;
        border-radius: 4px;
        margin: 1.5px;
        line-height: 1.3;
    }
    .category-chip {
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #DBEAFE;
        font-size: 10px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .score-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
    }
    .score-high { background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }
    .score-med { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
    .score-low { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }

    .slug-chip {
        font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
        font-size: 11px;
        background: #F8FAFC;
        color: #475569;
        border: 1px solid #E2E8F0;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Stacked Action Layout */
    .saas-action-stack {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 100px;
        max-width: 115px;
        margin-left: auto;
    }
    .saas-action-row-bottom {
        display: flex;
        gap: 3px;
        width: 100%;
    }
    .btn-saas-action {
        flex: 1;
        height: 28px;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--saas-border);
        background: #FFFFFF;
        color: #475569;
        font-size: 12px;
        transition: all 0.15s ease;
        padding: 0;
    }
    .btn-saas-action:hover {
        background: #F1F5F9;
        color: var(--saas-text-main);
        border-color: #CBD5E1;
    }
    .btn-saas-action.btn-saas-delete:hover {
        background: var(--saas-danger-light);
        color: var(--saas-danger);
        border-color: #FECACA;
    }
    .btn-saas-action.btn-saas-edit:hover {
        background: var(--saas-primary-light);
        color: var(--saas-primary);
        border-color: #BFDBFE;
    }
    .btn-saas-publish {
        width: 100%;
        height: 28px;
        background: var(--saas-primary);
        color: #FFFFFF;
        border: 1px solid var(--saas-primary);
        font-weight: 700;
        font-size: 11px;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        box-shadow: 0 1px 2px rgba(37,99,235,0.25);
        transition: all 0.15s;
    }
    .btn-saas-publish:hover {
        background: var(--saas-primary-hover);
        color: #FFFFFF;
        box-shadow: 0 3px 6px rgba(37,99,235,0.35);
    }
    .btn-saas-enrich {
        width: 100%;
        height: 28px;
        background: var(--saas-success);
        color: #FFFFFF;
        border: 1px solid var(--saas-success);
        font-weight: 700;
        font-size: 11px;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        box-shadow: 0 1px 2px rgba(3,133,92,0.25);
        transition: all 0.15s;
    }
    .btn-saas-enrich:hover {
        background: #047857;
        color: #FFFFFF;
    }
    .btn-saas-live {
        width: 100%;
        height: 28px;
        background: #FFFFFF;
        color: var(--saas-primary);
        border: 1.5px solid #BFDBFE;
        font-weight: 700;
        font-size: 11px;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-saas-live:hover {
        background: var(--saas-primary-light);
        color: var(--saas-primary-hover);
        text-decoration: none;
    }

    /* Selection Sticky Bar */
    .bulk-selection-bar {
        padding: 10px 16px;
        background: #F8FAFC;
        border-bottom: 1px solid var(--saas-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="page-content-wrapper pipeline-dashboard-wrap">
    <div class="page-content" style="background: #F8FAFC; min-height: 100vh;">
        
        <!-- SaaS Top Header & Breadcrumb -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0; letter-spacing: -0.5px;">
                    AI Job Ingestion Pipeline
                </h2>
                <div style="font-size: 13px; color: #64748B; display: flex; align-items: center; gap: 6px;">
                    <span>AI Engine</span>
                    <i class="fa fa-angle-right" style="font-size: 11px; color: #CBD5E1;"></i>
                    <span style="color: #0F172A; font-weight: 600;">Automated Ingestion & Direct Portal Sync</span>
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#addRawJobModal" style="border-radius: 8px; font-weight: 600; background: #FFFFFF; border: 1px solid #CBD5E1; color: #334155; padding: 7px 13px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <i class="fa fa-plus" style="margin-right: 4px; color: #64748B;"></i> Manual Ingest
                </button>
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#keywordSearchModal" style="border-radius: 8px; font-weight: 600; background: #FFFFFF; border: 1px solid #7C3AED; color: #7C3AED; padding: 7px 13px; box-shadow: 0 1px 2px rgba(124,58,237,0.08);">
                    <i class="fa fa-search" style="margin-right: 4px;"></i> Keyword Job Search
                </button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#fetchAdzunaModal" style="border-radius: 8px; font-weight: 700; background-color: #03855c; border-color: #03855c; padding: 7px 15px; box-shadow: 0 2px 5px rgba(3,133,92,0.25);">
                    <i class="fa fa-cloud-download" style="margin-right: 5px;"></i> Fetch Live Jobs
                </button>
            </div>

        </div>

        @include('flash::message')

        <!-- Row 1: KPI Metrics Overview -->
        <div class="row">
            <!-- 1. Published Today -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Published Today</div>
                        <div class="kpi-value" style="color: #03855c;">
                            {{ $publishedTodayCount }} <span style="font-size: 15px; font-weight: 600; color: #94A3B8;">/ {{ $pipelineSettings->daily_fetch_limit }}</span>
                        </div>
                        <div class="kpi-sub">
                            <span class="status-pulse-pill" style="padding: 1px 6px; font-size: 10px;">Target Active</span>
                        </div>
                    </div>
                    <div class="kpi-icon-box" style="background: #ECFDF5; color: #03855c;">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <!-- 2. Enriched & Ready -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Enriched & Ready</div>
                        <div class="kpi-value" style="color: #2563EB;">{{ number_format($enrichedCount) }}</div>
                        <div class="kpi-sub">Scored & SEO Packaged</div>
                    </div>
                    <div class="kpi-icon-box" style="background: #EFF6FF; color: #2563EB;">
                        <i class="fa fa-magic"></i>
                    </div>
                </div>
            </div>

            <!-- 3. Raw Ingestion Queue -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Raw Queue</div>
                        <div class="kpi-value" style="color: #D97706;">{{ number_format($rawCount) }}</div>
                        <div class="kpi-sub">Pending AI Analysis</div>
                    </div>
                    <div class="kpi-icon-box" style="background: #FFFBEB; color: #D97706;">
                        <i class="fa fa-inbox"></i>
                    </div>
                </div>
            </div>

            <!-- 4. Total Published on Portal -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Total AI Published</div>
                        <div class="kpi-value" style="color: #7C3AED;">{{ number_format($totalPublished) }}</div>
                        <div class="kpi-sub">Live on Portal (/jobs)</div>
                    </div>
                    <div class="kpi-icon-box" style="background: #F5F3FF; color: #7C3AED;">
                        <i class="fa fa-globe"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: SaaS Automated Ingestion & Configuration Bar -->
        <div class="saas-settings-panel">
            <div class="saas-panel-header">
                <div class="saas-title-group">
                    <div class="saas-title-icon">
                        <i class="fa fa-sliders"></i>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <h4 style="margin: 0; font-weight: 800; color: #0F172A; font-size: 15px;">Daily Automated Ingestion Settings</h4>
                            <span class="status-pulse-pill">
                                <span class="pulse-dot-green"></span> Live Cron Active (8 AM & 4 PM)
                            </span>
                        </div>
                        <p style="margin: 2px 0 0 0; font-size: 12px; color: #64748B;">
                            Fetch fresh Indian vacancies via Adzuna API, enrich with Gemini Flash-Lite, and auto-publish to live portal.
                        </p>
                    </div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <form action="{{ route('admin.ai.pipeline.fetch_adzuna') }}" method="POST" style="margin: 0;">
                        @csrf
                        <input type="hidden" name="limit" value="{{ $pipelineSettings->daily_fetch_limit }}">
                        <button type="submit" class="btn btn-sm btn-success" style="border-radius: 8px; font-weight: 700; background: #03855c; border-color: #03855c; padding: 7px 14px;">
                            <i class="fa fa-bolt" style="margin-right: 4px;"></i> Run Fetcher Now ({{ $pipelineSettings->daily_fetch_limit }} Jobs)
                        </button>
                    </form>
                </div>
            </div>

            <form action="{{ route('admin.ai.pipeline.update_settings') }}" method="POST">
                @csrf
                <div class="row" style="align-items: center;">
                    <!-- 1. Daily Jobs Limit Stepper -->
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                                <i class="fa fa-bullseye" style="color: #2563EB; margin-right: 4px;"></i> Daily Job Target
                            </label>
                            <div class="saas-stepper">
                                <button type="button" onclick="adjustJobLimit(-1)">-</button>
                                <input type="number" name="daily_fetch_limit" id="daily_fetch_limit_input" value="{{ $pipelineSettings->daily_fetch_limit }}" min="1" max="50" required>
                                <button type="button" onclick="adjustJobLimit(1)">+</button>
                            </div>
                            <span style="font-size: 11px; color: #64748B; margin-top: 4px; display: block;">Jobs ingested per daily run</span>
                        </div>
                    </div>

                    <!-- 2. Auto-Publish to Portal Toggle -->
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                                <i class="fa fa-rocket" style="color: #03855c; margin-right: 4px;"></i> Auto-Publish to Portal
                            </label>
                            <div style="display: flex; align-items: center; gap: 10px; height: 36px;">
                                <input type="hidden" name="auto_publish" id="auto_publish_input" value="{{ $pipelineSettings->auto_publish ? 1 : 0 }}">
                                <div class="saas-toggle-btn {{ $pipelineSettings->auto_publish ? 'is-active' : '' }}" id="auto_publish_toggle" onclick="toggleAutoPublish()" title="Click to toggle Auto-Publish">
                                    <div class="saas-toggle-thumb"></div>
                                </div>
                                <span id="auto_publish_label" onclick="toggleAutoPublish()" style="cursor: pointer; font-weight: 700; font-size: 12px; color: {{ $pipelineSettings->auto_publish ? '#03855c' : '#64748B' }}; user-select: none;">
                                    {{ $pipelineSettings->auto_publish ? 'Directly Live on /jobs' : 'Hold in Queue' }}
                                </span>
                            </div>
                            <span style="font-size: 11px; color: #64748B; margin-top: 4px; display: block;">Publishes if Quality Score ≥ {{ $pipelineSettings->min_quality_score }}%</span>
                        </div>
                    </div>

                    <!-- 3. Target Cities -->
                    <div class="col-md-4 col-sm-8">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                                <i class="fa fa-map-marker" style="color: #DC2626; margin-right: 4px;"></i> Target Indian Cities
                            </label>
                            <input type="text" name="target_cities" value="{{ $pipelineSettings->target_cities }}" class="form-control" style="border-radius: 8px; height: 36px; font-size: 12px; border: 1.5px solid #CBD5E1;" placeholder="Nagpur, Mumbai, Pune, Delhi, Bangalore">
                            <span style="font-size: 11px; color: #64748B; margin-top: 4px; display: block;">Comma-separated target cities</span>
                        </div>
                    </div>

                    <!-- 4. Save Button -->
                    <div class="col-md-2 col-sm-4 text-right">
                        <div class="form-group" style="margin-bottom: 0; padding-top: 24px;">
                            <button type="submit" class="btn btn-primary btn-block" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; font-weight: 700; height: 36px; box-shadow: 0 2px 4px rgba(37,99,235,0.25);">
                                <i class="fa fa-save" style="margin-right: 4px;"></i> Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Row 3: SaaS Data Tables & Tab Segment -->
        <div class="saas-tabs-wrap">
            <!-- Segmented Navigation Header -->
            <ul class="saas-nav-tabs">
                <li class="{{ $tab == 'enriched' ? 'active' : '' }}">
                    <a href="{{ route('admin.ai.pipeline', ['tab' => 'enriched']) }}">
                        <i class="fa fa-magic" style="color: #2563EB;"></i> Enriched & Ready
                        <span class="tab-counter-badge">{{ $enrichedCount }}</span>
                    </a>
                </li>
                <li class="{{ $tab == 'raw' ? 'active' : '' }}">
                    <a href="{{ route('admin.ai.pipeline', ['tab' => 'raw']) }}">
                        <i class="fa fa-inbox" style="color: #D97706;"></i> Raw Ingestion Queue
                        <span class="tab-counter-badge">{{ $rawCount }}</span>
                    </a>
                </li>
                <li class="{{ $tab == 'published' ? 'active' : '' }}">
                    <a href="{{ route('admin.ai.pipeline', ['tab' => 'published']) }}">
                        <i class="fa fa-check-circle" style="color: #03855c;"></i> Published Portal Jobs
                        <span class="tab-counter-badge">{{ $totalPublished }}</span>
                    </a>
                </li>
            </ul>

            {{-- ═══ SEARCH BAR — works on all 3 pipeline tabs ═══ --}}
            <div style="padding: 12px 16px 4px 16px; border-bottom: 1px solid #F1F5F9;">
                <form method="GET" action="{{ route('admin.ai.pipeline') }}" id="pipelineSearchForm" style="margin:0;">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <div style="position:relative; flex:1; min-width:220px; max-width:480px;">
                            <span style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#94A3B8; font-size:14px; pointer-events:none;">
                                <i class="fa fa-search"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                id="pipelineSearchInput"
                                value="{{ $search }}"
                                placeholder="Search by title, company, location…"
                                autocomplete="off"
                                style="width:100%; padding:8px 36px 8px 32px; border:1.5px solid {{ $search ? '#2563EB' : '#E2E8F0' }}; border-radius:8px; font-size:13px; font-weight:500; color:#0F172A; background:#FAFAFA; outline:none; transition:border-color .15s;"
                                onfocus="this.style.borderColor='#2563EB'; this.style.background='#fff';"
                                onblur="this.style.borderColor=this.value ? '#2563EB' : '#E2E8F0'; this.style.background='#FAFAFA';"
                            >
                            @if($search)
                            <a href="{{ route('admin.ai.pipeline', ['tab' => $tab]) }}"
                               title="Clear search"
                               style="position:absolute; right:8px; top:50%; transform:translateY(-50%); color:#94A3B8; font-size:13px; text-decoration:none; line-height:1;">
                                <i class="fa fa-times-circle"></i>
                            </a>
                            @endif
                        </div>
                        <button type="submit"
                            style="padding:8px 16px; background:#2563EB; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:5px; transition:background .15s;"
                            onmouseover="this.style.background='#1D4ED8';" onmouseout="this.style.background='#2563EB';">
                            <i class="fa fa-search"></i> Search
                        </button>
                        @if($search)
                        <a href="{{ route('admin.ai.pipeline', ['tab' => $tab]) }}"
                           style="padding:8px 14px; background:#F1F5F9; color:#475569; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:5px;">
                            <i class="fa fa-times"></i> Clear
                        </a>
                        @endif
                    </div>
                    @if($search)
                    <div style="margin-top:7px; font-size:12px; color:#64748B; font-weight:500;">
                        <i class="fa fa-filter" style="color:#2563EB; margin-right:3px;"></i>
                        Showing results for <strong style="color:#0F172A;">"{{ $search }}"</strong>
                        — {{ method_exists($jobs, 'total') ? $jobs->total() : $jobs->count() }} job(s) found
                    </div>
                    @endif
                </form>
            </div>
            {{-- ═══ END SEARCH BAR ═══ --}}

            <div>
                <!-- TAB 1: ENRICHED & READY -->
                @if($tab == 'enriched')

                    @if($jobs->count() > 0)
                        <!-- Standalone Bulk Delete Form -->
                        <form id="bulkDeleteEnrichedForm" action="{{ route('admin.ai.pipeline.raw.bulk_delete') }}" method="POST" style="display: none;">
                            @csrf
                            <div id="bulkDeleteEnrichedInputs"></div>
                        </form>

                        <div class="bulk-selection-bar">
                            <div style="font-size: 13px; font-weight: 600; color: #475569;">
                                <span id="selectedCountBadgeEnriched" class="badge" style="background: #2563EB; font-size: 11px; padding: 4px 8px;">0 selected</span>
                            </div>
                            <div>
                                <button type="button" id="bulkDeleteBtnEnriched" class="btn btn-sm btn-danger" style="border-radius: 6px; font-weight: 600; display: none;" onclick="submitBulkDeleteEnriched()" disabled>
                                    <i class="fa fa-trash"></i> Delete Selected Jobs
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="saas-table">
                                <thead>
                                    <tr>
                                        <th style="width: 4%; text-align: center;">
                                            <input type="checkbox" id="selectAllEnriched" title="Select All">
                                        </th>
                                        <th style="width: 32%;">Job Title & Company</th>
                                        <th style="width: 22%;">Extracted Skills</th>
                                        <th style="width: 14%;">Quality Score</th>
                                        <th style="width: 16%;">SEO / Slug</th>
                                        <th style="width: 12%; text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobs as $raw)
                                    @php $ai = $raw->aiData; @endphp
                                    <tr>
                                        <td style="text-align: center;">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $raw->id }}" class="enriched-checkbox" onchange="updateSelectedCountEnriched()">
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; font-size: 14px; color: #0F172A; line-height: 1.3;">
                                                {{ $ai && $ai->seo_title ? $ai->seo_title : $raw->raw_title }}
                                            </div>
                                            <div style="font-size: 12px; color: #64748B; margin-top: 4px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                                <span><i class="fa fa-building text-muted"></i> {{ $raw->raw_company ?: 'Featured Employer' }}</span>
                                                <span>•</span>
                                                <span><i class="fa fa-map-marker text-danger"></i> {{ $raw->raw_location ?: 'Nagpur' }}</span>
                                                @if($ai && $ai->suggested_category)
                                                    <span class="category-chip">{{ $ai->suggested_category }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div style="max-width: 220px;">
                                                @if($ai && count($ai->skills_array) > 0)
                                                    @foreach(array_slice($ai->skills_array, 0, 5) as $sk)
                                                        <span class="skill-chip">{{ $sk }}</span>
                                                    @endforeach
                                                    @if(count($ai->skills_array) > 5)
                                                        <span style="font-size: 10px; color: #64748B; font-weight: 700; margin-left: 2px;">+{{ count($ai->skills_array) - 5 }}</span>
                                                    @endif
                                                @else
                                                    <span style="color: #94A3B8; font-size: 11px;">No skills extracted</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php $score = $ai ? $ai->quality_score : 80; @endphp
                                            <div class="score-chip {{ $score >= 80 ? 'score-high' : ($score >= 60 ? 'score-med' : 'score-low') }}">
                                                <i class="fa {{ $score >= 80 ? 'fa-shield' : 'fa-info-circle' }}"></i>
                                                <span>{{ $score }}/100</span>
                                            </div>
                                            <div style="font-size: 11px; color: #94A3B8; margin-top: 3px;">
                                                {{ $ai ? $ai->experience_level : 'Not specified' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="slug-chip" title="{{ $ai ? '/' . $ai->slug : '' }}">
                                                {{ $ai && $ai->slug ? '/' . $ai->slug : '—' }}
                                            </div>
                                        </td>
                                        <td style="text-align: right;">
                                            <!-- Stacked Action Layout: Publish on Top, 3 buttons on Bottom -->
                                            <div class="saas-action-stack">
                                                <!-- Top: Publish Button -->
                                                <form action="{{ route('admin.ai.pipeline.publish', $raw->id) }}" method="POST" style="margin: 0; width: 100%;">
                                                    @csrf
                                                    <button type="submit" class="btn-saas-publish" title="Publish to live portal">
                                                        <i class="fa fa-rocket"></i> <span>Publish</span>
                                                    </button>
                                                </form>

                                                <!-- Bottom: View, Edit, Delete Row -->
                                                <div class="saas-action-row-bottom">
                                                    <button type="button" class="btn-saas-action" onclick="openViewModal({{ json_encode($raw) }})" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn-saas-action btn-saas-edit" onclick="openEditModal({{ json_encode($raw) }})" title="Edit Job">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('admin.ai.pipeline.raw.delete', $raw->id) }}" method="POST" style="margin: 0; flex: 1;" onsubmit="return confirm('Delete this job?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-saas-action btn-saas-delete" style="width: 100%;" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center" style="padding: 18px;">
                            {{ $jobs->appends(['tab' => 'enriched', 'search' => $search])->links() }}
                        </div>
                    @else
                        <div style="text-align: center; padding: 56px 20px; color: #64748B;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 14px auto;">
                                <i class="fa fa-magic"></i>
                            </div>
                            <h4 style="font-weight: 800; color: #0F172A; margin-bottom: 6px;">No Enriched Jobs Waiting</h4>
                            <p style="font-size: 13px; max-width: 440px; margin: 0 auto 16px auto;">
                                Ingest raw jobs or run the Adzuna API fetcher to enrich fresh postings with Gemini AI.
                            </p>
                        </div>
                    @endif

                <!-- TAB 2: RAW INGESTION QUEUE -->
                @elseif($tab == 'raw')
                    @if($jobs->count() > 0)
                        <!-- Standalone Bulk Delete Form -->
                        <form id="bulkDeleteRawForm" action="{{ route('admin.ai.pipeline.raw.bulk_delete') }}" method="POST" style="display: none;">
                            @csrf
                            <div id="bulkDeleteRawInputs"></div>
                        </form>

                        <div class="bulk-selection-bar">
                            <div style="font-size: 13px; font-weight: 600; color: #475569;">
                                <span id="selectedCountBadge" class="badge" style="background: #2563EB; font-size: 11px; padding: 4px 8px;">0 selected</span>
                            </div>
                            <div>
                                <button type="button" id="bulkDeleteBtn" class="btn btn-sm btn-danger" style="border-radius: 6px; font-weight: 600; display: none;" onclick="submitBulkDeleteRaw()" disabled>
                                    <i class="fa fa-trash"></i> Delete Selected Jobs
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="saas-table">
                                <thead>
                                    <tr>
                                        <th style="width: 4%; text-align: center;">
                                            <input type="checkbox" id="selectAllRaw" title="Select All">
                                        </th>
                                        <th style="width: 34%;">Job Title & Company</th>
                                        <th style="width: 20%;">Source & Location</th>
                                        <th style="width: 18%;">Fingerprint Hash (SHA-256)</th>
                                        <th style="width: 12%;">Ingested</th>
                                        <th style="width: 12%; text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobs as $raw)
                                    <tr>
                                        <td style="text-align: center;">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $raw->id }}" class="raw-checkbox" onchange="updateSelectedCount()">
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; font-size: 14px; color: #0F172A; line-height: 1.3;">
                                                {{ $raw->raw_title }}
                                            </div>
                                            <div style="font-size: 12px; color: #64748B; margin-top: 3px;">
                                                <i class="fa fa-building" style="margin-right: 4px;"></i> {{ $raw->raw_company ?: 'Featured Employer' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size: 13px; font-weight: 600; color: #334155;">
                                                <i class="fa fa-map-marker text-danger" style="margin-right: 4px;"></i> {{ $raw->raw_location ?: 'Nagpur' }}
                                            </div>
                                            <div style="font-size: 11px; color: #64748B; margin-top: 3px; display: flex; align-items: center; gap: 6px;">
                                                <span class="category-chip" style="background: #F1F5F9; color: #475569; border-color: #E2E8F0;">
                                                    {{ $raw->source_name ?: 'Feed' }}
                                                </span>
                                                @if(!empty($raw->source_url))
                                                    <a href="{{ $raw->source_url }}" target="_blank" rel="noopener noreferrer" style="color: #2563EB; font-weight: 600; text-decoration: none;" title="Verify Source">
                                                        <i class="fa fa-external-link"></i> Verify
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="slug-chip" style="max-width: 170px;" title="{{ $raw->content_hash }}">
                                                {{ substr($raw->content_hash, 0, 16) }}...
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size: 12px; color: #64748B;">
                                                {{ $raw->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td style="text-align: right;">
                                            <!-- Stacked Action Layout: Enrich on Top, 3 buttons on Bottom -->
                                            <div class="saas-action-stack">
                                                <!-- Top: Enrich Button -->
                                                <form action="{{ route('admin.ai.pipeline.enrich', $raw->id) }}" method="POST" style="margin: 0; width: 100%;">
                                                    @csrf
                                                    <button type="submit" class="btn-saas-enrich" title="Run Gemini AI Enrichment">
                                                        <i class="fa fa-bolt"></i> <span>Enrich</span>
                                                    </button>
                                                </form>

                                                <!-- Bottom: View, Edit, Delete Row -->
                                                <div class="saas-action-row-bottom">
                                                    <button type="button" class="btn-saas-action" onclick="openViewModal({{ json_encode($raw) }})" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn-saas-action btn-saas-edit" onclick="openEditModal({{ json_encode($raw) }})" title="Edit Job">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('admin.ai.pipeline.raw.delete', $raw->id) }}" method="POST" style="margin: 0; flex: 1;" onsubmit="return confirm('Delete this job?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-saas-action btn-saas-delete" style="width: 100%;" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center" style="padding: 18px;">
                            {{ $jobs->appends(['tab' => 'raw', 'search' => $search])->links() }}
                        </div>
                    @else
                        <div style="text-align: center; padding: 56px 20px; color: #64748B;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: #FFFBEB; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 14px auto;">
                                <i class="fa fa-inbox"></i>
                            </div>
                            <h4 style="font-weight: 800; color: #0F172A; margin-bottom: 6px;">Raw Queue is Empty</h4>
                            <p style="font-size: 13px; max-width: 440px; margin: 0 auto 16px auto;">
                                Click below to ingest fresh sample job feeds or run the live Adzuna fetcher.
                            </p>
                            <form action="{{ route('admin.ai.pipeline.seed_samples') }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; font-weight: 700; padding: 8px 18px;">
                                    <i class="fa fa-bolt" style="margin-right: 4px;"></i> Ingest Sample Batch
                                </button>
                            </form>
                        </div>
                    @endif

                <!-- TAB 3: PUBLISHED JOBS -->
                @elseif($tab == 'published')
                    @if($jobs->count() > 0)
                        <!-- Standalone Bulk Delete Form -->
                        <form id="bulkDeletePublishedForm" action="{{ route('admin.ai.pipeline.raw.bulk_delete') }}" method="POST" style="display: none;">
                            @csrf
                            <div id="bulkDeletePublishedInputs"></div>
                        </form>

                        <div class="bulk-selection-bar">
                            <div style="font-size: 13px; font-weight: 600; color: #475569;">
                                <span id="selectedCountBadgePublished" class="badge" style="background: #2563EB; font-size: 11px; padding: 4px 8px;">0 selected</span>
                            </div>
                            <div>
                                <button type="button" id="bulkDeleteBtnPublished" class="btn btn-sm btn-danger" style="border-radius: 6px; font-weight: 600; display: none;" onclick="submitBulkDeletePublished()" disabled>
                                    <i class="fa fa-trash"></i> Delete Selected Jobs
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="saas-table">
                                <thead>
                                    <tr>
                                        <th style="width: 4%; text-align: center;">
                                            <input type="checkbox" id="selectAllPublished" title="Select All">
                                        </th>
                                        <th style="width: 32%;">Live Job Posting</th>
                                        <th style="width: 22%;">Company & Location</th>
                                        <th style="width: 14%;">Quality Score</th>
                                        <th style="width: 16%;">Google Schema</th>
                                        <th style="width: 12%; text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobs as $raw)
                                    @php 
                                        $job = $raw->publishedJob; 
                                        $ai = $raw->aiData;
                                    @endphp
                                    <tr>
                                        <td style="text-align: center;">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $raw->id }}" class="published-checkbox" onchange="updateSelectedCountPublished()">
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; font-size: 14px; color: #0F172A; line-height: 1.3;">
                                                {{ $job ? $job->title : $raw->raw_title }}
                                            </div>
                                            <div style="font-size: 11px; color: #64748B; margin-top: 3px;">
                                                Published: {{ $raw->updated_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size: 13px; font-weight: 600; color: #334155;">
                                                {{ $raw->raw_company ?: ($job ? $job->getCompany('name') : '') }}
                                            </div>
                                            <div style="font-size: 11px; color: #64748B; margin-top: 2px;">
                                                <i class="fa fa-map-marker text-danger"></i> {{ $raw->raw_location ?: ($job ? $job->getLocation() : '') }}
                                            </div>
                                        </td>
                                        <td>
                                            @php $score = $ai ? $ai->quality_score : 85; @endphp
                                            <div class="score-chip score-high">
                                                <i class="fa fa-check-circle"></i>
                                                <span>{{ $score }}/100</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 4px; display: inline-flex; align-items: center; gap: 3px;">
                                                <i class="fa fa-google" style="color: #10B981;"></i> Schema
                                            </span>
                                        </td>
                                        <td style="text-align: right;">
                                            <!-- Stacked Action Layout: Live on Top, 3 buttons on Bottom -->
                                            <div class="saas-action-stack">
                                                @if($job)
                                                    <a href="{{ route('job.detail', [$job->slug]) }}" target="_blank" class="btn-saas-live" title="View Live on Portal">
                                                        <i class="fa fa-external-link"></i> <span>Live Job</span>
                                                    </a>
                                                @else
                                                    <div style="height: 28px;"></div>
                                                @endif

                                                <!-- Bottom: View, Edit, Delete Row -->
                                                <div class="saas-action-row-bottom">
                                                    <button type="button" class="btn-saas-action" onclick="openViewModal({{ json_encode($raw) }})" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn-saas-action btn-saas-edit" onclick="openEditModal({{ json_encode($raw) }})" title="Edit Job">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('admin.ai.pipeline.raw.delete', $raw->id) }}" method="POST" style="margin: 0; flex: 1;" onsubmit="return confirm('Delete and unpublish this job from portal?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-saas-action btn-saas-delete" style="width: 100%;" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center" style="padding: 18px;">
                            {{ $jobs->appends(['tab' => 'published', 'search' => $search])->links() }}
                        </div>
                    @else
                        <div style="text-align: center; padding: 56px 20px; color: #64748B;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background: #F5F3FF; color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 14px auto;">
                                <i class="fa fa-globe"></i>
                            </div>
                            <h4 style="font-weight: 800; color: #0F172A; margin-bottom: 6px;">No Published AI Jobs Yet</h4>
                            <p style="font-size: 13px; max-width: 440px; margin: 0 auto;">
                                Enriched jobs published through the pipeline will be listed here.
                            </p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ingest Raw Job Manually -->
<div class="modal fade" id="addRawJobModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <div class="modal-header" style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; padding: 18px 24px;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="font-weight: 800; color: #0F172A; margin: 0;">
                    <i class="fa fa-plus-circle" style="color: #2563EB; margin-right: 6px;"></i> Ingest Raw Job Manually
                </h4>
            </div>
            <form action="{{ route('admin.ai.pipeline.ingest') }}" method="POST">
                @csrf
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group">
                        <label><strong>Job Title <span class="text-danger">*</span></strong></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Senior PHP Backend Engineer" required style="border-radius: 8px; height: 40px;">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Company / Employer Name</strong></label>
                                <input type="text" name="company" class="form-control" placeholder="e.g. Apex Tech Solutions" style="border-radius: 8px; height: 40px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Location</strong></label>
                                <input type="text" name="location" class="form-control" placeholder="e.g. Nagpur, Maharashtra" style="border-radius: 8px; height: 40px;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><strong>Job Description <span class="text-danger">*</span></strong></label>
                        <textarea name="description" class="form-control" rows="6" placeholder="Paste unformatted job description, requirements, or bullet points here..." required style="border-radius: 8px;"></textarea>
                    </div>
                    <div class="alert alert-info" style="border-radius: 8px; font-size: 12px; margin-bottom: 0; background: #EFF6FF; border-color: #BFDBFE; color: #1E40AF;">
                        <i class="fa fa-shield"></i> <strong>Zero Duplicate Protection:</strong> A deterministic SHA-256 content hash will be generated. If identical posting exists, it will be skipped automatically at ₹0 AI cost.
                    </div>
                </div>
                <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 14px 24px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; font-weight: 700; padding: 8px 18px;">
                        <i class="fa fa-check"></i> Add to Queue
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Fetch Real Jobs via Adzuna API -->
<div class="modal fade" id="fetchAdzunaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <div class="modal-header" style="background: #F0FDF4; border-bottom: 1px solid #DCFCE7; padding: 18px 24px;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="font-weight: 800; color: #065F46; margin: 0;">
                    <i class="fa fa-cloud-download" style="color: #03855c; margin-right: 6px;"></i> Automated Adzuna Live Job Ingestion
                </h4>
            </div>
            <form action="{{ route('admin.ai.pipeline.fetch_adzuna') }}" method="POST">
                @csrf
                <div class="modal-body" style="padding: 24px;">
                    <p style="font-size: 13px; color: #475569; margin-bottom: 18px;">
                        Fetches <strong>real, fresh job postings</strong> (created in the last 5–7 days) directly from Adzuna's India search API, computes SHA-256 fingerprint hashes, enriches them with Gemini AI, and auto-publishes to your live Job Portal.
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Jobs To Ingest (Limit)</strong></label>
                                <input type="number" name="limit" value="{{ $pipelineSettings->daily_fetch_limit }}" min="1" max="50" class="form-control" style="border-radius: 8px; font-weight: 700; height: 40px;">
                                <span class="help-block" style="font-size: 11px; color: #64748B;">Target number of jobs to fetch now.</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Maximum Job Age</strong></label>
                                <select name="days" class="form-control" style="border-radius: 8px; height: 40px;">
                                    <option value="3" {{ $pipelineSettings->max_job_age_days == 3 ? 'selected' : '' }}>Last 3 Days (Super Fresh)</option>
                                    <option value="5" {{ $pipelineSettings->max_job_age_days == 5 ? 'selected' : '' }}>Last 5 Days</option>
                                    <option value="7" {{ $pipelineSettings->max_job_age_days == 7 ? 'selected' : '' }}>Last 7 Days (Recommended)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Target Cities (Comma Separated)</strong></label>
                        <input type="text" name="cities" class="form-control" value="{{ $pipelineSettings->target_cities }}" style="border-radius: 8px; height: 40px;">
                        <span class="help-block" style="font-size: 11px; color: #64748B;">Enter target Indian cities to query for live vacancies.</span>
                    </div>

                    <div class="form-group" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0;">
                            <input type="checkbox" name="auto_publish" value="1" {{ $pipelineSettings->auto_publish ? 'checked' : '' }} style="width: 16px; height: 16px; margin: 0;">
                            <span style="font-size: 13px; font-weight: 700; color: #0F172A;">
                                <i class="fa fa-rocket text-success" style="margin-right: 4px;"></i> Auto-Publish Directly to Live Job Portal (/jobs)
                            </span>
                        </label>
                    </div>

                    <div class="alert alert-success" style="border-radius: 8px; font-size: 12px; margin-bottom: 0; background: #F0FDF4; border-color: #BBF7D0; color: #166534;">
                        <i class="fa fa-info-circle"></i> <strong>Automated Cron Active:</strong> This job fetcher also runs automatically twice daily (at 8:00 AM & 4:00 PM) via Laravel Scheduler.
                    </div>
                </div>
                <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 14px 24px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cancel</button>
                    <button type="submit" class="btn btn-success" style="background: #03855c; border-color: #03855c; border-radius: 8px; font-weight: 700; padding: 8px 18px;">
                        <i class="fa fa-refresh"></i> Fetch & Ingest Live Jobs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: View Raw Job Details -->

<!-- ═══ Smart Keyword Job Search — 2-Step AJAX Preview Modal ═══ -->
<div class="modal fade" id="keywordSearchModal" tabindex="-1" role="dialog" aria-labelledby="kwModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width: 860px; max-width: 96vw;">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 24px 40px -5px rgba(0,0,0,0.18);">

            {{-- ── Shared Header ── --}}
            <div class="modal-header" id="kwModalHeader" style="background: linear-gradient(135deg,#F5F3FF 0%,#EFF6FF 100%); border-bottom:1px solid #DDD6FE; padding:16px 24px; display:flex; align-items:center; justify-content:space-between;">
                <h4 class="modal-title" id="kwModalTitle" style="font-weight:800; color:#3730A3; margin:0; font-size:16px;">
                    <i class="fa fa-search" style="color:#7C3AED; margin-right:7px;"></i>
                    <span id="kwTitleText">Smart Keyword Job Search &amp; Preview</span>
                    <span style="font-size:11px; font-weight:600; color:#7C3AED; background:#EDE9FE; padding:2px 8px; border-radius:20px; margin-left:8px; vertical-align:middle;">Live Adzuna API</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin:0; font-size:22px; color:#64748B; opacity:1;">&times;</button>
            </div>

            {{-- ════════ STEP 1: Search Form ════════ --}}
            <div id="kwStep1">
                <div class="modal-body" style="padding:22px 24px;">

                    {{-- Pipeline flow badge --}}
                    <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:10px 14px; margin-bottom:18px; display:flex; align-items:center; gap:8px; flex-wrap:wrap; font-size:12px; color:#475569; font-weight:600;">
                        <span style="background:#FEF3C7; color:#92400E; border:1px solid #FDE68A; padding:2px 9px; border-radius:20px;"><i class="fa fa-inbox"></i> 1. Raw Queue</span>
                        <i class="fa fa-arrow-right" style="color:#CBD5E1; font-size:10px;"></i>
                        <span style="background:#EFF6FF; color:#1D4ED8; border:1px solid #BFDBFE; padding:2px 9px; border-radius:20px;"><i class="fa fa-magic"></i> 2. AI Enrich</span>
                        <i class="fa fa-arrow-right" style="color:#CBD5E1; font-size:10px;"></i>
                        <span style="background:#ECFDF5; color:#065F46; border:1px solid #A7F3D0; padding:2px 9px; border-radius:20px;"><i class="fa fa-rocket"></i> 3. Publish Live</span>
                        <span style="margin-left:auto; color:#7C3AED; font-weight:600; font-size:11px;"><i class="fa fa-eye"></i> Search → Preview Info (Date, Req, Desc) → Select → Add to Queue</span>
                    </div>

                    {{-- Quick chips --}}
                    <div class="form-group" style="margin-bottom:14px;">
                        <label style="font-size:12px; font-weight:700; color:#334155; margin-bottom:8px; display:block;">
                            <i class="fa fa-bolt" style="color:#7C3AED; margin-right:4px;"></i> Quick-Pick Roles
                        </label>
                        <div style="display:flex; flex-wrap:wrap; gap:6px;">
                            @foreach([
                                'SEO Specialist','Digital Marketing','Software Engineer',
                                'Web Developer','PHP Developer','Laravel Developer',
                                'React Developer','Python Developer','UI/UX Designer',
                                'Content Writer','Data Analyst','DevOps Engineer',
                                'Flutter Developer','Android Developer','iOS Developer',
                                'Project Manager','HR Manager','Sales Manager'
                            ] as $chip)
                            <button type="button" onclick="setKeyword('{{ $chip }}')" class="kw-chip-btn"
                                style="padding:4px 11px; border:1.5px solid #C4B5FD; border-radius:20px; font-size:12px; font-weight:600; color:#5B21B6; background:#F5F3FF; cursor:pointer; transition:all .15s;">
                                {{ $chip }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Keyword input --}}
                    <div class="form-group">
                        <label style="font-size:12.5px; font-weight:700; color:#334155; margin-bottom:6px; display:block;">
                            Job Keyword / Role Title <span class="text-danger">*</span>
                        </label>
                        <div style="position:relative; display:flex; align-items:center;">
                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#7C3AED; pointer-events:none; font-size:15px; z-index:3;">
                                <i class="fa fa-search"></i>
                            </span>
                            <input type="text" id="kwKeyword" required placeholder="e.g. SEO Specialist, Software Engineer, Digital Marketing..." class="form-control"
                                style="padding-left:44px !important; padding-right:96px !important; border-radius:10px !important; height:46px !important; font-weight:600 !important; border:1.5px solid #C4B5FD !important; font-size:14px !important; color:#0F172A !important; background:#FFFFFF !important; box-shadow:0 2px 6px rgba(124,58,237,0.06) !important;"
                                onfocus="this.style.borderColor='#7C3AED'; this.style.boxShadow='0 0 0 3px rgba(124,58,237,0.15)';"
                                onblur="this.style.borderColor='#C4B5FD'; this.style.boxShadow='0 2px 6px rgba(124,58,237,0.06)';"
                                onkeydown="if(event.key==='Enter'){event.preventDefault(); kwFetchPreview();}">
                            <button type="button" onclick="kwFetchPreview()" title="Search & Preview live jobs"
                                style="position:absolute; right:6px; top:50%; transform:translateY(-50%); background:linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%); color:#FFFFFF; border:none; border-radius:8px; padding:8px 16px; font-size:12.5px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 2px 6px rgba(124,58,237,0.3); transition:all .15s; z-index:3;"
                                onmouseover="this.style.transform='translateY(-50%) scale(1.02)';"
                                onmouseout="this.style.transform='translateY(-50%) scale(1)';">
                                <i class="fa fa-search"></i> <span>Search</span>
                            </button>
                        </div>
                        <span class="help-block" style="font-size:11.5px; color:#64748B; margin-top:5px; display:block;">Searches live vacancies across job titles, requirements, and descriptions.</span>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-size:12px; font-weight:700; color:#334155;">
                                    <i class="fa fa-globe" style="color:#2563EB;"></i> Target Country
                                </label>
                                <select id="kwCountry" class="form-control" style="border-radius:8px !important; height:40px !important; font-weight:600 !important; font-size:13px !important;">
                                    <option value="in" selected>🇮🇳 India</option>
                                    <option value="gb">🇬🇧 United Kingdom</option>
                                    <option value="us">🇺🇸 United States</option>
                                    <option value="au">🇦🇺 Australia</option>
                                    <option value="ca">🇨🇦 Canada</option>
                                    <option value="sg">🇸🇬 Singapore</option>
                                    <option value="nz">🇳🇿 New Zealand</option>
                                    <option value="za">🇿🇦 South Africa</option>
                                    <option value="de">🇩🇪 Germany</option>
                                    <option value="fr">🇫🇷 France</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-size:12px; font-weight:700; color:#334155;">
                                    <i class="fa fa-map-marker" style="color:#DC2626;"></i> City / Location <span style="color:#94A3B8; font-weight:400;">(optional)</span>
                                </label>
                                <div style="position:relative;">
                                    <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#DC2626; pointer-events:none; font-size:13px; z-index:2;">
                                        <i class="fa fa-map-marker"></i>
                                    </span>
                                    <input type="text" id="kwLocation" placeholder="e.g. Mumbai, Delhi, Bangalore..." class="form-control" style="padding-left:32px !important; border-radius:8px !important; height:40px !important; font-size:13px !important;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-size:12px; font-weight:700; color:#334155;">
                                    <i class="fa fa-list-ol" style="color:#7C3AED;"></i> Preview Limit
                                </label>
                                <select id="kwLimit" class="form-control" style="border-radius:8px !important; height:40px !important; font-weight:700 !important; font-size:13px !important;">
                                    <option value="5">5 Jobs</option>
                                    <option value="10" selected>10 Jobs</option>
                                    <option value="15">15 Jobs</option>
                                    <option value="20">20 Jobs</option>
                                    <option value="30">30 Jobs</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label style="font-size:12px; font-weight:700; color:#334155;">
                            <i class="fa fa-calendar" style="color:#D97706;"></i> Live Freshness (Max Posting Age)
                        </label>
                        <select id="kwMaxDays" class="form-control" style="border-radius:8px; height:40px; font-size:13px; max-width:260px;">
                            <option value="7">Last 7 Days (Freshest / Active)</option>
                            <option value="14">Last 14 Days</option>
                            <option value="30" selected>Last 30 Days (Recommended)</option>
                            <option value="60">Last 60 Days</option>
                            <option value="90">Last 90 Days</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer" style="background:#F8FAFC; border-top:1px solid #E2E8F0; padding:14px 24px; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:8px; font-weight:600;">Cancel</button>
                    <button type="button" onclick="kwFetchPreview()" id="kwSearchBtn"
                        style="background:#7C3AED; border:none; border-radius:8px; font-weight:700; padding:10px 24px; color:#fff; cursor:pointer; font-size:13.5px; box-shadow:0 2px 6px rgba(124,58,237,0.3); display:inline-flex; align-items:center; gap:6px;">
                        <i class="fa fa-search"></i> Search &amp; Preview Jobs
                    </button>
                </div>
            </div>

            {{-- ════════ STEP 2: Job Preview Cards ════════ --}}
            <div id="kwStep2" style="display:none;">

                {{-- Loading state --}}
                <div id="kwLoading" style="text-align:center; padding:55px 24px; display:none;">
                    <div style="width:50px; height:50px; border:4px solid #EDE9FE; border-top-color:#7C3AED; border-radius:50%; animation:spin 0.7s linear infinite; margin:0 auto 16px;"></div>
                    <div style="font-size:15px; font-weight:700; color:#3730A3;">Searching Live Vacancies for "<span id="kwLoadingKw"></span>"...</div>
                    <div style="font-size:12.5px; color:#64748B; margin-top:6px;">Querying Adzuna API, checking SHA-256 duplicate fingerprints...</div>
                </div>

                {{-- Error state --}}
                <div id="kwError" style="display:none; margin:20px; background:#FEF2F2; border:1px solid #FCA5A5; border-radius:10px; padding:14px 18px; color:#DC2626; font-size:13px; font-weight:600;">
                    <i class="fa fa-exclamation-triangle" style="margin-right:6px;"></i>
                    <span id="kwErrorMsg"></span>
                    <div style="margin-top:10px;">
                        <button type="button" onclick="kwGoBack()" class="btn btn-sm btn-default" style="border-radius:6px; font-weight:600;">
                            <i class="fa fa-arrow-left"></i> Modify Search
                        </button>
                    </div>
                </div>

                {{-- Results bar --}}
                <div id="kwResultsBar" style="display:none; padding:12px 24px; background:#F8FAFC; border-bottom:1px solid #E2E8F0; align-items:center; gap:12px; flex-wrap:wrap;">
                    <label style="display:inline-flex; align-items:center; gap:7px; margin:0; cursor:pointer; font-size:13px; font-weight:700; color:#1E293B;">
                        <input type="checkbox" id="kwSelectAll" onchange="kwToggleAll(this)" style="width:16px; height:16px; accent-color:#7C3AED; cursor:pointer;">
                        Select All
                    </label>
                    <span id="kwResultsSummary" style="font-size:12.5px; color:#64748B; font-weight:500;"></span>
                    
                    <div style="margin-left:auto; display:flex; align-items:center; gap:8px;">
                        <button type="button" onclick="kwSelectOnlyNew()" style="background:#ECFDF5; border:1px solid #A7F3D0; color:#065F46; border-radius:6px; padding:4px 10px; font-size:11.5px; font-weight:700; cursor:pointer;">
                            <i class="fa fa-check"></i> Select Fresh Only
                        </button>
                        <span id="kwSelectedCount" style="background:#7C3AED; color:#fff; font-size:11.5px; font-weight:700; padding:4px 11px; border-radius:20px;">0 selected</span>
                        <button type="button" onclick="kwGoBack()"
                            style="background:#FFFFFF; border:1px solid #CBD5E1; border-radius:6px; padding:5px 12px; font-size:12px; font-weight:600; color:#475569; cursor:pointer;">
                            <i class="fa fa-arrow-left"></i> Change Filters
                        </button>
                    </div>
                </div>

                {{-- Job cards list --}}
                <div id="kwJobList" style="max-height:460px; overflow-y:auto; padding:16px 24px; background:#F8FAFC;">
                </div>

                {{-- Empty state --}}
                <div id="kwEmpty" style="display:none; text-align:center; padding:48px 24px; color:#64748B;">
                    <div style="width:56px; height:56px; background:#EDE9FE; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto 14px; color:#7C3AED;"><i class="fa fa-search"></i></div>
                    <h4 style="font-weight:800; color:#0F172A; margin-bottom:6px;">No Live Postings Found</h4>
                    <p style="font-size:13px; max-width:380px; margin:0 auto 16px;">We could not find matching active vacancies. Try broadening your keyword, country, or increasing the freshness days.</p>
                    <button type="button" onclick="kwGoBack()" style="background:#7C3AED; border:none; border-radius:8px; padding:8px 20px; color:#fff; font-weight:700; cursor:pointer;"><i class="fa fa-arrow-left"></i> Modify Search</button>
                </div>

                {{-- Success state --}}
                <div id="kwSuccess" style="display:none; margin:20px; background:#ECFDF5; border:1px solid #A7F3D0; border-radius:10px; padding:18px 22px; color:#065F46; font-size:13.5px; font-weight:600;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <i class="fa fa-check-circle" style="font-size:22px; color:#059669;"></i>
                        <div>
                            <div id="kwSuccessMsg" style="font-size:14px; font-weight:800; color:#065F46;"></div>
                            <div style="font-size:12px; color:#047857; margin-top:2px;">Redirecting you to Raw Ingestion Queue so you can Enrich with AI and Publish Live...</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" id="kwFooterStep2" style="background:#FFFFFF; border-top:1px solid #E2E8F0; padding:14px 24px; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" class="btn btn-default" onclick="kwGoBack()" style="border-radius:8px; font-weight:600;">
                        <i class="fa fa-arrow-left"></i> Back
                    </button>
                    <button type="button" id="kwAddBtn" onclick="kwAddSelected()" disabled
                        style="background:#7C3AED; border:none; border-radius:8px; font-weight:700; padding:10px 24px; color:#fff; cursor:not-allowed; opacity:0.5; font-size:13.5px; box-shadow:0 2px 6px rgba(124,58,237,0.3); display:inline-flex; align-items:center; gap:6px;">
                        <i class="fa fa-plus-circle"></i> Add Selected Jobs to Raw Queue
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
{{-- ═══ END keywordSearchModal ═══ --}}

<div class="modal fade" id="viewRawJobModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <div class="modal-header" style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; padding: 18px 24px;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="font-weight: 800; color: #0F172A; margin: 0;">
                    <i class="fa fa-file-text-o" style="color: #2563EB; margin-right: 6px;"></i> Raw Job Details
                </h4>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <div style="margin-bottom: 18px;">
                    <h3 id="viewJobTitle" style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;"></h3>
                    <div style="font-size: 14px; font-weight: 600; color: #2563EB;" id="viewJobCompany"></div>
                    <div style="font-size: 13px; color: #64748B; margin-top: 4px;" id="viewJobMeta"></div>
                </div>

                <div class="form-group">
                    <label><strong>Fingerprint Hash (SHA-256):</strong></label>
                    <div id="viewJobHash" class="slug-chip" style="display: block; padding: 8px 12px; font-size: 12px; word-break: break-all; max-width: 100%;"></div>
                </div>

                <div class="form-group" id="viewSourceUrlGroup">
                    <label><strong>Source URL:</strong></label>
                    <div>
                        <a id="viewJobSourceUrl" href="#" target="_blank" rel="noopener noreferrer" style="color: #2563EB; font-weight: 600; word-break: break-all;"></a>
                    </div>
                </div>

                <div class="form-group">
                    <label><strong>Raw Description:</strong></label>
                    <div id="viewJobDescription" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 16px; font-size: 13px; color: #334155; line-height: 1.6; max-height: 280px; overflow-y: auto; white-space: pre-wrap;"></div>
                </div>
            </div>
            <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 14px 24px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Edit Raw Job Details -->
<div class="modal fade" id="editRawJobModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <div class="modal-header" style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; padding: 18px 24px;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="font-weight: 800; color: #0F172A; margin: 0;">
                    <i class="fa fa-pencil" style="color: #2563EB; margin-right: 6px;"></i> Edit Job Details
                </h4>
            </div>
            <form id="editRawJobForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group">
                        <label><strong>Job Title <span class="text-danger">*</span></strong></label>
                        <input type="text" name="title" id="editJobTitle" class="form-control" required style="border-radius: 8px; height: 40px;">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Company / Employer Name</strong></label>
                                <input type="text" name="company" id="editJobCompany" class="form-control" style="border-radius: 8px; height: 40px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Location</strong></label>
                                <input type="text" name="location" id="editJobLocation" class="form-control" style="border-radius: 8px; height: 40px;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><strong>Job Description <span class="text-danger">*</span></strong></label>
                        <textarea name="description" id="editJobDescription" class="form-control" rows="8" required style="border-radius: 8px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 14px 24px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; font-weight: 700; padding: 8px 18px;">
                        <i class="fa fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.kw-job-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    padding: 16px 18px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.kw-job-card:hover {
    border-color: #C4B5FD;
    box-shadow: 0 4px 12px rgba(124,58,237,0.08);
}
.kw-job-card.is-selected {
    border-color: #7C3AED;
    background: #FAF8FF;
}
.kw-job-card.is-duplicate {
    opacity: 0.75;
    background: #F8FAFC;
    border-color: #E2E8F0;
}
</style>

<script>
    window.kwPreviewJobs = [];

    function openViewModal(raw) {
        document.getElementById('viewJobTitle').innerText = raw.raw_title || 'Untitled';
        document.getElementById('viewJobCompany').innerText = raw.raw_company || 'Featured Employer';
        document.getElementById('viewJobMeta').innerHTML = '<i class="fa fa-map-marker text-danger"></i> ' + (raw.raw_location || 'Nagpur') + ' &nbsp;|&nbsp; <i class="fa fa-tag text-muted"></i> Source: ' + (raw.source_name || 'Feed');
        document.getElementById('viewJobHash').innerText = raw.content_hash || 'N/A';
        
        var sourceUrlGroup = document.getElementById('viewSourceUrlGroup');
        var sourceUrlLink = document.getElementById('viewJobSourceUrl');
        if (raw.source_url) {
            sourceUrlGroup.style.display = 'block';
            sourceUrlLink.href = raw.source_url;
            sourceUrlLink.innerText = raw.source_url;
        } else {
            sourceUrlGroup.style.display = 'none';
        }

        document.getElementById('viewJobDescription').innerText = raw.raw_description || 'No description provided.';
        $('#viewRawJobModal').modal('show');
    }

    function openEditModal(raw) {
        document.getElementById('editJobTitle').value = raw.raw_title || '';
        document.getElementById('editJobCompany').value = raw.raw_company || '';
        document.getElementById('editJobLocation').value = raw.raw_location || '';
        document.getElementById('editJobDescription').value = raw.raw_description || '';
        
        var updateUrl = "{{ url('admin/ai-job-pipeline/raw') }}/" + raw.id + "/update";
        document.getElementById('editRawJobForm').action = updateUrl;
        $('#editRawJobModal').modal('show');
    }

    // ── Table Bulk Selection & Uniform Compatibility ──
    $(document).ready(function() {
        // 1. Raw Tab Select All Click & Change
        $(document).on('click change', '#selectAllRaw', function() {
            var isChecked = $(this).is(':checked');
            $('.raw-checkbox').prop('checked', isChecked);
            $('.raw-checkbox').each(function() {
                var parentSpan = $(this).parent('span');
                if (isChecked) {
                    parentSpan.addClass('checked');
                } else {
                    parentSpan.removeClass('checked');
                }
            });
            if (window.jQuery && $.uniform) {
                $.uniform.update($('.raw-checkbox'));
            }
            updateSelectedCount();
        });

        // 2. Enriched Tab Select All Click & Change
        $(document).on('click change', '#selectAllEnriched', function() {
            var isChecked = $(this).is(':checked');
            $('.enriched-checkbox').prop('checked', isChecked);
            $('.enriched-checkbox').each(function() {
                var parentSpan = $(this).parent('span');
                if (isChecked) {
                    parentSpan.addClass('checked');
                } else {
                    parentSpan.removeClass('checked');
                }
            });
            if (window.jQuery && $.uniform) {
                $.uniform.update($('.enriched-checkbox'));
            }
            updateSelectedCountEnriched();
        });

        // 3. Published Tab Select All Click & Change
        $(document).on('click change', '#selectAllPublished', function() {
            var isChecked = $(this).is(':checked');
            $('.published-checkbox').prop('checked', isChecked);
            $('.published-checkbox').each(function() {
                var parentSpan = $(this).parent('span');
                if (isChecked) {
                    parentSpan.addClass('checked');
                } else {
                    parentSpan.removeClass('checked');
                }
            });
            if (window.jQuery && $.uniform) {
                $.uniform.update($('.published-checkbox'));
            }
            updateSelectedCountPublished();
        });

        // Individual Checkbox Listeners
        $(document).on('click change', '.raw-checkbox', function() {
            updateSelectedCount();
        });
        $(document).on('click change', '.enriched-checkbox', function() {
            updateSelectedCountEnriched();
        });
        $(document).on('click change', '.published-checkbox', function() {
            updateSelectedCountPublished();
        });
    });

    function updateSelectedCount() {
        var selected = $('.raw-checkbox:checked').length;
        var total = $('.raw-checkbox').length;
        $('#selectedCountBadge').text(selected + ' selected');
        
        if (selected > 0) {
            $('#bulkDeleteBtn').show().prop('disabled', false).html('<i class="fa fa-trash"></i> Delete Selected (' + selected + ')');
        } else {
            $('#bulkDeleteBtn').hide().prop('disabled', true);
        }

        var allChecked = total > 0 && selected === total;
        $('#selectAllRaw').prop('checked', allChecked);
        var selectAllSpan = $('#selectAllRaw').parent('span');
        if (allChecked) {
            selectAllSpan.addClass('checked');
        } else {
            selectAllSpan.removeClass('checked');
        }
        if (window.jQuery && $.uniform) {
            $.uniform.update($('#selectAllRaw'));
        }
    }

    function updateSelectedCountEnriched() {
        var selected = $('.enriched-checkbox:checked').length;
        var total = $('.enriched-checkbox').length;
        $('#selectedCountBadgeEnriched').text(selected + ' selected');
        
        if (selected > 0) {
            $('#bulkDeleteBtnEnriched').show().prop('disabled', false).html('<i class="fa fa-trash"></i> Delete Selected (' + selected + ')');
        } else {
            $('#bulkDeleteBtnEnriched').hide().prop('disabled', true);
        }

        var allChecked = total > 0 && selected === total;
        $('#selectAllEnriched').prop('checked', allChecked);
        var selectAllSpan = $('#selectAllEnriched').parent('span');
        if (allChecked) {
            selectAllSpan.addClass('checked');
        } else {
            selectAllSpan.removeClass('checked');
        }
        if (window.jQuery && $.uniform) {
            $.uniform.update($('#selectAllEnriched'));
        }
    }

    function updateSelectedCountPublished() {
        var selected = $('.published-checkbox:checked').length;
        var total = $('.published-checkbox').length;
        $('#selectedCountBadgePublished').text(selected + ' selected');
        
        if (selected > 0) {
            $('#bulkDeleteBtnPublished').show().prop('disabled', false).html('<i class="fa fa-trash"></i> Delete Selected (' + selected + ')');
        } else {
            $('#bulkDeleteBtnPublished').hide().prop('disabled', true);
        }

        var allChecked = total > 0 && selected === total;
        $('#selectAllPublished').prop('checked', allChecked);
        var selectAllSpan = $('#selectAllPublished').parent('span');
        if (allChecked) {
            selectAllSpan.addClass('checked');
        } else {
            selectAllSpan.removeClass('checked');
        }
        if (window.jQuery && $.uniform) {
            $.uniform.update($('#selectAllPublished'));
        }
    }

    function submitBulkDeleteRaw() {
        var checked = $('.raw-checkbox:checked');
        if (checked.length === 0) return;
        if (!confirm('Are you sure you want to delete the ' + checked.length + ' selected raw jobs?')) return;

        var container = $('#bulkDeleteRawInputs');
        container.empty();
        checked.each(function() {
            container.append('<input type="hidden" name="selected_ids[]" value="' + $(this).val() + '">');
        });
        $('#bulkDeleteRawForm').submit();
    }

    function submitBulkDeleteEnriched() {
        var checked = $('.enriched-checkbox:checked');
        if (checked.length === 0) return;
        if (!confirm('Are you sure you want to delete the ' + checked.length + ' selected enriched jobs?')) return;

        var container = $('#bulkDeleteEnrichedInputs');
        container.empty();
        checked.each(function() {
            container.append('<input type="hidden" name="selected_ids[]" value="' + $(this).val() + '">');
        });
        $('#bulkDeleteEnrichedForm').submit();
    }

    function submitBulkDeletePublished() {
        var checked = $('.published-checkbox:checked');
        if (checked.length === 0) return;
        if (!confirm('Are you sure you want to delete and unpublish the ' + checked.length + ' selected jobs?')) return;

        var container = $('#bulkDeletePublishedInputs');
        container.empty();
        checked.each(function() {
            container.append('<input type="hidden" name="selected_ids[]" value="' + $(this).val() + '">');
        });
        $('#bulkDeletePublishedForm').submit();
    }

    function adjustJobLimit(change) {
        var input = document.getElementById('daily_fetch_limit_input');
        if (input) {
            var current = parseInt(input.value, 10) || 5;
            var next = current + change;
            if (next < 1) next = 1;
            if (next > 50) next = 50;
            input.value = next;
        }
    }

    function toggleAutoPublish() {
        var input = document.getElementById('auto_publish_input');
        var toggle = document.getElementById('auto_publish_toggle');
        var label = document.getElementById('auto_publish_label');
        if (!input || !toggle || !label) return;

        var isCurrentlyOn = input.value === '1';
        if (isCurrentlyOn) {
            input.value = '0';
            toggle.classList.remove('is-active');
            label.innerText = 'Hold in Queue';
            label.style.color = '#64748B';
        } else {
            input.value = '1';
            toggle.classList.add('is-active');
            label.innerText = 'Directly Live on /jobs';
            label.style.color = '#03855c';
        }
    }

    // ── Keyword Search 2-Step Preview System ──
    function setKeyword(kw) {
        var input = document.getElementById('kwKeyword');
        if (input) {
            input.value = kw;
            input.focus();
        }
        document.querySelectorAll('.kw-chip-btn').forEach(function(btn) {
            if (btn.textContent.trim() === kw) {
                btn.style.background  = '#7C3AED';
                btn.style.color       = '#fff';
                btn.style.borderColor = '#7C3AED';
            } else {
                btn.style.background  = '#F5F3FF';
                btn.style.color       = '#5B21B6';
                btn.style.borderColor = '#C4B5FD';
            }
        });
    }

    function kwFetchPreview() {
        var keyword = (document.getElementById('kwKeyword').value || '').trim();
        if (!keyword) {
            alert('Please enter or select a job keyword / title.');
            document.getElementById('kwKeyword').focus();
            return;
        }

        var country  = document.getElementById('kwCountry').value || 'in';
        var location = (document.getElementById('kwLocation').value || '').trim();
        var limit    = parseInt(document.getElementById('kwLimit').value, 10) || 10;
        var maxDays  = parseInt(document.getElementById('kwMaxDays').value, 10) || 30;

        // Transition UI to Step 2
        document.getElementById('kwStep1').style.display = 'none';
        document.getElementById('kwStep2').style.display = 'block';

        document.getElementById('kwLoading').style.display = 'block';
        document.getElementById('kwLoadingKw').innerText = keyword;
        document.getElementById('kwError').style.display = 'none';
        document.getElementById('kwEmpty').style.display = 'none';
        document.getElementById('kwSuccess').style.display = 'none';
        document.getElementById('kwResultsBar').style.display = 'none';
        document.getElementById('kwJobList').innerHTML = '';
        document.getElementById('kwJobList').style.display = 'none';
        document.getElementById('kwFooterStep2').style.display = 'none';

        var postData = {
            _token: '{{ csrf_token() }}',
            keyword: keyword,
            country: country,
            location: location,
            limit: limit,
            max_days: maxDays
        };

        $.ajax({
            url: "{{ route('admin.ai.pipeline.keyword_preview') }}",
            type: "POST",
            data: postData,
            dataType: "json",
            success: function(res) {
                document.getElementById('kwLoading').style.display = 'none';

                if (!res.success) {
                    document.getElementById('kwError').style.display = 'block';
                    document.getElementById('kwErrorMsg').innerText = res.message || 'Adzuna API request failed.';
                    return;
                }

                if (!res.jobs || res.jobs.length === 0) {
                    document.getElementById('kwEmpty').style.display = 'block';
                    return;
                }

                window.kwPreviewJobs = res.jobs;

                // Render Results Bar
                document.getElementById('kwResultsBar').style.display = 'flex';
                document.getElementById('kwResultsSummary').innerHTML =
                    'Found <strong style="color:#0F172A;">' + res.total + ' jobs</strong> (' +
                    '<span style="color:#059669; font-weight:700;">' + res.new + ' fresh</span>' +
                    (res.total - res.new > 0 ? ', <span style="color:#D97706;">' + (res.total - res.new) + ' already ingested</span>' : '') + ')';

                // Render Job Cards
                var html = '';
                res.jobs.forEach(function(job, idx) {
                    var isDup = job.is_duplicate;
                    var checkedAttr = isDup ? '' : 'checked';
                    var cardClass = 'kw-job-card' + (isDup ? ' is-duplicate' : ' is-selected');

                    html += '<div class="' + cardClass + '" id="kwCard_' + idx + '">';
                    html += '  <div style="display:flex; align-items:flex-start; gap:12px;">';
                    
                    // Checkbox
                    html += '    <div style="padding-top:2px;">';
                    html += '      <input type="checkbox" class="kw-job-cb" data-idx="' + idx + '" ' + checkedAttr + ' onchange="updateKwCardState(' + idx + ')" style="width:18px; height:18px; accent-color:#7C3AED; cursor:pointer;">';
                    html += '    </div>';

                    // Content
                    html += '    <div style="flex:1; min-width:0;">';
                    
                    // Title & Badge row
                    html += '      <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap;">';
                    html += '        <h4 style="font-size:15px; font-weight:800; color:#0F172A; margin:0; line-height:1.3;">' + escapeHtml(job.title) + '</h4>';
                    if (isDup) {
                        html += '        <span style="background:#FEF3C7; color:#92400E; border:1px solid #FDE68A; font-size:11px; font-weight:700; padding:2px 8px; border-radius:12px;"><i class="fa fa-info-circle"></i> Already in Queue</span>';
                    } else {
                        html += '        <span style="background:#ECFDF5; color:#065F46; border:1px solid #A7F3D0; font-size:11px; font-weight:700; padding:2px 8px; border-radius:12px;"><i class="fa fa-sparkles"></i> ✨ Fresh Job</span>';
                    }
                    html += '      </div>';

                    // Meta Row: Company, Location, Date, Salary, Contract
                    html += '      <div style="font-size:12.5px; color:#64748B; margin:6px 0 10px 0; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">';
                    html += '        <span style="font-weight:700; color:#2563EB;"><i class="fa fa-building" style="margin-right:3px;"></i> ' + escapeHtml(job.company) + '</span>';
                    html += '        <span>•</span>';
                    html += '        <span><i class="fa fa-map-marker text-danger" style="margin-right:3px;"></i> ' + escapeHtml(job.location) + '</span>';
                    if (job.posted_label) {
                        html += '        <span>•</span>';
                        html += '        <span style="color:#059669; font-weight:600;"><i class="fa fa-calendar-check-o" style="margin-right:3px;"></i> Live: ' + escapeHtml(job.posted_label) + '</span>';
                    }
                    if (job.salary) {
                        html += '        <span>•</span>';
                        html += '        <span style="background:#EFF6FF; color:#1D4ED8; font-weight:700; padding:1px 6px; border-radius:4px; font-size:11px;"><i class="fa fa-money"></i> ' + escapeHtml(job.salary) + '</span>';
                    }
                    if (job.contract_time) {
                        html += '        <span style="background:#F1F5F9; color:#475569; font-weight:600; padding:1px 6px; border-radius:4px; font-size:11px; text-transform:capitalize;">' + escapeHtml(job.contract_time.replace('_', ' ')) + '</span>';
                    }
                    html += '      </div>';

                    // Description & Requirements Box
                    var desc = job.description || 'No description provided.';
                    var isLong = desc.length > 220;
                    var shortDesc = isLong ? desc.substring(0, 220) + '...' : desc;

                    html += '      <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:8px; padding:10px 14px; font-size:12.5px; color:#334155; line-height:1.55;">';
                    html += '        <div style="font-size:11px; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;"><i class="fa fa-file-text-o"></i> Description &amp; Requirements:</div>';
                    html += '        <div id="kwDescShort_' + idx + '">' + escapeHtml(shortDesc) + '</div>';
                    if (isLong) {
                        html += '        <div id="kwDescFull_' + idx + '" style="display:none; white-space:pre-wrap;">' + escapeHtml(desc) + '</div>';
                        html += '        <a href="javascript:void(0)" onclick="toggleKwDesc(' + idx + ')" id="kwDescToggle_' + idx + '" style="font-size:11px; font-weight:700; color:#7C3AED; text-decoration:underline; margin-top:4px; display:inline-block;">Show full details ▾</a>';
                    }
                    html += '      </div>';

                    // Live Source Link
                    if (job.source_url) {
                        html += '      <div style="margin-top:8px; font-size:11.5px;">';
                        html += '        <a href="' + job.source_url + '" target="_blank" rel="noopener noreferrer" style="color:#7C3AED; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">';
                        html += '          <i class="fa fa-external-link"></i> View Original Live Posting on Adzuna ↗';
                        html += '        </a>';
                        html += '      </div>';
                    }

                    html += '    </div>';
                    html += '  </div>';
                    html += '</div>';
                });

                document.getElementById('kwJobList').innerHTML = html;
                document.getElementById('kwJobList').style.display = 'block';
                document.getElementById('kwFooterStep2').style.display = 'flex';

                updateKwCount();
            },
            error: function(xhr) {
                document.getElementById('kwLoading').style.display = 'none';
                document.getElementById('kwError').style.display = 'block';
                document.getElementById('kwErrorMsg').innerText = 'Network error or server exception. Please check connection and try again.';
            }
        });
    }

    function updateKwCardState(idx) {
        var cb = document.querySelector('.kw-job-cb[data-idx="' + idx + '"]');
        var card = document.getElementById('kwCard_' + idx);
        if (cb && card) {
            if (cb.checked) {
                card.classList.add('is-selected');
            } else {
                card.classList.remove('is-selected');
            }
        }
        updateKwCount();
    }

    function toggleKwDesc(idx) {
        var shortDiv = document.getElementById('kwDescShort_' + idx);
        var fullDiv = document.getElementById('kwDescFull_' + idx);
        var toggle = document.getElementById('kwDescToggle_' + idx);
        if (!shortDiv || !fullDiv || !toggle) return;

        if (fullDiv.style.display === 'none') {
            fullDiv.style.display = 'block';
            shortDiv.style.display = 'none';
            toggle.innerText = 'Show less ▴';
        } else {
            fullDiv.style.display = 'none';
            shortDiv.style.display = 'block';
            toggle.innerText = 'Show full details ▾';
        }
    }

    function kwToggleAll(master) {
        var isChecked = master.checked;
        document.querySelectorAll('.kw-job-cb').forEach(function(cb) {
            cb.checked = isChecked;
            var idx = cb.getAttribute('data-idx');
            var card = document.getElementById('kwCard_' + idx);
            if (card) {
                if (isChecked) {
                    card.classList.add('is-selected');
                } else {
                    card.classList.remove('is-selected');
                }
            }
        });
        updateKwCount();
    }

    function kwSelectOnlyNew() {
        document.querySelectorAll('.kw-job-cb').forEach(function(cb) {
            var idx = parseInt(cb.getAttribute('data-idx'), 10);
            var job = window.kwPreviewJobs[idx];
            var isNew = job && !job.is_duplicate;
            cb.checked = isNew;
            var card = document.getElementById('kwCard_' + idx);
            if (card) {
                if (isNew) {
                    card.classList.add('is-selected');
                } else {
                    card.classList.remove('is-selected');
                }
            }
        });
        updateKwCount();
    }

    function updateKwCount() {
        var selectedCbs = document.querySelectorAll('.kw-job-cb:checked');
        var count = selectedCbs.length;
        var total = document.querySelectorAll('.kw-job-cb').length;

        document.getElementById('kwSelectedCount').innerText = count + ' selected';

        var addBtn = document.getElementById('kwAddBtn');
        if (count > 0) {
            addBtn.disabled = false;
            addBtn.style.cursor = 'pointer';
            addBtn.style.opacity = '1';
            addBtn.innerHTML = '<i class="fa fa-plus-circle"></i> Add Selected (' + count + ') Jobs to Raw Queue';
        } else {
            addBtn.disabled = true;
            addBtn.style.cursor = 'not-allowed';
            addBtn.style.opacity = '0.5';
            addBtn.innerHTML = '<i class="fa fa-plus-circle"></i> Select Jobs to Ingest';
        }

        var master = document.getElementById('kwSelectAll');
        if (master) {
            master.checked = total > 0 && count === total;
        }
    }

    function kwGoBack() {
        document.getElementById('kwStep2').style.display = 'none';
        document.getElementById('kwStep1').style.display = 'block';
    }

    function kwAddSelected() {
        var selectedJobs = [];
        document.querySelectorAll('.kw-job-cb:checked').forEach(function(cb) {
            var idx = parseInt(cb.getAttribute('data-idx'), 10);
            if (window.kwPreviewJobs[idx]) {
                selectedJobs.push(window.kwPreviewJobs[idx]);
            }
        });

        if (selectedJobs.length === 0) {
            alert('Please select at least 1 job to add.');
            return;
        }

        var addBtn = document.getElementById('kwAddBtn');
        addBtn.disabled = true;
        addBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Adding ' + selectedJobs.length + ' Jobs to Raw Queue...';

        $.ajax({
            url: "{{ route('admin.ai.pipeline.add_selected') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                jobs: selectedJobs
            },
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    document.getElementById('kwResultsBar').style.display = 'none';
                    document.getElementById('kwJobList').style.display = 'none';
                    document.getElementById('kwFooterStep2').style.display = 'none';
                    document.getElementById('kwSuccess').style.display = 'block';
                    document.getElementById('kwSuccessMsg').innerText = '✓ ' + (res.message || 'Successfully added jobs to Raw Queue!');
                    
                    // Redirect to Raw Queue tab after 1.2s
                    setTimeout(function() {
                        window.location.href = "{{ route('admin.ai.pipeline', ['tab' => 'raw']) }}";
                    }, 1200);
                } else {
                    addBtn.disabled = false;
                    addBtn.innerHTML = '<i class="fa fa-plus-circle"></i> Retry Adding Selected Jobs';
                    alert('Error: ' + (res.message || 'Could not add jobs.'));
                }
            },
            error: function(xhr) {
                addBtn.disabled = false;
                addBtn.innerHTML = '<i class="fa fa-plus-circle"></i> Retry Adding Selected Jobs';
                alert('Server error while adding jobs. Please try again.');
            }
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Reset chips on modal close
    $(document).on('hidden.bs.modal', '#keywordSearchModal', function () {
        document.querySelectorAll('.kw-chip-btn').forEach(function(btn) {
            btn.style.background  = '#F5F3FF';
            btn.style.color       = '#5B21B6';
            btn.style.borderColor = '#C4B5FD';
        });
        kwGoBack();
    });
</script>
@endpush

