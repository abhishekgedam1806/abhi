@extends('admin.layouts.admin_layout')

@section('content')
<style>
    /* Executive AI Dashboard Styles */
    .ai-hero-card {
        background: #0F172A;
        border-radius: 12px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 25px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15), 0 8px 10px -6px rgba(15, 23, 42, 0.1);
        border: 1px solid #334155;
        position: relative;
        overflow: hidden;
    }
    .ai-hero-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: none;
        border-radius: 50%;
        pointer-events: none;
    }
    .ai-providers-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .ai-providers-table th {
        background: #F8FAFC !important;
        color: #475569 !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 14px 16px !important;
        border-top: none !important;
        border-bottom: 1px solid #E2E8F0 !important;
    }
    .ai-providers-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #F1F5F9;
        color: #1E293B;
    }
    .ai-providers-table tbody tr {
        transition: background-color 0.15s ease;
    }
    .ai-providers-table tbody tr:hover {
        background-color: #F8FAFC !important;
    }
    .ai-providers-table tbody tr.is-active-row {
        background-color: #F0F7FF !important;
        border-left: 3px solid #2563EB;
    }
    
    /* Vendor Icon Avatars */
    .vendor-avatar {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .vendor-gemini { background: #E8F0FE; color: #1A73E8; border: 1px solid #C2E7FF; }
    .vendor-openai { background: #ECFDF5; color: #03855c; border: 1px solid #A7F3D0; }
    .vendor-claude { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
    .vendor-grok { background: #F1F5F9; color: #0F172A; border: 1px solid #E2E8F0; }
    .vendor-glm { background: #FAF5FF; color: #7C3AED; border: 1px solid #E9D5FF; }
    .vendor-azure_openai { background: #F0F9FF; color: #0284C7; border: 1px solid #BAE6FD; }

    /* Model Pill */
    .model-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 10px;
        background: #F1F5F9;
        color: #0F172A;
        border: 1px solid #CBD5E1;
        border-radius: 6px;
        white-space: nowrap;
    }

    /* API Key Pill */
    .api-key-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        color: #475569;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    /* Health Status Badges */
    .health-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
    .health-operational { background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }
    .health-inactive { background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; }
    .health-error { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }

    .pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }
    .pulse-dot-green {
        background-color: #10B981;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
    }
    .pulse-dot-grey { background-color: #94A3B8; }
    .pulse-dot-red {
        background-color: #EF4444;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.25);
    }

    /* Action Buttons */
    .btn-action-test {
        background: #FFFFFF;
        border: 1px solid #CBD5E1;
        color: #334155;
        font-weight: 600;
        font-size: 12px;
        border-radius: 6px;
        padding: 6px 12px;
        transition: all 0.15s ease;
    }
    .btn-action-test:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
        color: #0F172A;
    }
    .btn-action-active {
        background: #2563EB;
        border: 1px solid #2563EB;
        color: #FFFFFF;
        font-weight: 600;
        font-size: 12px;
        border-radius: 6px;
        padding: 6px 14px;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        transition: all 0.15s ease;
    }
    .btn-action-active:hover {
        background: #1D4ED8;
        border-color: #1D4ED8;
        color: #FFFFFF;
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
    }
    .btn-action-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        color: #475569;
        transition: all 0.15s ease;
    }
    .btn-action-icon:hover {
        background: #F1F5F9;
        color: #0F172A;
        border-color: #CBD5E1;
    }
    .btn-action-delete {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #FECACA;
        background: #FEF2F2;
        color: #DC2626;
        transition: all 0.15s ease;
    }
    .btn-action-delete:hover {
        background: #DC2626;
        color: #FFFFFF;
        border-color: #DC2626;
    }
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <!-- Breadcrumbs & Header -->
        <div class="page-bar" style="margin-bottom: 20px; background: #fff; padding: 12px 18px; border-radius: 8px; border: 1px solid #E2E8F0;">
            <ul class="page-breadcrumb" style="margin: 0;">
                <li><a href="{{ route('admin.home') }}" style="color: #64748B;">Dashboard</a><i class="fa fa-angle-right" style="margin: 0 8px; color: #CBD5E1;"></i></li>
                <li><a href="javascript:;" style="color: #64748B;">AI Engine</a><i class="fa fa-angle-right" style="margin: 0 8px; color: #CBD5E1;"></i></li>
                <li><span style="font-weight: 600; color: #0F172A;">AI Providers</span></li>
            </ul>
            <div class="page-toolbar" style="margin: 0;">
                <a href="{{ route('admin.ai.cost_performance') }}" class="btn btn-default btn-sm" style="border-radius: 6px; margin-right: 8px; font-weight: 600; border: 1px solid #CBD5E1; color: #334155;">
                    <i class="fa fa-line-chart" style="color: #10B981;"></i> View Cost & Performance
                </a>
                <a href="{{ route('admin.ai.providers.create') }}" class="btn btn-primary btn-sm" style="border-radius: 6px; font-weight: 600; background-color: #2563EB; border-color: #2563EB; box-shadow: 0 2px 4px rgba(37,99,235,0.25);">
                    <i class="fa fa-plus"></i> Add AI Provider
                </a>
            </div>
        </div>

        @include('flash::message')

        <!-- Active Provider Banner -->
        <div class="ai-hero-card">
            <div class="row" style="align-items: center;">
                <div class="col-md-8">
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <span style="background: rgba(37, 99, 235, 0.25); color: #93C5FD; border: 1px solid rgba(59, 130, 246, 0.5); padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fa fa-check-circle" style="color: #60A5FA;"></i> Primary Active AI Engine
                        </span>
                    </div>
                    @if($activeProvider)
                        <h2 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.3px;">
                            {{ $activeProvider->name }}
                        </h2>
                        <div style="color: #94A3B8; font-size: 13px; display: flex; flex-wrap: wrap; align-items: center; gap: 14px;">
                            <span><strong style="color: #E2E8F0;">Provider:</strong> {{ ucfirst($activeProvider->provider_type) }}</span>
                            <span><strong style="color: #E2E8F0;">Model:</strong> <code style="background: #334155; color: #38BDF8; padding: 2px 8px; border-radius: 4px; font-family: monospace;">{{ $activeProvider->model }}</code></span>
                            <span><strong style="color: #E2E8F0;">Key:</strong> <span class="api-key-pill" style="background: #334155; border-color: #475569; color: #CBD5E1;"><i class="fa fa-lock" style="font-size: 11px;"></i> {{ $activeProvider->masked_api_key }}</span></span>
                            <span><strong style="color: #E2E8F0;">Timeout:</strong> {{ $activeProvider->timeout_sec }}s</span>
                        </div>
                    @else
                        <h2 style="margin: 0 0 6px 0; font-size: 22px; font-weight: 700; color: #F87171;">
                            No Active AI Provider Configured
                        </h2>
                        <p style="margin: 0; color: #CBD5E1; font-size: 13px;">
                            Please add or select an AI provider below and click <strong>"Set as Active"</strong> to enable AI job matching, resume analysis, and SEO optimization.
                        </p>
                    @endif
                </div>
                <div class="col-md-4 text-right" style="margin-top: 15px;">
                    @if($activeProvider)
                        <button type="button" class="btn btn-sm btn-info btn-test-conn" data-id="{{ $activeProvider->id }}" data-name="{{ $activeProvider->name }}" style="border-radius: 6px; font-weight: 600; padding: 8px 16px; background: #0284C7; border-color: #0284C7;">
                            <i class="fa fa-bolt" style="color: #FDE047;"></i> Test Connection
                        </button>
                        <a href="{{ route('admin.ai.providers.edit', $activeProvider->id) }}" class="btn btn-sm btn-outline-light" style="border-radius: 6px; margin-left: 6px; border: 1px solid #475569; color: #fff; padding: 8px 14px; background: rgba(255,255,255,0.05);">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Providers List Section -->
        <div class="portlet light bordered" style="border-radius: 12px; border: 1px solid #E2E8F0; box-shadow: 0 1px 3px rgba(0,0,0,0.03); overflow: hidden; padding: 0;">
            <div class="portlet-title" style="padding: 16px 20px; margin-bottom: 0; border-bottom: 1px solid #E2E8F0; background: #FFFFFF;">
                <div class="caption font-dark" style="margin: 0;">
                    <i class="fa fa-server" style="color: #2563EB;"></i>
                    <span class="caption-subject bold" style="font-size: 15px; color: #0F172A;">Configured AI Providers ({{ $providers->count() }})</span>
                    <span class="help-inline" style="font-size: 12px; color: #64748B; margin-left: 12px;">
                        Add multiple providers and switch seamlessly. Adding a new provider will NOT automatically replace the active provider.
                    </span>
                </div>
            </div>
            <div class="portlet-body" style="padding: 0;">
                @if($providers->count() > 0)
                    <div class="table-responsive" style="margin: 0;">
                        <table class="ai-providers-table">
                            <thead>
                                <tr>
                                    <th style="width: 28%;">Provider & Configuration</th>
                                    <th style="width: 18%;">Model</th>
                                    <th style="width: 17%;">API Key</th>
                                    <th style="width: 14%;">Health & Status</th>
                                    <th style="width: 10%;">Last Tested</th>
                                    <th style="width: 13%; text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($providers as $prov)
                                <tr class="{{ $prov->is_default ? 'is-active-row' : '' }}">
                                    <!-- Column 1: Provider Avatar & Info -->
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div class="vendor-avatar vendor-{{ $prov->provider_type }}">
                                                @if($prov->provider_type == 'gemini')
                                                    <i class="fa fa-google"></i>
                                                @elseif($prov->provider_type == 'openai')
                                                    <i class="fa fa-cube"></i>
                                                @elseif($prov->provider_type == 'claude')
                                                    <i class="fa fa-bolt"></i>
                                                @elseif($prov->provider_type == 'grok')
                                                    <i class="fa fa-rocket"></i>
                                                @elseif($prov->provider_type == 'glm')
                                                    <i class="fa fa-shield"></i>
                                                @else
                                                    <i class="fa fa-microchip"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                                    <span style="font-weight: 700; font-size: 14px; color: #0F172A;">
                                                        {{ $prov->name }}
                                                    </span>
                                                    @if($prov->is_default)
                                                        <span style="background: #2563EB; color: #fff; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; letter-spacing: 0.5px;">ACTIVE</span>
                                                    @endif
                                                </div>
                                                <div style="margin-top: 3px; display: flex; align-items: center; gap: 8px; font-size: 12px; color: #64748B;">
                                                    <span style="font-weight: 600; text-transform: uppercase; font-size: 10px; color: #475569;">
                                                        {{ ucfirst(str_replace('_', ' ', $prov->provider_type)) }}
                                                    </span>
                                                    @if($prov->base_url)
                                                        <span style="color: #94A3B8; font-size: 11px;" title="{{ $prov->base_url }}">
                                                            <i class="fa fa-link"></i> Custom URL
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Column 2: Model Pill (No line break) -->
                                    <td>
                                        <div class="model-badge">
                                            <i class="fa fa-microchip" style="color: #64748B; font-size: 11px;"></i>
                                            <span>{{ $prov->model }}</span>
                                        </div>
                                    </td>

                                    <!-- Column 3: Masked API Key -->
                                    <td>
                                        <div class="api-key-pill">
                                            <i class="fa fa-lock" style="color: #94A3B8; font-size: 11px;"></i>
                                            <span>{{ $prov->masked_api_key }}</span>
                                        </div>
                                    </td>

                                    <!-- Column 4: Health & Status -->
                                    <td>
                                        @if($prov->status == 'active' && $prov->is_active)
                                            <div class="health-pill health-operational">
                                                <span class="pulse-dot pulse-dot-green"></span>
                                                <span>Operational</span>
                                            </div>
                                            @if($prov->last_test_response_ms)
                                                <div style="font-size: 11px; color: #64748B; margin-top: 3px; padding-left: 14px;">
                                                    <i class="fa fa-clock-o" style="font-size: 10px;"></i> {{ $prov->last_test_response_ms }} ms
                                                </div>
                                            @endif
                                        @elseif(!$prov->is_active)
                                            <div class="health-pill health-inactive">
                                                <span class="pulse-dot pulse-dot-grey"></span>
                                                <span>Inactive</span>
                                            </div>
                                        @elseif($prov->status == 'connection_error')
                                            <div class="health-pill health-error" title="{{ $prov->last_test_error }}">
                                                <span class="pulse-dot pulse-dot-red"></span>
                                                <span>Error</span>
                                            </div>
                                        @else
                                            <div class="health-pill health-inactive">
                                                <span class="pulse-dot pulse-dot-grey"></span>
                                                <span>{{ ucfirst($prov->status) }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Column 5: Last Tested -->
                                    <td>
                                        <span style="font-size: 12px; color: #64748B;">
                                            @if($prov->last_tested_at)
                                                {{ $prov->last_tested_at->diffForHumans() }}
                                            @else
                                                <span style="color: #94A3B8;">Never</span>
                                            @endif
                                        </span>
                                    </td>

                                    <!-- Column 6: Actions Toolbar -->
                                    <td style="text-align: right;">
                                        <div style="display: inline-flex; align-items: center; gap: 6px; justify-content: flex-end;">
                                            <!-- Test Connection -->
                                            <button type="button" class="btn-action-test btn-test-conn" data-id="{{ $prov->id }}" data-name="{{ $prov->name }}" title="Test Connection">
                                                <i class="fa fa-bolt" style="color: #D97706; margin-right: 3px;"></i> Test
                                            </button>

                                            <!-- Set Active Button -->
                                            @if(!$prov->is_default)
                                                <form action="{{ route('admin.ai.providers.set_active', $prov->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Set {{ $prov->name }} as the primary active AI provider for the portal?');">
                                                    @csrf
                                                    <button type="submit" class="btn-action-active" title="Set as Active Provider">
                                                        <i class="fa fa-star" style="margin-right: 3px;"></i> Set Active
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Edit Icon Button -->
                                            <a href="{{ route('admin.ai.providers.edit', $prov->id) }}" class="btn-action-icon" title="Edit Provider">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <!-- Delete Icon Button -->
                                            @if(!$prov->is_default)
                                                <form action="{{ route('admin.ai.providers.destroy', $prov->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Delete this provider configuration?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-delete" title="Delete Provider">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 48px 20px;">
                        <div style="width: 60px; height: 60px; background: #F1F5F9; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                            <i class="fa fa-microchip" style="font-size: 28px; color: #94A3B8;"></i>
                        </div>
                        <h3 style="color: #0F172A; font-weight: 700; margin-bottom: 6px; font-size: 18px;">No AI Providers Configured Yet</h3>
                        <p style="color: #64748B; max-width: 480px; margin: 0 auto 20px auto; font-size: 13px;">
                            Configure Google Gemini, OpenAI, Anthropic Claude, xAI Grok, or Zhipu GLM to power candidate matching, job optimization, and automated SEO.
                        </p>
                        <a href="{{ route('admin.ai.providers.create') }}" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 6px; font-weight: 600; padding: 9px 22px; box-shadow: 0 2px 4px rgba(37,99,235,0.25);">
                            <i class="fa fa-plus"></i> Configure First AI Provider
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Test Connection Modal -->
<div class="modal fade" id="testModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden;">
            <div class="modal-header" style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; padding: 18px 24px;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="font-size: 22px;">&times;</button>
                <h4 class="modal-title" style="font-weight: 700; color: #0F172A; font-size: 16px; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-bolt" style="color: #D97706;"></i> Live AI Connection Test
                </h4>
            </div>
            <div class="modal-body" id="testModalBody" style="padding: 30px 24px; text-align: center;">
                <div id="testSpinner" style="display: none;">
                    <i class="fa fa-spinner fa-spin fa-3x fa-fw" style="color: #2563EB; margin-bottom: 15px;"></i>
                    <p style="font-weight: 600; color: #334155; font-size: 14px; margin: 0;">Sending safe ping to provider API...</p>
                    <p style="color: #94A3B8; font-size: 12px; margin-top: 4px;">Measuring latency and validating credentials</p>
                </div>
                <div id="testResult"></div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #E2E8F0; background: #F8FAFC; padding: 12px 24px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px; font-weight: 600; border: 1px solid #CBD5E1;">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-test-conn').on('click', function(e) {
        e.preventDefault();
        var providerId = $(this).data('id');
        var providerName = $(this).data('name');

        $('#testModal').modal('show');
        $('#testSpinner').show();
        $('#testResult').hide().html('');

        $.ajax({
            url: '{{ url("admin/ai-providers") }}/' + providerId + '/test-connection',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#testSpinner').hide();
                $('#testResult').show();

                if (response.success) {
                    $('#testResult').html(`
                        <div style="color: #03855c; font-size: 46px; margin-bottom: 12px;"><i class="fa fa-check-circle"></i></div>
                        <h4 style="font-weight: 800; color: #065F46; margin-bottom: 6px; font-size: 18px;">✓ Connection Successful</h4>
                        <p style="color: #64748B; font-size: 13px; margin-bottom: 16px;">Provider API responded within healthy threshold.</p>
                        <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 8px; padding: 16px; text-align: left; font-size: 13px; color: #166534;">
                            <div style="margin-bottom: 6px;"><strong>Provider:</strong> ${providerName}</div>
                            <div style="margin-bottom: 6px;"><strong>Model:</strong> <code style="background: #DCFCE7; color: #166534; padding: 2px 6px; border-radius: 4px; font-family: monospace;">${response.model}</code></div>
                            <div style="margin-bottom: 6px;"><strong>Response Time:</strong> <span style="font-weight: 700; color: #03855c;">${response.response_time_ms} ms</span></div>
                            <div><strong>Status:</strong> <span style="background: #10B981; color: #fff; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 10px;">OPERATIONAL</span></div>
                        </div>
                    `);
                } else {
                    $('#testResult').html(`
                        <div style="color: #DC2626; font-size: 46px; margin-bottom: 12px;"><i class="fa fa-times-circle"></i></div>
                        <h4 style="font-weight: 800; color: #991B1B; margin-bottom: 6px; font-size: 18px;">✕ Connection Failed</h4>
                        <p style="color: #64748B; font-size: 13px; margin-bottom: 16px;">Unable to reach provider with the configured credentials.</p>
                        <div style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; padding: 16px; text-align: left; font-size: 13px; color: #991B1B;">
                            <div style="margin-bottom: 6px;"><strong>Provider:</strong> ${providerName}</div>
                            <div style="margin-bottom: 6px;"><strong>Model:</strong> <code style="background: #FEE2E2; color: #991B1B; padding: 2px 6px; border-radius: 4px; font-family: monospace;">${response.model || 'Unknown'}</code></div>
                            <div><strong>Reason:</strong> ${response.message}</div>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                $('#testSpinner').hide();
                $('#testResult').show();
                var msg = 'An unexpected server error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $('#testResult').html(`
                    <div style="color: #DC2626; font-size: 46px; margin-bottom: 12px;"><i class="fa fa-exclamation-triangle"></i></div>
                    <h4 style="font-weight: 800; color: #991B1B; margin-bottom: 6px; font-size: 18px;">✕ Test Request Error</h4>
                    <div style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; padding: 16px; text-align: left; font-size: 13px; color: #991B1B;">
                        ${msg}
                    </div>
                `);
            }
        });
    });
});
</script>
@endpush
@endsection
