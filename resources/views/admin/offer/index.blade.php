@extends('admin.layouts.admin_layout')
@section('content')
<style>
.kpi-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 18px 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s;
}
.kpi-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    border-color: #CBD5E1;
}
.kpi-icon-wrap {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.kpi-val {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.1;
}
.kpi-lbl {
    font-size: 12.5px;
    color: #64748B;
    font-weight: 600;
    margin-top: 2px;
}
.code-pill {
    background: #EFF6FF;
    border: 1px dashed #3B82F6;
    color: #1E40AF;
    font-family: monospace;
    font-size: 13px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-block;
}
.badge-status {
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
}
.badge-status-active { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
.badge-status-scheduled { background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; }
.badge-status-expired { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
.badge-status-disabled { background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; }
.badge-status-exhausted { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-bar" style="background: transparent; border: none; box-shadow: none; padding: 0 0 16px 0;">
            <ul class="page-breadcrumb" style="padding: 0; margin: 0; font-size: 13px;">
                <li><a href="{{ route('admin.home') }}" style="color: #64748B; text-decoration: none;"><i class="fa fa-home"></i> Home</a> <i class="fa fa-angle-right" style="color: #CBD5E1; margin: 0 6px;"></i></li>
                <li><a href="{{ route('admin.payment.index') }}" style="color: #64748B; text-decoration: none;">Payment Management</a> <i class="fa fa-angle-right" style="color: #CBD5E1; margin: 0 6px;"></i></li>
                <li><span style="color: #0F172A; font-weight: 700;">Offers & Coupons</span></li>
            </ul>
        </div>

        @include('flash::message')

        <!-- Header Title & Action -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h1 class="page-title" style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0;">
                    <i class="fa fa-tags text-primary"></i> Promotional Offers & Coupons
                </h1>
                <p style="font-size: 13px; color: #64748B; margin: 4px 0 0 0;">
                    Manage discount campaigns, generate unique promo codes, and track customer redemptions.
                </p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.coupon-redemptions.index') }}" class="btn btn-default" style="border-radius: 8px; font-weight: 700; background: #FFFFFF; border: 1px solid #CBD5E1; color: #334155; padding: 9px 16px;">
                    <i class="fa fa-history"></i> View Redemptions
                </a>
                <a href="{{ route('admin.offers.create') }}" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; font-weight: 700; padding: 9px 18px; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                    <i class="fa fa-plus"></i> Create New Offer
                </a>
            </div>
        </div>

        <!-- KPI Metrics Grid -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="kpi-card">
                    <div class="kpi-icon-wrap" style="background: #EFF6FF; color: #2563EB;">
                        <i class="fa fa-bullhorn"></i>
                    </div>
                    <div>
                        <div class="kpi-val">{{ $activeOffersCount }} / {{ $totalOffersCount }}</div>
                        <div class="kpi-lbl">Active Campaigns</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="kpi-card">
                    <div class="kpi-icon-wrap" style="background: #FAF5FF; color: #9333EA;">
                        <i class="fa fa-ticket"></i>
                    </div>
                    <div>
                        <div class="kpi-val">{{ $activeCouponsCount }}</div>
                        <div class="kpi-lbl">Active Coupon Codes</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="kpi-card">
                    <div class="kpi-icon-wrap" style="background: #ECFDF5; color: #059669;">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="kpi-val">{{ number_format($totalRedemptionsCount) }}</div>
                        <div class="kpi-lbl">Total Redemptions</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="kpi-card">
                    <div class="kpi-icon-wrap" style="background: #FEF3C7; color: #D97706;">
                        <i class="fa fa-inr"></i>
                    </div>
                    <div>
                        <div class="kpi-val">₹{{ number_format($totalDiscountGiven, 0) }}</div>
                        <div class="kpi-lbl">Total Discount Given</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="portlet light bordered" style="border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; background: #FFFFFF;">
            <form method="GET" action="{{ route('admin.offers.index') }}" class="form-inline" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                <div class="form-group" style="flex: 1; min-width: 240px; margin: 0;">
                    <input type="text" name="search" value="{{ Request::get('search') }}" class="form-control input-medium" placeholder="Search Offer Name, Description, or Code..." style="width: 100%; border-radius: 8px; border: 1px solid #CBD5E1;">
                </div>
                <div class="form-group" style="margin: 0;">
                    <select name="status" class="form-control" style="border-radius: 8px; border: 1px solid #CBD5E1;">
                        <option value="">All Statuses</option>
                        <option value="active" {{ Request::get('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="disabled" {{ Request::get('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-default" style="border-radius: 8px; font-weight: 700; background: #F8FAFC; border: 1px solid #CBD5E1;">
                    <i class="fa fa-filter"></i> Filter
                </button>
                @if(Request::filled('search') || Request::filled('status'))
                <a href="{{ route('admin.offers.index') }}" class="btn btn-link" style="color: #64748B; font-size: 13px;">Clear Filter</a>
                @endif
            </form>
        </div>

        <!-- Offers Table -->
        <div class="portlet light bordered" style="border: 1px solid #E2E8F0; border-radius: 12px; padding: 0; overflow: hidden; background: #FFFFFF;">
            <div class="table-responsive">
                <table class="table table-hover" style="margin: 0; vertical-align: middle;">
                    <thead style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0;">
                        <tr>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Offer / Campaign</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Coupon Code</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Discount</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Validity</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Usage</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase;">Status</th>
                            <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            @php
                                $coupon = $offer->coupons->first();
                                $computedStatus = $offer->computed_status;
                                $statusClass = 'badge-status-' . strtolower($computedStatus);
                            @endphp
                            <tr style="border-bottom: 1px solid #F1F5F9;">
                                <td style="padding: 14px 18px;">
                                    <div style="font-weight: 800; color: #0F172A; font-size: 14px;">{{ $offer->name }}</div>
                                    @if($offer->description)
                                    <div style="font-size: 12px; color: #64748B; margin-top: 2px;">{{ Str::limit($offer->description, 50) }}</div>
                                    @endif
                                </td>
                                <td style="padding: 14px 18px;">
                                    @if($coupon)
                                        <span class="code-pill">{{ $coupon->code }}</span>
                                    @else
                                        <span style="color: #94A3B8; font-size: 12px;">No Code</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 18px;">
                                    @if($coupon)
                                        <div style="font-weight: 700; color: #03855c;">
                                            {{ $coupon->formatted_discount }}
                                        </div>
                                        @if($coupon->min_order_value > 0)
                                        <div style="font-size: 11px; color: #64748B;">Min Order: ₹{{ number_format($coupon->min_order_value, 0) }}</div>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="padding: 14px 18px; font-size: 12.5px; color: #334155;">
                                    @if($offer->starts_at || $offer->expires_at)
                                        <div><i class="fa fa-calendar-o" style="color: #64748B;"></i> {{ $offer->starts_at ? $offer->starts_at->format('d M Y') : 'Start' }} &rarr; {{ $offer->expires_at ? $offer->expires_at->format('d M Y') : 'Ongoing' }}</div>
                                    @else
                                        <span style="color: #64748B;">Always Valid</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 18px;">
                                    @if($coupon)
                                        <div style="font-weight: 700; color: #1E293B;">
                                            {{ $coupon->used_count }} / {{ $coupon->total_usage_limit ?? '∞' }}
                                        </div>
                                        <div style="font-size: 11px; color: #64748B;">{{ $coupon->per_user_usage_limit }} per user</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="padding: 14px 18px;">
                                    <span class="badge-status {{ $statusClass }}">
                                        {{ $computedStatus }}
                                    </span>
                                </td>
                                <td style="padding: 14px 18px; text-align: right;">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.offers.edit', $offer->id) }}" class="btn btn-xs btn-default" title="Edit Offer" style="border-radius: 4px; padding: 4px 8px;">
                                            <i class="fa fa-pencil text-primary"></i>
                                        </a>
                                        
                                        <!-- Toggle Status Form -->
                                        <form method="POST" action="{{ route('admin.offers.toggle-status', $offer->id) }}" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-default" title="{{ $offer->status == 'active' ? 'Disable Offer' : 'Enable Offer' }}" style="border-radius: 4px; padding: 4px 8px;">
                                                <i class="fa {{ $offer->status == 'active' ? 'fa-pause text-warning' : 'fa-play text-success' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Duplicate Form -->
                                        <form method="POST" action="{{ route('admin.offers.duplicate', $offer->id) }}" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-default" title="Duplicate Campaign" onclick="return confirm('Duplicate this offer and generate a new code?')" style="border-radius: 4px; padding: 4px 8px;">
                                                <i class="fa fa-clone text-info"></i>
                                            </button>
                                        </form>

                                        <!-- Delete Form -->
                                        <form method="POST" action="{{ route('admin.offers.destroy', $offer->id) }}" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-default" title="Delete / Archive Offer" onclick="return confirm('Are you sure you want to delete or archive this offer?')" style="border-radius: 4px; padding: 4px 8px;">
                                                <i class="fa fa-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px 20px; color: #64748B;">
                                    <i class="fa fa-tags" style="font-size: 36px; color: #CBD5E1; margin-bottom: 10px; display: block;"></i>
                                    <div style="font-size: 15px; font-weight: 700; color: #1E293B;">No Promotional Offers Found</div>
                                    <p style="font-size: 13px; margin-top: 4px;">Click the button below to create your first discount campaign.</p>
                                    <a href="{{ route('admin.offers.create') }}" class="btn btn-primary btn-sm" style="background: #2563EB; border-color: #2563EB; border-radius: 6px; font-weight: 700; margin-top: 8px;">
                                        <i class="fa fa-plus"></i> Create Offer
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($offers->hasPages())
            <div style="padding: 14px 18px; border-top: 1px solid #F1F5F9; background: #F8FAFC;">
                {!! $offers->appends(Request::all())->links() !!}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
