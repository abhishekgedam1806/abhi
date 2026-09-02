@extends('admin.layouts.admin_layout')
@section('content')
<style>
/* Modern Admin Dashboard Styling */
.adm-dash-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
}

/* Stat Cards Grid */
.adm-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.adm-stat-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}
.adm-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    border-color: #CBD5E1;
}
.adm-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
}
.adm-stat-green::before { background: #10B981; }
.adm-stat-red::before { background: #EF4444; }
.adm-stat-blue::before { background: #3B82F6; }
.adm-stat-purple::before { background: #8B5CF6; }
.adm-stat-amber::before { background: #F59E0B; }

.adm-stat-num {
    font-size: 26px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.2;
}
.adm-stat-desc {
    font-size: 13px;
    font-weight: 600;
    color: #64748B;
    margin-top: 2px;
}
.adm-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.adm-stat-green .adm-stat-icon { background: #ECFDF5; color: #10B981; }
.adm-stat-red .adm-stat-icon { background: #FEF2F2; color: #EF4444; }
.adm-stat-blue .adm-stat-icon { background: #EFF6FF; color: #3B82F6; }
.adm-stat-purple .adm-stat-icon { background: #F5F3FF; color: #8B5CF6; }
.adm-stat-amber .adm-stat-icon { background: #FFFBEB; color: #F59E0B; }

/* Dashboard Content Cards */
.adm-section-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    margin-bottom: 24px;
    overflow: hidden;
}
.adm-section-header {
    padding: 16px 20px;
    border-bottom: 1px solid #F1F5F9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #FAFAFC;
}
.adm-section-title {
    font-size: 15px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.adm-section-title i {
    color: #2563EB;
}
.adm-see-all-link {
    font-size: 12.5px;
    font-weight: 700;
    color: #2563EB;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: color 0.15s ease;
}
.adm-see-all-link:hover {
    color: #1D4ED8;
}

/* List Items Table / Cards */
.adm-dash-table {
    width: 100%;
    margin-bottom: 0;
}
.adm-dash-table th {
    background: #F8FAFC;
    font-size: 11.5px;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 10px 16px;
    border-bottom: 1px solid #E2E8F0;
    border-top: none !important;
}
.adm-dash-table td {
    padding: 12px 16px;
    vertical-align: middle !important;
    border-top: 1px solid #F1F5F9;
    font-size: 13px;
}
.adm-dash-table tr:hover td {
    background: #F8FAFC;
}

.adm-user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}
.adm-user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #EFF6FF;
    color: #2563EB;
    font-weight: 700;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #DBEAFE;
    flex-shrink: 0;
    overflow: hidden;
}
.adm-user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.adm-user-name {
    font-weight: 700;
    color: #0F172A;
    display: block;
    line-height: 1.3;
    text-decoration: none !important;
}
.adm-user-name:hover {
    color: #2563EB;
}
.adm-user-email {
    font-size: 12px;
    color: #64748B;
}

.adm-job-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}
.adm-job-logo {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
}
.adm-job-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.adm-job-title-link {
    font-weight: 700;
    color: #0F172A;
    display: block;
    line-height: 1.3;
    text-decoration: none !important;
}
.adm-job-title-link:hover {
    color: #2563EB;
}
.adm-job-company {
    font-size: 12px;
    color: #64748B;
    font-weight: 500;
}

/* Action & Status Toggle Buttons */
.btn-toggle-status {
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 11.5px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.15s ease;
}
.status-approved {
    background: #ECFDF5;
    color: #03855c;
    border: 1px solid #A7F3D0;
}
.status-approved:hover {
    background: #D1FAE5;
}
.status-pending {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}
.status-pending:hover {
    background: #FEE2E2;
}

.btn-toggle-feat {
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 11.5px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.15s ease;
}
.feat-active {
    background: #FEF3C7;
    color: #D97706;
    border: 1px solid #FDE68A;
}
.feat-active:hover {
    background: #FDE68A;
}
.feat-inactive {
    background: #F1F5F9;
    color: #64748B;
    border: 1px solid #E2E8F0;
}
.feat-inactive:hover {
    background: #E2E8F0;
}

.btn-quick-icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: #F1F5F9;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    text-decoration: none !important;
    transition: all 0.15s ease;
}
.btn-quick-icon:hover {
    background: #2563EB;
    color: #FFFFFF;
}

/* Toast alert notification */
#adminActionToast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #0F172A;
    color: #FFFFFF;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 600;
    display: none;
    align-items: center;
    gap: 10px;
    z-index: 99999;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
</style>

<div class="page-content-wrapper"> 
    <div class="page-content" style="background-color:#F8FAFC; min-height: 100vh; padding-top: 20px;"> 
        
        <!-- Breadcrumb Bar -->
        <div class="page-bar" style="background: #FFFFFF; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <ul class="page-breadcrumb">
                <li> <a href="{{ route('admin.home') }}" style="color:#64748B;">Home</a> <i class="fa fa-circle" style="font-size:6px; color:#CBD5E1;"></i> </li>
                <li> <span style="font-weight:700; color:#0F172A;">{{ $siteSetting->site_name }} Dashboard</span> </li>
            </ul>
        </div>

        <div class="adm-dash-wrapper">
            <!-- 1. Top Stat Metrics Grid -->
            <div class="adm-stat-grid">
                <a class="adm-stat-card adm-stat-green" href="{{ route('list.users') }}">
                    <div>
                        <div class="adm-stat-num" id="statTodayUsers">{{ $totalTodaysUsers }}</div>
                        <div class="adm-stat-desc">Today's Users</div>
                    </div>
                    <div class="adm-stat-icon"><i class="fa fa-user-plus"></i></div>
                </a>

                <a class="adm-stat-card adm-stat-blue" href="{{ route('list.users') }}">
                    <div>
                        <div class="adm-stat-num" id="statActiveUsers">{{ $totalActiveUsers }}</div>
                        <div class="adm-stat-desc">Active Users</div>
                    </div>
                    <div class="adm-stat-icon"><i class="fa fa-users"></i></div>
                </a>

                <a class="adm-stat-card adm-stat-green" href="{{ route('list.users') }}">
                    <div>
                        <div class="adm-stat-num">{{ $totalVerifiedUsers }}</div>
                        <div class="adm-stat-desc">Verified Users</div>
                    </div>
                    <div class="adm-stat-icon"><i class="fa fa-check-circle"></i></div>
                </a>

                <a class="adm-stat-card adm-stat-amber" href="{{ route('list.jobs') }}">
                    <div>
                        <div class="adm-stat-num" id="statTodayJobs">{{ $totalTodaysJobs }}</div>
                        <div class="adm-stat-desc">Today's Jobs</div>
                    </div>
                    <div class="adm-stat-icon"><i class="fa fa-briefcase"></i></div>
                </a>

                <a class="adm-stat-card adm-stat-blue" href="{{ route('list.jobs') }}">
                    <div>
                        <div class="adm-stat-num" id="statActiveJobs">{{ $totalActiveJobs }}</div>
                        <div class="adm-stat-desc">Active Jobs</div>
                    </div>
                    <div class="adm-stat-icon"><i class="fa fa-list-alt"></i></div>
                </a>

                <a class="adm-stat-card adm-stat-purple" href="{{ route('list.jobs') }}">
                    <div>
                        <div class="adm-stat-num" id="statFeaturedJobs">{{ $totalFeaturedJobs }}</div>
                        <div class="adm-stat-desc">Featured Jobs</div>
                    </div>
                    <div class="adm-stat-icon"><i class="fa fa-star"></i></div>
                </a>
            </div>

            <!-- 2. Main Tables Row -->
            <div class="row">
                {{-- LEFT: Recent Registered Users --}}
                <div class="col-md-6 col-12">
                    <div class="adm-section-card">
                        <div class="adm-section-header">
                            <h3 class="adm-section-title">
                                <i class="fa fa-user"></i> Recent Registered Users
                            </h3>
                            <a href="{{ route('list.users') }}" class="adm-see-all-link">
                                View All ({{ $totalActiveUsers }}) <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                            <table class="table adm-dash-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Location</th>
                                        <th style="text-align:center;">Approval Status</th>
                                        <th style="text-align:right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentUsers as $recentUser)
                                    <tr id="user-row-{{ $recentUser->id }}">
                                        <td>
                                            <div class="adm-user-cell">
                                                <div class="adm-user-avatar">
                                                    @if(!empty($recentUser->image))
                                                        <img src="{{ asset('user_images/'.$recentUser->image) }}" alt="{{ $recentUser->name }}">
                                                    @else
                                                        {{ strtoupper(substr($recentUser->name ?: 'U', 0, 1)) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="{{ route('edit.user', $recentUser->id) }}" class="adm-user-name">
                                                        {{ $recentUser->name ?: 'New Candidate' }}
                                                    </a>
                                                    <span class="adm-user-email">{{ $recentUser->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="color:#475569; font-size:12.5px;">
                                                <i class="fa fa-map-marker" style="color:#94A3B8;"></i> 
                                                {{ $recentUser->getCity('city') ?: ($recentUser->getLocation() ?: 'N/A') }}
                                            </span>
                                            <div style="font-size:11px; color:#94A3B8;">{{ $recentUser->created_at ? $recentUser->created_at->diffForHumans() : '' }}</div>
                                        </td>
                                        <td style="text-align:center;">
                                            @if($recentUser->is_active)
                                                <button type="button" class="btn-toggle-status status-approved" title="Click to Disapprove" onclick="toggleUserApproval({{ $recentUser->id }}, 0, this)">
                                                    <i class="fa fa-check-circle"></i> Approved
                                                </button>
                                            @else
                                                <button type="button" class="btn-toggle-status status-pending" title="Click to Approve" onclick="toggleUserApproval({{ $recentUser->id }}, 1, this)">
                                                    <i class="fa fa-clock-o"></i> Pending (Approve)
                                                </button>
                                            @endif
                                        </td>
                                        <td style="text-align:right;">
                                            <a href="{{ route('edit.user', $recentUser->id) }}" class="btn-quick-icon" title="Edit Profile">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center" style="padding:30px; color:#94A3B8;">No users registered yet.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Recent Jobs --}}
                <div class="col-md-6 col-12">
                    <div class="adm-section-card">
                        <div class="adm-section-header">
                            <h3 class="adm-section-title">
                                <i class="fa fa-briefcase"></i> Recent Job Postings
                            </h3>
                            <a href="{{ route('list.jobs') }}" class="adm-see-all-link">
                                View All ({{ $totalActiveJobs }}) <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                            <table class="table adm-dash-table">
                                <thead>
                                    <tr>
                                        <th>Job & Company</th>
                                        <th style="text-align:center;">Approve</th>
                                        <th style="text-align:center;">Featured</th>
                                        <th style="text-align:right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentJobs as $recentJob)
                                    @php
                                        $company = $recentJob->getCompany();
                                    @endphp
                                    <tr id="job-row-{{ $recentJob->id }}">
                                        <td>
                                            <div class="adm-job-cell">
                                                <div class="adm-job-logo">
                                                    @if($company && !empty($company->logo))
                                                        <img src="{{ asset('company_logos/'.$company->logo) }}" alt="{{ $company->name }}">
                                                    @else
                                                        <i class="fa fa-building-o" style="color:#94A3B8; font-size:16px;"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="{{ route('edit.job', $recentJob->id) }}" class="adm-job-title-link">
                                                        {{ \Illuminate\Support\Str::limit($recentJob->title, 32) }}
                                                    </a>
                                                    <div class="adm-job-company">
                                                        {{ $company ? $company->name : 'Unknown Employer' }} • <i class="fa fa-map-marker" style="font-size:10px;"></i> {{ $recentJob->getCity('city') ?: 'Work From Home' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="text-align:center;">
                                            @if($recentJob->is_active)
                                                <button type="button" class="btn-toggle-status status-approved" title="Click to Deactivate" onclick="toggleJobApproval({{ $recentJob->id }}, 0, this)">
                                                    <i class="fa fa-check-circle"></i> Active
                                                </button>
                                            @else
                                                <button type="button" class="btn-toggle-status status-pending" title="Click to Approve" onclick="toggleJobApproval({{ $recentJob->id }}, 1, this)">
                                                    <i class="fa fa-clock-o"></i> Approve
                                                </button>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            @if($recentJob->is_featured)
                                                <button type="button" class="btn-toggle-feat feat-active" title="Click to Unfeature" onclick="toggleJobFeaturedStatus({{ $recentJob->id }}, 0, this)">
                                                    <i class="fa fa-star"></i> Featured
                                                </button>
                                            @else
                                                <button type="button" class="btn-toggle-feat feat-inactive" title="Click to Make Featured" onclick="toggleJobFeaturedStatus({{ $recentJob->id }}, 1, this)">
                                                    <i class="fa fa-star-o"></i> Make Feat.
                                                </button>
                                            @endif
                                        </td>
                                        <td style="text-align:right;">
                                            <a href="{{ route('edit.job', $recentJob->id) }}" class="btn-quick-icon" title="Edit Job Details">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center" style="padding:30px; color:#94A3B8;">No jobs posted yet.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Toast Feedback -->
<div id="adminActionToast">
    <i class="fa fa-check-circle text-success"></i>
    <span id="adminActionToastText">Updated successfully</span>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
function showAdminToast(msg, isSuccess = true) {
    var toast = $('#adminActionToast');
    var icon = isSuccess ? '<i class="fa fa-check-circle text-success"></i> ' : '<i class="fa fa-exclamation-circle text-danger"></i> ';
    toast.html(icon + msg);
    toast.stop(true, true).fadeIn(200).delay(2500).fadeOut(300);
}

// 1. Toggle User Approval / Active
function toggleUserApproval(userId, targetStatus, btn) {
    var $btn = $(btn);
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
    var url = (targetStatus === 1) ? "{{ route('make.active.user') }}" : "{{ route('make.not.active.user') }}";

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            id: userId,
            _method: 'PUT',
            _token: '{{ csrf_token() }}'
        },
        success: function (res) {
            $btn.prop('disabled', false);
            if (targetStatus === 1) {
                $btn.removeClass('status-pending').addClass('status-approved');
                $btn.attr('onclick', 'toggleUserApproval(' + userId + ', 0, this)');
                $btn.html('<i class="fa fa-check-circle"></i> Approved');
                showAdminToast('User #' + userId + ' has been Approved (Active)!');
            } else {
                $btn.removeClass('status-approved').addClass('status-pending');
                $btn.attr('onclick', 'toggleUserApproval(' + userId + ', 1, this)');
                $btn.html('<i class="fa fa-clock-o"></i> Pending (Approve)');
                showAdminToast('User #' + userId + ' status changed to In-Active.');
            }
        },
        error: function () {
            $btn.prop('disabled', false);
            showAdminToast('Failed to update user status.', false);
        }
    });
}

// 2. Toggle Job Approval / Active
function toggleJobApproval(jobId, targetStatus, btn) {
    var $btn = $(btn);
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
    var url = (targetStatus === 1) ? "{{ route('make.active.job') }}" : "{{ route('make.not.active.job') }}";

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            id: jobId,
            _method: 'PUT',
            _token: '{{ csrf_token() }}'
        },
        success: function (res) {
            $btn.prop('disabled', false);
            if (targetStatus === 1) {
                $btn.removeClass('status-pending').addClass('status-approved');
                $btn.attr('onclick', 'toggleJobApproval(' + jobId + ', 0, this)');
                $btn.html('<i class="fa fa-check-circle"></i> Active');
                showAdminToast('Job #' + jobId + ' has been Approved and Activated!');
            } else {
                $btn.removeClass('status-approved').addClass('status-pending');
                $btn.attr('onclick', 'toggleJobApproval(' + jobId + ', 1, this)');
                $btn.html('<i class="fa fa-clock-o"></i> Approve');
                showAdminToast('Job #' + jobId + ' has been Inactivated.');
            }
        },
        error: function () {
            $btn.prop('disabled', false);
            showAdminToast('Failed to update job approval status.', false);
        }
    });
}

// 3. Toggle Job Featured Status
function toggleJobFeaturedStatus(jobId, targetStatus, btn) {
    var $btn = $(btn);
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
    var url = (targetStatus === 1) ? "{{ route('make.featured.job') }}" : "{{ route('make.not.featured.job') }}";

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            id: jobId,
            _method: 'PUT',
            _token: '{{ csrf_token() }}'
        },
        success: function (res) {
            $btn.prop('disabled', false);
            if (targetStatus === 1) {
                $btn.removeClass('feat-inactive').addClass('feat-active');
                $btn.attr('onclick', 'toggleJobFeaturedStatus(' + jobId + ', 0, this)');
                $btn.html('<i class="fa fa-star"></i> Featured');
                showAdminToast('Job #' + jobId + ' is now Marked as Featured!');
            } else {
                $btn.removeClass('feat-active').addClass('feat-inactive');
                $btn.attr('onclick', 'toggleJobFeaturedStatus(' + jobId + ', 1, this)');
                $btn.html('<i class="fa fa-star-o"></i> Make Feat.');
                showAdminToast('Job #' + jobId + ' removed from Featured.');
            }
        },
        error: function () {
            $btn.prop('disabled', false);
            showAdminToast('Failed to update featured status.', false);
        }
    });
}
</script>
@endpush