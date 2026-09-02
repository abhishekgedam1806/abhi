@extends('admin.layouts.admin_layout')
@section('content')
<style type="text/css">
    .table td, .table th {
        font-size: 13px;
        vertical-align: middle !important;
    }
    .stat-card-box {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .stat-title {
        font-size: 12px;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: #0F172A;
        margin-top: 4px;
    }
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        
        <!-- BEGIN PAGE HEADER-->
        <h1 class="page-title">
            <i class="fa fa-credit-card text-primary"></i> Payment & Order Management
            <small>Manage transactions, Razorpay payments, and refunds</small>
        </h1>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><a href="{{ route('admin.home') }}">Home</a><i class="fa fa-angle-right"></i></li>
                <li><span>Payments</span></li>
            </ul>
        </div>
        <!-- END PAGE HEADER-->

        <!-- Stat Counter Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card-box">
                    <div class="stat-title">Total Revenue (Paid)</div>
                    <div class="stat-value text-success">₹{{ number_format($statusCounts['total_revenue'] ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card-box">
                    <div class="stat-title">Paid Orders</div>
                    <div class="stat-value text-primary">{{ $statusCounts['paid'] ?? 0 }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card-box">
                    <div class="stat-title">Pending Orders</div>
                    <div class="stat-value text-warning">{{ $statusCounts['pending'] ?? 0 }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card-box">
                    <div class="stat-title">Failed / Refunded</div>
                    <div class="stat-value text-danger">{{ ($statusCounts['failed'] ?? 0) + ($statusCounts['refunded'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="icon-list font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase">Transaction History</span>
                        </div>
                        <div class="actions">
                            <a href="{{ route('edit.site.setting') }}#paymentGateways" class="btn btn-circle btn-default">
                                <i class="fa fa-cog"></i> Gateway API Settings
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        
                        {{-- Filter Bar --}}
                        <div class="row" style="margin-bottom: 20px; background: #F8FAFC; padding: 16px; border-radius: 10px; border: 1px solid #E2E8F0;">
                            <div class="col-md-3 col-sm-6 mb-2">
                                <label style="font-size: 12px; font-weight: 700; color: #475569;">Search Order / Buyer / ID</label>
                                <input type="text" id="filter_search" class="form-control input-sm" placeholder="Order ID, Gateway ID, Title...">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <label style="font-size: 12px; font-weight: 700; color: #475569;">Payment Status</label>
                                <select id="filter_status" class="form-control input-sm">
                                    <option value="">All Statuses</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <label style="font-size: 12px; font-weight: 700; color: #475569;">Gateway</label>
                                <select id="filter_gateway" class="form-control input-sm">
                                    <option value="">All Gateways</option>
                                    <option value="razorpay">Razorpay</option>
                                    <option value="phonepe">PhonePe</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="stripe">Stripe</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2" style="display: flex; align-items: flex-end; gap: 8px;">
                                <button id="btn_filter" class="btn btn-sm btn-primary" style="font-weight: 700;">
                                    <i class="fa fa-filter"></i> Apply Filter
                                </button>
                                <button id="btn_reset" class="btn btn-sm btn-default">
                                    Reset
                                </button>
                            </div>
                        </div>

                        {{-- DataTable --}}
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="payment_orders_datatable">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th>Order Number</th>
                                        <th>Buyer / Company</th>
                                        <th>Package & Amount</th>
                                        <th>Gateway & ID</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    var oTable = $('#payment_orders_datatable').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        searching: false,
        ajax: {
            url: '{!! route("admin.payment.fetchData") !!}',
            data: function (d) {
                d.search_term = $('#filter_search').val();
                d.status = $('#filter_status').val();
                d.gateway = $('#filter_gateway').val();
            }
        },
        columns: [
            {data: 'order_number', name: 'order_number'},
            {data: 'buyer_info', name: 'buyer_info', orderable: false},
            {data: 'package_info', name: 'package_info', orderable: false},
            {data: 'gateway_info', name: 'gateway_info', orderable: false},
            {data: 'status_badge', name: 'status_badge', orderable: false},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[5, 'desc']]
    });

    $('#btn_filter').on('click', function (e) {
        oTable.draw();
        e.preventDefault();
    });

    $('#btn_reset').on('click', function (e) {
        $('#filter_search').val('');
        $('#filter_status').val('');
        $('#filter_gateway').val('');
        oTable.draw();
        e.preventDefault();
    });
});
</script>
@endpush
