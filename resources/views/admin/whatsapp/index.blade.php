@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content" style="background-color: #F8FAFC; min-height: 100vh; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
        
        <!-- Scoped Modern WhatsApp Dashboard -->
        <div class="wa-dashboard-container" style="max-width: 1400px; margin: 0 auto; padding-bottom: 50px;">
            
            <!-- Breadcrumb & Top Bar -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #64748B; margin-bottom: 4px;">
                        <a href="{{ route('admin.home') }}" style="color: #64748B; text-decoration: none;">Dashboard</a>
                        <span>/</span>
                        <span style="color: #0F172A; font-weight: 600;">WhatsApp Desk</span>
                    </div>
                    <h1 style="font-size: 24px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.5px;">
                        WhatsApp Notification Desk
                    </h1>
                </div>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <button type="button" class="btn btn-default" id="btnTestConn" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 10px; font-weight: 700; color: #334155; padding: 9px 18px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s ease;">
                        <i class="fa fa-plug" style="color: #10B981; margin-right: 6px;"></i> Test Ping
                    </button>
                    <a href="{{ route('admin.whatsapp.templates') }}" class="btn btn-default" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 10px; font-weight: 700; color: #334155; padding: 9px 18px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); text-decoration: none;">
                        <i class="fa fa-file-text-o" style="color: #8B5CF6; margin-right: 6px;"></i> Templates
                    </a>
                    <a href="{{ route('admin.whatsapp.settings') }}" class="btn" style="background: #059669; color: #FFFFFF; border: none; border-radius: 10px; font-weight: 700; padding: 10px 20px; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3); text-decoration: none;">
                        <i class="fa fa-sliders" style="margin-right: 6px;"></i> Configure Gateway
                    </a>
                </div>
            </div>

            @include('flash::message')

            <!-- 1. Hero Banner Card (Forced Dark Emerald Gradient - Immune to Metronic White Overrides) -->
            <div class="wa-hero-box" style="background: linear-gradient(135deg, #064E3B 0%, #047857 60%, #059669 100%) !important; border-radius: 18px; padding: 28px 32px; color: #FFFFFF !important; margin-bottom: 28px; box-shadow: 0 10px 30px rgba(6, 78, 59, 0.18); position: relative; overflow: hidden;">
                <!-- Decorative Glow Background Circle -->
                <div style="position: absolute; right: -50px; top: -50px; width: 280px; height: 280px; background: radial-gradient(circle, rgba(52, 211, 153, 0.2) 0%, rgba(6, 78, 59, 0) 70%); border-radius: 50%; pointer-events: none;"></div>

                <div class="row" style="position: relative; z-index: 2; display: flex; align-items: center; flex-wrap: wrap;">
                    <div class="col-md-8 col-sm-12">
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px; flex-wrap: wrap;">
                            <div style="background: #25D366; width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 16px rgba(37, 211, 102, 0.4); flex-shrink: 0;">
                                <i class="fa fa-whatsapp" style="font-size: 30px; color: #FFFFFF;"></i>
                            </div>
                            <div>
                                <h2 style="font-size: 22px; font-weight: 800; color: #FFFFFF !important; margin: 0; letter-spacing: -0.3px; line-height: 1.2;">
                                    Enterprise WhatsApp Notification Engine
                                </h2>
                                <div style="display: flex; gap: 8px; align-items: center; margin-top: 6px; flex-wrap: wrap;">
                                    <span style="background: rgba(255,255,255,0.18); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.25); color: #FFFFFF; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 20px; text-transform: uppercase;">
                                        Driver: {{ $setting->provider }}
                                    </span>
                                    @if($setting->is_enabled)
                                        <span style="background: #D1FAE5; color: #065F46; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                                            <span style="width: 7px; height: 7px; background: #10B981; border-radius: 50%; display: inline-block;"></span> Active & Online
                                        </span>
                                    @else
                                        <span style="background: #FEE2E2; color: #991B1B; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">
                                            <i class="fa fa-pause-circle"></i> Master Switch Disabled
                                        </span>
                                    @endif
                                    @if($setting->test_mode)
                                        <span style="background: #FEF3C7; color: #92400E; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">
                                            <i class="fa fa-flask"></i> Sandbox Mode
                                        </span>
                                    @endif
                                    <span style="color: #A7F3D0; font-size: 12px; font-weight: 600;">
                                        Limit: {{ number_format($setting->daily_limit ?: 500) }} msgs/day
                                    </span>
                                </div>
                            </div>
                        </div>

                        <p style="margin: 0; color: #ECFDF5 !important; font-size: 14px; line-height: 1.55; max-width: 720px;">
                            Autonomous, asynchronous notification channel that dispatches real-time transactional alerts for <b>Job Matches</b>, <b>Application Confirmations</b>, <b>Status Updates</b>, and <b>Employer Leads</b> using verified templates at <b>₹0 Gemini AI cost</b>.
                        </p>
                    </div>

                    <div class="col-md-4 col-sm-12 text-right" style="margin-top: 15px;">
                        <div style="background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 14px 18px; display: inline-block; text-align: left; min-width: 220px;">
                            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #A7F3D0; font-weight: 700; margin-bottom: 4px;">
                                Gateway Health Check
                            </div>
                            <div style="font-size: 14px; font-weight: 800; color: #FFFFFF; display: flex; align-items: center; gap: 8px;">
                                @if($setting->last_test_status === 'success')
                                    <i class="fa fa-check-circle" style="color: #34D399; font-size: 16px;"></i> Operational
                                @elseif($setting->last_test_status === 'failed')
                                    <i class="fa fa-exclamation-circle" style="color: #F87171; font-size: 16px;"></i> Needs Attention
                                @else
                                    <i class="fa fa-circle-o" style="color: #94A3B8; font-size: 16px;"></i> Ready to Test
                                @endif
                            </div>
                            <div style="font-size: 11.5px; color: #D1FAE5; margin-top: 4px;">
                                Ping: {{ $setting->last_tested_at ? $setting->last_tested_at->diffForHumans() : 'Never' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Four Sleek Modern KPI Metric Cards -->
            <div class="row" style="margin-bottom: 28px;">
                <!-- Total Sent Card -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                    <div class="wa-kpi-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <div>
                                <span style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Total Sent</span>
                                <h3 style="font-size: 28px; font-weight: 800; color: #0F172A; margin: 0; line-height: 1;">
                                    {{ number_format($totalSent) }}
                                </h3>
                            </div>
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: #ECFDF5; border: 1px solid #A7F3D0; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-paper-plane" style="color: #059669; font-size: 18px;"></i>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #64748B; padding-top: 10px; border-top: 1px solid #F1F5F9;">
                            <span>Today's Dispatches</span>
                            <span style="font-weight: 700; color: #0F172A; background: #F8FAFC; padding: 2px 8px; border-radius: 6px; border: 1px solid #E2E8F0;">{{ number_format($todaySent) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Delivery Rate Card -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                    <div class="wa-kpi-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <div>
                                <span style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Delivery Rate</span>
                                <h3 style="font-size: 28px; font-weight: 800; color: #0284C7; margin: 0; line-height: 1;">
                                    {{ $deliveryRate }}%
                                </h3>
                            </div>
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: #F0F9FF; border: 1px solid #BAE6FD; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-check-circle" style="color: #0284C7; font-size: 20px;"></i>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #64748B; padding-top: 10px; border-top: 1px solid #F1F5F9;">
                            <span>Delivered / Read</span>
                            <span style="font-weight: 700; color: #0284C7; background: #F0F9FF; padding: 2px 8px; border-radius: 6px; border: 1px solid #BAE6FD;">{{ number_format($totalDelivered) }} msgs</span>
                        </div>
                    </div>
                </div>

                <!-- Read Receipts Card -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                    <div class="wa-kpi-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <div>
                                <span style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Read Receipts</span>
                                <h3 style="font-size: 28px; font-weight: 800; color: #7C3AED; margin: 0; line-height: 1;">
                                    {{ number_format($totalRead) }}
                                </h3>
                            </div>
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: #F5F3FF; border: 1px solid #DDD6FE; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-eye" style="color: #7C3AED; font-size: 18px;"></i>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #64748B; padding-top: 10px; border-top: 1px solid #F1F5F9;">
                            <span>Blue Tick Open Rate</span>
                            <span style="font-weight: 700; color: #7C3AED; background: #F5F3FF; padding: 2px 8px; border-radius: 6px; border: 1px solid #DDD6FE;">{{ $readRate }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Failed / Queued Card -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
                    <div class="wa-kpi-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <div>
                                <span style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Failed / Queued</span>
                                <h3 style="font-size: 28px; font-weight: 800; color: {{ $totalFailed > 0 ? '#DC2626' : '#059669' }}; margin: 0; line-height: 1;">
                                    {{ number_format($totalFailed) }} <span style="font-size: 15px; font-weight: 600; color: #94A3B8;">/ {{ number_format($totalQueued) }}</span>
                                </h3>
                            </div>
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: {{ $totalFailed > 0 ? '#FEF2F2' : '#ECFDF5' }}; border: 1px solid {{ $totalFailed > 0 ? '#FECACA' : '#A7F3D0' }}; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-{{ $totalFailed > 0 ? 'exclamation-triangle' : 'shield' }}" style="color: {{ $totalFailed > 0 ? '#DC2626' : '#059669' }}; font-size: 18px;"></i>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #64748B; padding-top: 10px; border-top: 1px solid #F1F5F9;">
                            <span>Fail-Safe Protection</span>
                            <span style="font-weight: 700; color: #059669;">100% Isolated</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Main Data Content (Recent Dispatches + Traffic Breakdown) -->
            <div class="row">
                <!-- Left: Recent Dispatches Table -->
                <div class="col-lg-8 col-md-12" style="margin-bottom: 25px;">
                    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: #EFF6FF; display: flex; align-items: center; justify-content: center; color: #2563EB;">
                                    <i class="fa fa-history" style="font-size: 15px;"></i>
                                </div>
                                <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">
                                    Recent Notification Dispatches
                                </h3>
                            </div>
                            <a href="{{ route('admin.whatsapp.logs') }}" style="font-size: 13px; font-weight: 700; color: #2563EB; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                View Full Audit Trail <i class="fa fa-angle-right"></i>
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table" style="margin-bottom: 0; vertical-align: middle;">
                                <thead>
                                    <tr style="border-bottom: 1.5px solid #E2E8F0; color: #64748B; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <th style="padding: 12px 10px;">Recipient</th>
                                        <th style="padding: 12px 10px;">Template Event</th>
                                        <th style="padding: 12px 10px;">Phone Number</th>
                                        <th style="padding: 12px 10px;">Status</th>
                                        <th style="padding: 12px 10px; text-align: right;">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentNotifications as $item)
                                        <tr style="border-bottom: 1px solid #F1F5F9; font-size: 13px;">
                                            <td style="padding: 14px 10px;">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div style="width: 34px; height: 34px; border-radius: 50%; background: {{ $item->notifiable_type === 'company' ? '#F0FDF4' : '#EFF6FF' }}; color: {{ $item->notifiable_type === 'company' ? '#15803D' : '#1D4ED8' }}; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; flex-shrink: 0; border: 1px solid {{ $item->notifiable_type === 'company' ? '#BBF7D0' : '#BFDBFE' }};">
                                                        {{ strtoupper(substr($item->recipient_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div style="font-weight: 700; color: #0F172A; line-height: 1.2;">{{ $item->recipient_name }}</div>
                                                        <span style="font-size: 11px; color: #64748B; text-transform: capitalize;">{{ $item->notifiable_type }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding: 14px 10px;">
                                                <span style="font-weight: 700; color: #2563EB;">
                                                    {{ ucwords(str_replace('_', ' ', $item->template_key)) }}
                                                </span>
                                            </td>
                                            <td style="padding: 14px 10px;">
                                                <code style="background: #F8FAFC; border: 1px solid #E2E8F0; color: #0F172A; padding: 3px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                                    {{ $item->recipient_phone }}
                                                </code>
                                            </td>
                                            <td style="padding: 14px 10px;">
                                                @if($item->status == 'sent')
                                                    <span style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                                        <i class="fa fa-paper-plane" style="font-size: 10px;"></i> Sent
                                                    </span>
                                                @elseif($item->status == 'delivered')
                                                    <span style="background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                                        &#10003;&#10003; Delivered
                                                    </span>
                                                @elseif($item->status == 'read')
                                                    <span style="background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                                        <span style="color: #0284C7; font-weight: 900;">&#10003;&#10003;</span> Read
                                                    </span>
                                                @elseif($item->status == 'queued')
                                                    <span style="background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                                        <i class="fa fa-clock-o" style="font-size: 10px;"></i> Queued
                                                    </span>
                                                @else
                                                    <span style="background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;" title="{{ $item->error_message }}">
                                                        <i class="fa fa-times-circle" style="font-size: 10px;"></i> Failed
                                                    </span>
                                                @endif
                                            </td>
                                            <td style="padding: 14px 10px; text-align: right; color: #64748B; font-size: 12px; font-weight: 500;">
                                                {{ $item->created_at ? $item->created_at->diffForHumans() : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" style="text-align: center; padding: 40px 20px; color: #94A3B8;">
                                                <div style="background: #F8FAFC; width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto; color: #94A3B8;">
                                                    <i class="fa fa-whatsapp" style="font-size: 26px;"></i>
                                                </div>
                                                <div style="font-weight: 700; font-size: 14px; color: #334155; margin-bottom: 4px;">No WhatsApp Notifications Yet</div>
                                                <div style="font-size: 12.5px;">Trigger a job application, or click "Test Ping" above to dispatch a test alert.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Traffic Breakdown & Shortcuts -->
                <div class="col-lg-4 col-md-12">
                    <!-- Traffic Breakdown Card -->
                    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #F5F3FF; display: flex; align-items: center; justify-content: center; color: #7C3AED;">
                                <i class="fa fa-pie-chart" style="font-size: 15px;"></i>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">
                                Traffic Distribution By Event
                            </h3>
                        </div>

                        @forelse($eventBreakdown as $ev)
                            <div style="margin-bottom: 16px;">
                                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 700; margin-bottom: 6px;">
                                    <span style="color: #334155;">{{ ucwords(str_replace('_', ' ', $ev->event_type)) }}</span>
                                    <span style="color: #059669; background: #ECFDF5; padding: 1px 8px; border-radius: 6px; font-size: 12px;">{{ $ev->count }} msgs</span>
                                </div>
                                <div style="height: 7px; background: #F1F5F9; border-radius: 10px; overflow: hidden;">
                                    <div style="height: 100%; width: {{ $totalNotifications > 0 ? ($ev->count / $totalNotifications) * 100 : 0 }}%; background: linear-gradient(90deg, #10B981, #059669); border-radius: 10px;"></div>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 24px 0; color: #94A3B8; font-size: 13px;">
                                No event traffic recorded yet.
                            </div>
                        @endforelse
                    </div>

                    <!-- Quick Navigation Shortcuts -->
                    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #FEF3C7; display: flex; align-items: center; justify-content: center; color: #D97706;">
                                <i class="fa fa-bolt" style="font-size: 15px;"></i>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">
                                Administrative Shortcuts
                            </h3>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <a href="{{ route('admin.whatsapp.templates') }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; text-decoration: none; color: #0F172A; font-weight: 700; font-size: 13.5px; transition: all 0.2s ease;">
                                <span style="display: flex; align-items: center; gap: 10px;">
                                    <i class="fa fa-file-text-o" style="color: #7C3AED; font-size: 15px;"></i> 10 Pre-Approved Templates
                                </span>
                                <i class="fa fa-chevron-right" style="color: #94A3B8; font-size: 11px;"></i>
                            </a>

                            <a href="{{ route('admin.whatsapp.settings') }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; text-decoration: none; color: #0F172A; font-weight: 700; font-size: 13.5px; transition: all 0.2s ease;">
                                <span style="display: flex; align-items: center; gap: 10px;">
                                    <i class="fa fa-sliders" style="color: #0284C7; font-size: 15px;"></i> Provider Credentials & Keys
                                </span>
                                <i class="fa fa-chevron-right" style="color: #94A3B8; font-size: 11px;"></i>
                            </a>

                            <a href="{{ route('admin.whatsapp.logs') }}?status=failed" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; text-decoration: none; color: #0F172A; font-weight: 700; font-size: 13.5px; transition: all 0.2s ease;">
                                <span style="display: flex; align-items: center; gap: 10px;">
                                    <i class="fa fa-refresh" style="color: #DC2626; font-size: 15px;"></i> Retry Failed Dispatches
                                </span>
                                <i class="fa fa-chevron-right" style="color: #94A3B8; font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Test Connection Modal -->
<div class="modal fade" id="modalTestConn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 18px; border: none; box-shadow: 0 15px 50px rgba(0,0,0,0.15); overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #064E3B 0%, #047857 100%); color: #FFFFFF; padding: 20px 24px; border: none;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #FFF; opacity: 0.8; font-size: 24px;">&times;</button>
                <h4 class="modal-title" style="font-weight: 800; font-size: 18px; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="fa fa-whatsapp" style="color: #25D366; font-size: 22px;"></i> Test WhatsApp Gateway Connection
                </h4>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <p style="font-size: 13.5px; color: #475569; margin-bottom: 18px;">
                    Verify that your active driver (<code>{{ $setting->provider }}</code>) credentials and tokens are functioning properly.
                </p>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-size: 13px; font-weight: 700; color: #1E293B; margin-bottom: 6px; display: block;">
                        Optional Recipient Phone Number (with Country Code):
                    </label>
                    <input type="text" id="testPhoneInput" class="form-control" placeholder="+919876543210" value="{{ $setting->sender_number }}" style="border: 1.5px solid #CBD5E1; border-radius: 10px; height: 44px; font-weight: 600; font-size: 14px;">
                    <span class="help-block" style="font-size: 12px; color: #64748B; margin-top: 4px;">Leave empty to test API connectivity only without sending a real message.</span>
                </div>

                <div id="testResultBox" style="display: none; padding: 16px; border-radius: 12px; margin-top: 18px; font-size: 13px; line-height: 1.5;"></div>
            </div>
            <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 16px 24px; display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 10px; font-weight: 700; padding: 9px 18px;">Close</button>
                <button type="button" class="btn btn-success" id="btnExecuteTest" style="background: #059669; border: none; border-radius: 10px; font-weight: 700; padding: 9px 22px;">
                    <i class="fa fa-play"></i> Run Live Ping Test
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.wa-kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.06) !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#btnTestConn').on('click', function() {
        $('#testResultBox').hide().empty();
        $('#modalTestConn').modal('show');
    });

    $('#btnExecuteTest').on('click', function() {
        var $btn = $(this);
        var testPhone = $('#testPhoneInput').val();
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Running Ping Test...');

        $.ajax({
            url: "{{ route('admin.whatsapp.test_connection') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                test_phone: testPhone
            },
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa fa-play"></i> Run Live Ping Test');
                var box = $('#testResultBox');
                if (res.success) {
                    box.css({'background': '#ECFDF5', 'color': '#065F46', 'border': '1.5px solid #10B981'}).html(
                        '<strong><i class="fa fa-check-circle" style="color: #059669;"></i> Success (' + res.latency_ms + 'ms):</strong><br>' + res.message
                    ).fadeIn();
                } else {
                    box.css({'background': '#FEF2F2', 'color': '#991B1B', 'border': '1.5px solid #EF4444'}).html(
                        '<strong><i class="fa fa-times-circle" style="color: #DC2626;"></i> Connection Test Failed:</strong><br>' + res.message
                    ).fadeIn();
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fa fa-play"></i> Run Live Ping Test');
                $('#testResultBox').css({'background': '#FEF2F2', 'color': '#991B1B', 'border': '1.5px solid #EF4444'}).html(
                    '<strong><i class="fa fa-times-circle" style="color: #DC2626;"></i> Network Error:</strong> ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText)
                ).fadeIn();
            }
        });
    });
});
</script>
@endpush
@endsection
