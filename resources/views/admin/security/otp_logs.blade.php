@extends('admin.layouts.admin_layout')

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><a href="{{ route('admin.home') }}">Home</a> <i class="fa fa-circle"></i></li>
                <li><a href="{{ route('admin.smtp.settings') }}">SMTP Settings</a> <i class="fa fa-circle"></i></li>
                <li><span>OTP & Anti-Fraud Security Logs</span></li>
            </ul>
        </div>
        <br />
        @include('flash::message')

        {{-- KPI Metrics Row --}}
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 bordered">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-green-sharp"><span data-counter="counterup">{{ $totalOtps }}</span></h3>
                            <small>TOTAL OTPS GENERATED</small>
                        </div>
                        <div class="icon"><i class="fa fa-envelope-o font-green-sharp"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 bordered">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-blue-sharp"><span data-counter="counterup">{{ $todayOtps }}</span></h3>
                            <small>TODAY'S OTPS</small>
                        </div>
                        <div class="icon"><i class="fa fa-calendar font-blue-sharp"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 bordered">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-red-haze"><span data-counter="counterup">{{ $failedAttempts }}</span></h3>
                            <small>FAILED ATTEMPTS</small>
                        </div>
                        <div class="icon"><i class="fa fa-shield font-red-haze"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 bordered">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-purple-soft"><span data-counter="counterup">{{ count($blockedDomains) }}</span></h3>
                            <small>BLOCKED SPAM DOMAINS</small>
                        </div>
                        <div class="icon"><i class="fa fa-ban font-purple-soft"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- OTP Authentication Logs --}}
            <div class="col-md-8">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-list-alt font-dark"></i>
                            <span class="caption-subject bold uppercase">Recent Login OTP & Security Logs</span>
                        </div>
                        <div class="actions">
                            <a href="{{ route('admin.smtp.settings') }}" class="btn btn-sm btn-outline dark">
                                <i class="fa fa-envelope"></i> SMTP Settings
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        {{-- Search & Filter Form --}}
                        <form method="GET" action="{{ route('admin.otp.logs') }}" class="form-inline" style="margin-bottom: 15px;">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control input-sm" placeholder="Search email or IP..." value="{{ request('search') }}">
                            </div>
                            <div class="form-group">
                                <select name="user_type" class="form-control input-sm">
                                    <option value="">All Roles</option>
                                    <option value="candidate" {{ request('user_type') == 'candidate' ? 'selected' : '' }}>Candidate</option>
                                    <option value="employer" {{ request('user_type') == 'employer' ? 'selected' : '' }}>Employer</option>
                                    <option value="business" {{ request('user_type') == 'business' ? 'selected' : '' }}>Business</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-sm blue"><i class="fa fa-search"></i> Filter</button>
                            <a href="{{ route('admin.otp.logs') }}" class="btn btn-sm default">Reset</a>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Email Address</th>
                                        <th>Role</th>
                                        <th>IP Address</th>
                                        <th>Attempts</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td><strong>{{ $log->email }}</strong></td>
                                        <td>
                                            @if($log->user_type == 'candidate')
                                                <span class="label label-info">Candidate</span>
                                            @elseif($log->user_type == 'employer')
                                                <span class="label label-primary">Employer</span>
                                            @else
                                                <span class="label label-warning">Business</span>
                                            @endif
                                        </td>
                                        <td><code>{{ $log->ip_address ?: 'Unknown' }}</code></td>
                                        <td>
                                            @if($log->attempts > 0)
                                                <span class="badge badge-danger">{{ $log->attempts }} failed</span>
                                            @else
                                                <span class="badge badge-success">0</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->is_used)
                                                <span class="label label-success"><i class="fa fa-check"></i> Verified</span>
                                            @elseif($log->expires_at && $log->expires_at->isPast())
                                                <span class="label label-default">Expired</span>
                                            @else
                                                <span class="label label-info"><i class="fa fa-clock-o"></i> Active</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No OTP logs found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            {!! $logs->appends(request()->query())->links() !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Custom Blocked Email Domains Manager --}}
            <div class="col-md-4">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-ban font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase">Custom Blocked Domains</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <p style="font-size: 12.5px; color: #666;">
                            Add specific suspicious email domains to permanently ban them from requesting OTPs or creating accounts on your portal.
                        </p>

                        <form method="POST" action="{{ route('admin.add.blocked.domain') }}">
                            @csrf
                            <div class="form-group">
                                <label class="bold">Domain Name (e.g. badsite.com)</label>
                                <input type="text" name="domain" class="form-control" required placeholder="domain.com">
                            </div>
                            <div class="form-group">
                                <label class="bold">Reason / Note</label>
                                <input type="text" name="reason" class="form-control" placeholder="e.g. Fraud spam source">
                            </div>
                            <button type="submit" class="btn btn-sm red"><i class="fa fa-plus"></i> Block Domain</button>
                        </form>

                        <hr />
                        <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 10px;">Currently Blocked Custom Domains:</h4>
                        <div style="max-height: 300px; overflow-y: auto;">
                            <ul class="list-group">
                                @forelse($blockedDomains as $bDomain)
                                <li class="list-group-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px;">
                                    <div>
                                        <strong>{{ $bDomain->domain }}</strong>
                                        @if($bDomain->reason)
                                            <br><small class="text-muted">{{ $bDomain->reason }}</small>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('admin.delete.blocked.domain', $bDomain->id) }}" style="display:inline;" onsubmit="return confirm('Remove {{ $bDomain->domain }} from block list?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-outline red" title="Delete"><i class="fa fa-trash"></i></button>
                                    </form>
                                </li>
                                @empty
                                <li class="list-group-item text-center text-muted" style="font-size: 12.5px;">No custom domains blocked yet. 200+ popular disposable providers are auto-blocked by system.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
