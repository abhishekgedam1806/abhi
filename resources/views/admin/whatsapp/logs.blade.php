@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content" style="background-color: #F8FAFC; min-height: 100vh; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
        <div class="wa-dashboard-container" style="max-width: 1400px; margin: 0 auto; padding-bottom: 50px;">
            
            <!-- Breadcrumb & Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #64748B; margin-bottom: 4px;">
                        <a href="{{ route('admin.home') }}" style="color: #64748B; text-decoration: none;">Dashboard</a>
                        <span>/</span>
                        <a href="{{ route('admin.whatsapp.index') }}" style="color: #64748B; text-decoration: none;">WhatsApp Desk</a>
                        <span>/</span>
                        <span style="color: #0F172A; font-weight: 600;">Delivery Logs</span>
                    </div>
                    <h1 style="font-size: 24px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.5px;">
                        WhatsApp Delivery & Audit Logs
                    </h1>
                </div>

                <div>
                    <a href="{{ route('admin.whatsapp.index') }}" class="btn btn-default" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 10px; font-weight: 700; color: #334155; padding: 9px 18px;">
                        <i class="fa fa-arrow-left" style="margin-right: 6px;"></i> Back to Overview
                    </a>
                </div>
            </div>

            @include('flash::message')

            <!-- Filter Card -->
            <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                <form action="{{ route('admin.whatsapp.logs') }}" method="GET">
                    <div class="row" style="display: flex; align-items: flex-end; flex-wrap: wrap;">
                        <div class="col-md-3 col-sm-6" style="margin-bottom: 10px;">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 5px; display: block;">Search Phone / Message / ID:</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="e.g. +91987... or Rahul" style="border: 1.5px solid #CBD5E1; border-radius: 8px; height: 40px; font-size: 13px;">
                        </div>
                        <div class="col-md-2 col-sm-6" style="margin-bottom: 10px;">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 5px; display: block;">Delivery Status:</label>
                            <select name="status" class="form-control" style="border: 1.5px solid #CBD5E1; border-radius: 8px; height: 40px; font-size: 13px;">
                                <option value="">All Statuses</option>
                                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read &#10003;&#10003;</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="queued" {{ request('status') == 'queued' ? 'selected' : '' }}>Queued</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6" style="margin-bottom: 10px;">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 5px; display: block;">Template Event:</label>
                            <select name="template_key" class="form-control" style="border: 1.5px solid #CBD5E1; border-radius: 8px; height: 40px; font-size: 13px;">
                                <option value="">All Templates</option>
                                @foreach($templates as $k => $t)
                                    <option value="{{ $k }}" {{ request('template_key') == $k ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6" style="margin-bottom: 10px;">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 5px; display: block;">Recipient Type:</label>
                            <select name="recipient_type" class="form-control" style="border: 1.5px solid #CBD5E1; border-radius: 8px; height: 40px; font-size: 13px;">
                                <option value="">All Recipients</option>
                                <option value="user" {{ request('recipient_type') == 'user' ? 'selected' : '' }}>Candidate</option>
                                <option value="company" {{ request('recipient_type') == 'company' ? 'selected' : '' }}>Employer</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12" style="margin-bottom: 10px;">
                            <div style="display: flex; gap: 6px;">
                                <button type="submit" class="btn" style="background: #059669; color: #FFFFFF; font-weight: 700; border-radius: 8px; height: 40px; padding: 0 16px; flex: 1;">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('admin.whatsapp.logs') }}" class="btn btn-default" style="border: 1.5px solid #E2E8F0; border-radius: 8px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Logs Table Card -->
            <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">
                        Full Telemetry Records ({{ $logs->total() }})
                    </h3>
                </div>

                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0; vertical-align: middle;">
                        <thead>
                            <tr style="border-bottom: 1.5px solid #E2E8F0; color: #64748B; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                <th style="padding: 12px 10px;">ID</th>
                                <th style="padding: 12px 10px;">Recipient</th>
                                <th style="padding: 12px 10px;">Phone Number</th>
                                <th style="padding: 12px 10px;">Template & Event</th>
                                <th style="padding: 12px 10px;">Provider ID</th>
                                <th style="padding: 12px 10px;">Status</th>
                                <th style="padding: 12px 10px;">Attempts</th>
                                <th style="padding: 12px 10px;">Time</th>
                                <th style="padding: 12px 10px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr style="border-bottom: 1px solid #F1F5F9; font-size: 13px;">
                                    <td style="padding: 14px 10px; font-weight: 700; color: #64748B;">#{{ $log->id }}</td>
                                    <td style="padding: 14px 10px;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $log->notifiable_type === 'company' ? '#F0FDF4' : '#EFF6FF' }}; color: {{ $log->notifiable_type === 'company' ? '#15803D' : '#1D4ED8' }}; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; flex-shrink: 0;">
                                                {{ strtoupper(substr($log->recipient_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 700; color: #0F172A; line-height: 1.2;">{{ $log->recipient_name }}</div>
                                                <span style="font-size: 11px; color: #64748B; text-transform: capitalize;">{{ $log->notifiable_type }} #{{ $log->notifiable_id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 14px 10px;">
                                        <code style="background: #F8FAFC; border: 1px solid #E2E8F0; color: #0F172A; padding: 3px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                            {{ $log->recipient_phone }}
                                        </code>
                                    </td>
                                    <td style="padding: 14px 10px;">
                                        <div style="font-weight: 700; color: #2563EB;">{{ ucwords(str_replace('_', ' ', $log->template_key)) }}</div>
                                        <small style="color: #64748B; font-size: 11px;">{{ $log->event_type }}</small>
                                    </td>
                                    <td style="padding: 14px 10px;">
                                        <span style="background: #F8FAFC; color: #475569; border: 1px solid #E2E8F0; font-size: 10.5px; font-weight: 700; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                            {{ $log->provider }}
                                        </span>
                                        @if(!empty($log->provider_message_id))
                                            <div style="font-size: 10.5px; color: #94A3B8; margin-top: 2px;">{{ \Illuminate\Support\Str::limit($log->provider_message_id, 14) }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 14px 10px;">
                                        @if($log->status == 'sent')
                                            <span style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fa fa-paper-plane" style="font-size: 10px;"></i> Sent
                                            </span>
                                        @elseif($log->status == 'delivered')
                                            <span style="background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                                &#10003;&#10003; Delivered
                                            </span>
                                        @elseif($log->status == 'read')
                                            <span style="background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                                <span style="color: #0284C7; font-weight: 900;">&#10003;&#10003;</span> Read
                                            </span>
                                        @elseif($log->status == 'queued')
                                            <span style="background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fa fa-clock-o" style="font-size: 10px;"></i> Queued
                                            </span>
                                        @else
                                            <span style="background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;" title="{{ $log->error_message }}">
                                                <i class="fa fa-times-circle" style="font-size: 10px;"></i> Failed
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 14px 10px;">
                                        <span style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 11.5px; font-weight: 600; padding: 2px 8px; border-radius: 6px;">
                                            {{ $log->attempts }}/{{ $log->max_attempts }}
                                        </span>
                                    </td>
                                    <td style="padding: 14px 10px; font-size: 11.5px; color: #64748B;">
                                        {{ $log->created_at ? $log->created_at->format('d M Y, h:i A') : '-' }}
                                    </td>
                                    <td style="padding: 14px 10px; text-align: center;">
                                        <div style="display: flex; gap: 6px; justify-content: center;">
                                            <button type="button" class="btn btn-xs btn-default btn-view-msg" 
                                                    data-msg="{{ $log->rendered_message }}"
                                                    data-payload="{{ json_encode($log->payload, JSON_PRETTY_PRINT) }}"
                                                    data-error="{{ $log->error_message }}"
                                                    data-phone="{{ $log->recipient_phone }}"
                                                    data-title="{{ ucwords(str_replace('_', ' ', $log->template_key)) }}"
                                                    style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 6px; padding: 4px 8px;"
                                                    title="View Rendered Message">
                                                <i class="fa fa-eye" style="color: #2563EB;"></i>
                                            </button>

                                            @if($log->status == 'failed')
                                                <form action="{{ route('admin.whatsapp.logs.resend', $log->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-danger" style="border-radius: 6px; padding: 4px 8px;" title="Retry Delivery">
                                                        <i class="fa fa-refresh"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 50px 20px; color: #94A3B8;">
                                        <i class="fa fa-inbox" style="font-size: 36px; color: #CBD5E1; margin-bottom: 12px; display: block;"></i>
                                        <div style="font-weight: 700; font-size: 14px; color: #334155;">No records match your filters</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 20px; text-align: center;">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Message Inspector Modal -->
<div class="modal fade" id="modalViewMsg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 18px; border: none; box-shadow: 0 15px 50px rgba(0,0,0,0.15); overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #064E3B 0%, #047857 100%); color: #FFFFFF; padding: 20px 24px; border: none;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #FFF; opacity: 0.8; font-size: 24px;">&times;</button>
                <h4 class="modal-title" style="font-weight: 800; font-size: 18px; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="fa fa-whatsapp" style="color: #25D366; font-size: 22px;"></i> <span id="vTitle">Rendered Message</span>
                </h4>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <p style="font-size: 13px; color: #475569; margin-bottom: 12px;">
                    <b>Recipient Phone:</b> <code id="vPhone" style="font-size: 13px; font-weight: 700; color: #0F172A; background: #F1F5F9; padding: 2px 8px; border-radius: 6px;"></code>
                </p>

                <!-- WhatsApp Bubble -->
                <div style="background: #E5DDD5; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                    <div style="background: #FFFFFF; border-radius: 8px 8px 8px 2px; padding: 14px; box-shadow: 0 1px 2px rgba(0,0,0,0.12); font-size: 13px; color: #111827; line-height: 1.6; white-space: pre-wrap; word-break: break-word;" id="vMsgBody">
                    </div>
                </div>

                <div id="vErrorContainer" style="display: none; background: #FEF2F2; border: 1.5px solid #EF4444; border-radius: 10px; padding: 14px; margin-bottom: 16px;">
                    <strong style="color: #991B1B; font-size: 13px; display: block; margin-bottom: 4px;">
                        <i class="fa fa-exclamation-triangle"></i> Delivery Failure Reason:
                    </strong>
                    <div style="color: #B91C1C; font-size: 12px; word-break: break-all;" id="vErrorText"></div>
                </div>

                <div>
                    <label style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase;">Variables Payload:</label>
                    <pre id="vPayload" style="font-size: 11.5px; max-height: 140px; overflow: auto; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 8px; padding: 10px;"></pre>
                </div>
            </div>
            <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 16px 24px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 700;">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-view-msg').on('click', function() {
        var msg = $(this).data('msg');
        var payload = $(this).data('payload');
        var error = $(this).data('error');
        var phone = $(this).data('phone');
        var title = $(this).data('title');

        $('#vTitle').text(title);
        $('#vPhone').text(phone);
        $('#vMsgBody').text(msg || 'No rendered message body saved.');
        $('#vPayload').text(payload || '{}');

        if (error) {
            $('#vErrorText').text(error);
            $('#vErrorContainer').show();
        } else {
            $('#vErrorContainer').hide();
        }

        $('#modalViewMsg').modal('show');
    });
});
</script>
@endpush
@endsection
