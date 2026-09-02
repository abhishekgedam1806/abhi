@extends('admin.layouts.admin_layout')

@section('content')
<style>
    /* Executive Cost & Performance Dashboard Styles */
    .ai-stat-card {
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 20px 22px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .ai-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.06);
    }
    .ai-stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748B;
        margin-bottom: 8px;
    }
    .ai-stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }
    .ai-stat-sub {
        font-size: 12px;
        color: #94A3B8;
        margin-top: 6px;
    }
    .unit-eco-card {
        background: #0F172A;
        color: #fff;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #334155;
        height: 100%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .unit-eco-item {
        padding: 14px 0;
        border-bottom: 1px solid #334155;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .unit-eco-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .badge-feature {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
    }
    .badge-group-candidate { background: #EFF6FF; color: #1D4ED8; border: 1px solid #DBEAFE; }
    .badge-group-employer { background: #F5F3FF; color: #6D28D9; border: 1px solid #EDE9FE; }
    .badge-group-automated { background: #ECFDF5; color: #047857; border: 1px solid #D1FAE5; }
    .badge-group-system { background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; }

    .custom-dash-table {
        margin: 0;
        width: 100%;
    }
    .custom-dash-table th {
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
    .custom-dash-table td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #F1F5F9;
        font-size: 13px;
    }
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <!-- Breadcrumbs & Time Filter Toolbar -->
        <div class="page-bar" style="margin-bottom: 25px; background: #fff; padding: 12px 18px; border-radius: 8px; border: 1px solid #E2E8F0;">
            <ul class="page-breadcrumb" style="margin: 0;">
                <li><a href="{{ route('admin.home') }}" style="color: #64748B;">Dashboard</a><i class="fa fa-angle-right" style="margin: 0 8px; color: #CBD5E1;"></i></li>
                <li><a href="javascript:;" style="color: #64748B;">AI Engine</a><i class="fa fa-angle-right" style="margin: 0 8px; color: #CBD5E1;"></i></li>
                <li><span style="font-weight: 600; color: #0F172A;">AI Cost & Performance</span></li>
            </ul>
            <div class="page-toolbar" style="margin: 0;">
                <div class="btn-group" role="group" style="margin-right: 10px;">
                    <a href="{{ route('admin.ai.cost_performance', ['days' => 7]) }}" class="btn btn-sm {{ $days == 7 ? 'btn-primary active' : 'btn-default' }}" style="font-weight: 600; border-radius: 6px 0 0 6px;">7 Days</a>
                    <a href="{{ route('admin.ai.cost_performance', ['days' => 30]) }}" class="btn btn-sm {{ $days == 30 ? 'btn-primary active' : 'btn-default' }}" style="font-weight: 600;">30 Days</a>
                    <a href="{{ route('admin.ai.cost_performance', ['days' => 90]) }}" class="btn btn-sm {{ $days == 90 ? 'btn-primary active' : 'btn-default' }}" style="font-weight: 600; border-radius: 0 6px 6px 0;">90 Days</a>
                </div>
                <a href="{{ route('admin.ai.providers') }}" class="btn btn-sm btn-default" style="border: 1px solid #CBD5E1; font-weight: 600; border-radius: 6px; color: #334155;">
                    <i class="fa fa-cogs" style="color: #6366F1;"></i> Manage AI Providers
                </a>
            </div>
        </div>

        @include('flash::message')

        <!-- Row 1: Executive Cost Cards -->
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="ai-stat-card" style="border-top: 3px solid #2563EB;">
                    <div class="ai-stat-label">Cost Today</div>
                    <div class="ai-stat-value" style="color: #2563EB;">₹{{ number_format($costToday, 2) }}</div>
                    <div class="ai-stat-sub">Real-time accrued</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="ai-stat-card" style="border-top: 3px solid #64748B;">
                    <div class="ai-stat-label">Yesterday</div>
                    <div class="ai-stat-value">₹{{ number_format($costYesterday, 2) }}</div>
                    <div class="ai-stat-sub">24-hour total</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="ai-stat-card" style="border-top: 3px solid #10B981;">
                    <div class="ai-stat-label">This Month</div>
                    <div class="ai-stat-value" style="color: #03855c;">₹{{ number_format($costThisMonth, 2) }}</div>
                    <div class="ai-stat-sub">Current billing cycle</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="ai-stat-card" style="border-top: 3px solid #8B5CF6;">
                    <div class="ai-stat-label">Pre-sale Previews (30d)</div>
                    <div class="ai-stat-value" style="color: #7C3AED;">₹{{ number_format($costPresale30d, 2) }}</div>
                    <div class="ai-stat-sub">Profile & job analysis</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                <div class="ai-stat-card" style="border-top: 3px solid #F59E0B;">
                    <div class="ai-stat-label">Total AI Calls</div>
                    <div class="ai-stat-value" style="color: #D97706;">{{ number_format($totalCallsInPeriod) }}</div>
                    <div class="ai-stat-sub">Last {{ $days }} days</div>
                </div>
            </div>
        </div>

        <!-- Row 2: Unit Economics & Performance Metrics -->
        <div class="row" style="margin-bottom: 25px;">
            <!-- Unit Economics -->
            <div class="col-md-5">
                <div class="unit-eco-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <h4 style="margin: 0; font-size: 16px; font-weight: 700; color: #F8FAFC; display: flex; align-items: center; gap: 8px;">
                            <i class="fa fa-calculator" style="color: #FBBF24;"></i> Unit Economics (30d)
                        </h4>
                        <span style="font-size: 10px; font-weight: 700; background: #334155; color: #94A3B8; padding: 3px 8px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;">MEASURED</span>
                    </div>

                    <div class="unit-eco-item">
                        <div>
                            <div style="font-size: 14px; font-weight: 600; color: #F1F5F9;">Per Onboarding</div>
                            <div style="font-size: 11px; color: #94A3B8;">Candidate Profile & Resume Parsing</div>
                        </div>
                        <div style="font-size: 18px; font-weight: 800; color: #34D399; font-family: monospace;">
                            ₹{{ number_format($costPerOnboarding, 4) }}
                        </div>
                    </div>

                    <div class="unit-eco-item">
                        <div>
                            <div style="font-size: 14px; font-weight: 600; color: #F1F5F9;">Per Replacement Review</div>
                            <div style="font-size: 11px; color: #94A3B8;">Candidate Matching & Ranking</div>
                        </div>
                        <div style="font-size: 18px; font-weight: 800; color: #60A5FA; font-family: monospace;">
                            ₹{{ number_format($costPerReplacement, 4) }}
                        </div>
                    </div>

                    <div class="unit-eco-item">
                        <div>
                            <div style="font-size: 14px; font-weight: 600; color: #F1F5F9;">Per Smart AI Generation</div>
                            <div style="font-size: 11px; color: #94A3B8;">Job Description Optimization & Polish</div>
                        </div>
                        <div style="font-size: 18px; font-weight: 800; color: #A78BFA; font-family: monospace;">
                            ₹{{ number_format($costPerGeneration, 4) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Overview -->
            <div class="col-md-7">
                <div class="portlet light bordered" style="border-radius: 12px; height: 100%; margin-bottom: 0; border: 1px solid #E2E8F0; padding: 20px;">
                    <div class="portlet-title" style="min-height: 40px; margin-bottom: 12px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px;">
                        <div class="caption font-dark" style="margin: 0;">
                            <i class="fa fa-tachometer" style="color: #2563EB;"></i>
                            <span class="caption-subject bold" style="font-size: 15px; color: #0F172A;">AI Performance & Reliability ({{ $days }} Days)</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-xs-6 col-sm-3 text-center" style="border-right: 1px solid #F1F5F9; margin-bottom: 15px;">
                                <div style="font-size: 24px; font-weight: 800; color: #03855c;">{{ $successRate }}%</div>
                                <div style="font-size: 12px; font-weight: 600; color: #64748B; margin-top: 4px;">Success Rate</div>
                            </div>
                            <div class="col-xs-6 col-sm-3 text-center" style="border-right: 1px solid #F1F5F9; margin-bottom: 15px;">
                                <div style="font-size: 24px; font-weight: 800; color: #2563EB;">{{ round($avgResponseTimeMs / 1000, 2) }}s</div>
                                <div style="font-size: 12px; font-weight: 600; color: #64748B; margin-top: 4px;">Avg Response</div>
                            </div>
                            <div class="col-xs-6 col-sm-3 text-center" style="border-right: 1px solid #F1F5F9; margin-bottom: 15px;">
                                <div style="font-size: 24px; font-weight: 800; color: #0F172A;">{{ number_format($successfulCalls) }}</div>
                                <div style="font-size: 12px; font-weight: 600; color: #64748B; margin-top: 4px;">Successful</div>
                            </div>
                            <div class="col-xs-6 col-sm-3 text-center" style="margin-bottom: 15px;">
                                <div style="font-size: 24px; font-weight: 800; color: {{ $failedCalls > 0 ? '#DC2626' : '#94A3B8' }};">{{ number_format($failedCalls) }}</div>
                                <div style="font-size: 12px; font-weight: 600; color: #64748B; margin-top: 4px;">Failed Calls</div>
                            </div>
                        </div>

                        <!-- Reliability Progress Bar -->
                        <div style="margin-top: 15px;">
                            <div style="display: flex; justify-content: space-between; font-size: 12px; color: #64748B; margin-bottom: 6px;">
                                <span>Operational Health: <strong style="color: #03855c;">{{ $successRate }}%</strong></span>
                                <span>Total Volume: <strong>{{ number_format($totalCalls) }} calls</strong></span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 4px; background-color: #F1F5F9; margin-bottom: 0; overflow: hidden;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $successRate }}%; background-color: #10B981;"></div>
                                @if($failedCalls > 0)
                                    <div class="progress-bar" role="progressbar" style="width: {{ 100 - $successRate }}%; background-color: #EF4444;"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Provider Cost & Latency Comparison -->
        <div class="portlet light bordered" style="border-radius: 12px; margin-bottom: 25px; border: 1px solid #E2E8F0; overflow: hidden; padding: 0;">
            <div class="portlet-title" style="padding: 16px 20px; margin-bottom: 0; border-bottom: 1px solid #E2E8F0; background: #FFFFFF;">
                <div class="caption font-dark" style="margin: 0;">
                    <i class="fa fa-cubes" style="color: #2563EB;"></i>
                    <span class="caption-subject bold" style="font-size: 15px; color: #0F172A;">Provider Performance & Cost Comparison</span>
                </div>
            </div>
            <div class="portlet-body" style="padding: 0;">
                @if($providerPerformance->count() > 0)
                    <div class="table-responsive" style="margin: 0;">
                        <table class="custom-dash-table">
                            <thead>
                                <tr>
                                    <th style="width: 22%;">Provider Engine</th>
                                    <th style="width: 22%;">Model ID</th>
                                    <th style="text-align: center; width: 14%;">Total Calls</th>
                                    <th style="text-align: center; width: 14%;">Success Rate</th>
                                    <th style="text-align: center; width: 14%;">Avg Latency</th>
                                    <th style="text-align: right; width: 14%;">Total Cost (INR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($providerPerformance as $item)
                                <tr>
                                    <td>
                                        <span class="badge-feature badge-group-{{ $item->provider_type == 'gemini' ? 'candidate' : 'employer' }}">
                                            {{ ucfirst($item->provider_type) }}
                                        </span>
                                    </td>
                                    <td><code style="background: #F1F5F9; color: #0F172A; padding: 3px 8px; border-radius: 4px; font-family: monospace;">{{ $item->model }}</code></td>
                                    <td style="text-align: center; font-weight: 700; color: #0F172A;">{{ number_format($item->total_calls) }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge {{ $item->success_rate >= 95 ? 'badge-success' : 'badge-warning' }}" style="font-size: 11px; padding: 3px 8px; border-radius: 10px;">
                                            {{ $item->success_rate }}%
                                        </span>
                                    </td>
                                    <td style="text-align: center; color: #475569;">{{ $item->avg_response_sec }}s <span style="color: #94A3B8; font-size: 11px;">({{ (int)$item->avg_response_ms }}ms)</span></td>
                                    <td style="text-align: right; font-weight: 700; color: #0F172A; font-family: monospace; font-size: 14px;">
                                        ₹{{ number_format($item->total_cost_inr, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 35px 20px; color: #94A3B8;">
                        <i class="fa fa-info-circle" style="font-size: 24px; margin-bottom: 8px;"></i>
                        <p style="margin: 0; font-size: 13px;">No provider usage records recorded in the selected {{ $days }}-day window.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Row 4: Feature Categories Matrix -->
        <div class="portlet light bordered" style="border-radius: 12px; margin-bottom: 25px; border: 1px solid #E2E8F0; padding: 20px;">
            <div class="portlet-title" style="min-height: 40px; margin-bottom: 16px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px;">
                <div class="caption font-dark" style="margin: 0;">
                    <i class="fa fa-th-large" style="color: #2563EB;"></i>
                    <span class="caption-subject bold" style="font-size: 15px; color: #0F172A;">Feature Category Breakdown ({{ $days }} Days)</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <!-- Candidate Features -->
                    <div class="col-md-4">
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px; margin-bottom: 15px;">
                            <h4 style="margin: 0 0 14px 0; font-size: 14px; font-weight: 700; color: #1E293B; display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-user" style="color: #2563EB;"></i> Candidate Side
                            </h4>
                            @foreach($featuresList as $key => $feat)
                                @if($feat['group'] == 'candidate')
                                    @php $stat = $featureStats->get($key); @endphp
                                    <div style="display: flex; justify-content: space-between; font-size: 12px; padding: 8px 0; border-bottom: 1px dashed #E2E8F0;">
                                        <span><i class="fa {{ $feat['icon'] }}" style="color: #64748B; width: 16px;"></i> {{ $feat['name'] }}</span>
                                        <span>
                                            <strong style="color: #0F172A;">{{ $stat ? number_format($stat->total_calls) : 0 }}</strong> calls
                                            <span style="color: #03855c; margin-left: 4px; font-weight: 600;">(₹{{ $stat ? number_format($stat->total_cost_inr, 3) : '0.000' }})</span>
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Employer Features -->
                    <div class="col-md-4">
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px; margin-bottom: 15px;">
                            <h4 style="margin: 0 0 14px 0; font-size: 14px; font-weight: 700; color: #1E293B; display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-building" style="color: #7C3AED;"></i> Employer Side
                            </h4>
                            @foreach($featuresList as $key => $feat)
                                @if($feat['group'] == 'employer')
                                    @php $stat = $featureStats->get($key); @endphp
                                    <div style="display: flex; justify-content: space-between; font-size: 12px; padding: 8px 0; border-bottom: 1px dashed #E2E8F0;">
                                        <span><i class="fa {{ $feat['icon'] }}" style="color: #64748B; width: 16px;"></i> {{ $feat['name'] }}</span>
                                        <span>
                                            <strong style="color: #0F172A;">{{ $stat ? number_format($stat->total_calls) : 0 }}</strong> calls
                                            <span style="color: #03855c; margin-left: 4px; font-weight: 600;">(₹{{ $stat ? number_format($stat->total_cost_inr, 3) : '0.000' }})</span>
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Automated Jobs -->
                    <div class="col-md-4">
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px; margin-bottom: 15px;">
                            <h4 style="margin: 0 0 14px 0; font-size: 14px; font-weight: 700; color: #1E293B; display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-cogs" style="color: #03855c;"></i> Automated Jobs System
                            </h4>
                            @foreach($featuresList as $key => $feat)
                                @if($feat['group'] == 'automated_jobs')
                                    @php $stat = $featureStats->get($key); @endphp
                                    <div style="display: flex; justify-content: space-between; font-size: 12px; padding: 8px 0; border-bottom: 1px dashed #E2E8F0;">
                                        <span><i class="fa {{ $feat['icon'] }}" style="color: #64748B; width: 16px;"></i> {{ $feat['name'] }}</span>
                                        <span>
                                            <strong style="color: #0F172A;">{{ $stat ? number_format($stat->total_calls) : 0 }}</strong> calls
                                            <span style="color: #03855c; margin-left: 4px; font-weight: 600;">(₹{{ $stat ? number_format($stat->total_cost_inr, 3) : '0.000' }})</span>
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 5: Live Request Logs Stream -->
        <div class="portlet light bordered" style="border-radius: 12px; border: 1px solid #E2E8F0; overflow: hidden; padding: 0;">
            <div class="portlet-title" style="padding: 16px 20px; margin-bottom: 0; border-bottom: 1px solid #E2E8F0; background: #FFFFFF;">
                <div class="caption font-dark" style="margin: 0;">
                    <i class="fa fa-list-alt" style="color: #2563EB;"></i>
                    <span class="caption-subject bold" style="font-size: 15px; color: #0F172A;">Live AI Request Logs (Latest 20)</span>
                </div>
            </div>
            <div class="portlet-body" style="padding: 0;">
                @if($recentLogs->count() > 0)
                    <div class="table-responsive" style="margin: 0;">
                        <table class="custom-dash-table">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Feature</th>
                                    <th>Provider / Model</th>
                                    <th>Tokens (In/Out)</th>
                                    <th>Latency</th>
                                    <th>Estimated Cost</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLogs as $log)
                                <tr>
                                    <td style="color: #64748B; font-size: 12px;">{{ $log->created_at->format('M d, H:i:s') }}</td>
                                    <td>
                                        <span class="badge-feature badge-group-{{ $log->feature_group }}">
                                            {{ str_replace('_', ' ', ucfirst($log->feature)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong style="color: #0F172A;">{{ ucfirst($log->provider_type) }}</strong>
                                        <span style="color: #64748B; font-size: 11px; font-family: monospace;">({{ $log->model }})</span>
                                    </td>
                                    <td>
                                        <span style="color: #475569; font-size: 12px;">{{ number_format($log->input_tokens) }} in / {{ number_format($log->output_tokens) }} out</span>
                                    </td>
                                    <td style="color: #475569;">{{ $log->response_time_ms }} ms</td>
                                    <td style="font-family: monospace; font-weight: 700; color: #0F172A;">
                                        ₹{{ number_format($log->estimated_cost_inr, 4) }}
                                    </td>
                                    <td>
                                        @if($log->is_success)
                                            <span class="badge badge-success" style="font-size: 10px; padding: 2px 8px; border-radius: 10px;">SUCCESS</span>
                                        @else
                                            <span class="badge badge-danger" style="font-size: 10px; padding: 2px 8px; border-radius: 10px;" title="{{ $log->error_message }}">FAILED</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center" style="padding: 15px;">
                        {{ $recentLogs->appends(['days' => $days])->links() }}
                    </div>
                @else
                    <div style="text-align: center; padding: 35px 20px; color: #94A3B8;">
                        <i class="fa fa-terminal" style="font-size: 28px; margin-bottom: 8px;"></i>
                        <p style="margin: 0; font-size: 13px;">No AI requests logged yet. As portal features or test pings run, they will appear here in real-time.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
