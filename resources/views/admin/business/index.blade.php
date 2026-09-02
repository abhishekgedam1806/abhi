@extends('admin.layouts.admin_layout')
@section('content')
<style>
/* Admin Business Management UI */
.biz-admin-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
    padding: 0 0 20px 0;
}
.biz-admin-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.biz-admin-page-title {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.biz-admin-page-title .title-icon {
    width: 38px;
    height: 38px;
    background: #2563EB;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 16px;
    box-shadow: 0 4px 12px rgba(37,99,235,0.25);
}
.btn-bulk-delete {
    display: none;
    align-items: center;
    gap: 6px;
    background: #FEF2F2;
    color: #DC2626 !important;
    border: 1px solid #FECACA;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 16px;
    border-radius: 10px;
    cursor: pointer;
}
.btn-bulk-delete:hover {
    background: #DC2626;
    color: #fff !important;
}
.biz-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.biz-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1px solid #F1F5F9;
    background: #FAFBFC;
}
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="biz-admin-wrapper">
            {{-- Breadcrumb --}}
            <div style="font-size:13px;color:#94A3B8;margin-bottom:20px;display:flex;align-items:center;gap:6px;">
                <a href="{{ route('admin.home') }}" style="color:#64748B;text-decoration:none;"><i class="fa fa-home"></i> Home</a>
                <i class="fa fa-angle-right" style="font-size:8px;"></i>
                <span>Business Directory</span>
            </div>

            {{-- Header --}}
            <div class="biz-admin-header-row">
                <div>
                    <h1 class="biz-admin-page-title">
                        <span class="title-icon"><i class="fa fa-building"></i></span>
                        Manage Local Businesses
                    </h1>
                    <div style="font-size:13px;color:#94A3B8;margin-top:2px;">Approve verifications, manage NAP listings, and monitor leads</div>
                </div>
                <div style="display:flex;gap:10px;align-items:center;">
                    <button type="button" id="btnBulkDelete" class="btn-bulk-delete">
                        <i class="fa fa-trash-o"></i> Delete Selected (<span id="selectedCount">0</span>)
                    </button>
                    <a href="{{ route('admin.create.business') }}" class="btn btn-primary" style="font-weight:600;border-radius:8px;padding:8px 16px;">
                        <i class="fa fa-plus"></i> Add Business
                    </a>
                </div>
            </div>

            @include('flash::message')

            {{-- Main Table Card --}}
            <div class="biz-card">
                <div class="biz-card-header">
                    <h2 style="font-size:14px;font-weight:700;color:#0F172A;margin:0;">
                        <i class="fa fa-list" style="color:#2563EB;"></i> All Registered Businesses
                    </h2>
                </div>

                <div style="padding: 0;">
                    <form method="post" role="form" id="biz-search-form">
                        <table class="table table-striped table-bordered table-hover" id="biz_datatable_ajax" style="margin:0;">
                            <thead>
                                <tr role="row" class="filter">
                                    <td style="width:35px;text-align:center;">-</td>
                                    <td style="width:45px;"></td>
                                    <td><input type="text" class="form-control" name="name" id="name" placeholder="Search name..."></td>
                                    <td>
                                        <select name="category_id" id="category_id" class="form-control">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $cId => $cName)
                                            <option value="{{ $cId }}">{{ $cName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td></td>
                                    <td style="text-align:center;"></td>
                                    <td>
                                        <select name="verification_status" id="verification_status" class="form-control">
                                            <option value="">All Statuses</option>
                                            <option value="verified">Verified</option>
                                            <option value="pending">Pending</option>
                                            <option value="unverified">Unverified</option>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td style="width:160px;"></td>
                                </tr>
                                <tr role="row" class="heading">
                                    <th style="width:35px;text-align:center;">
                                        <input type="checkbox" id="selectAllCheckbox" style="cursor:pointer;width:17px;height:17px;" title="Select All" />
                                    </th>
                                    <th style="width:45px;text-align:center;">Logo</th>
                                    <th>Business Name</th>
                                    <th>Category</th>
                                    <th>City</th>
                                    <th style="text-align:center;">Package / Plan</th>
                                    <th style="text-align:center;">Verification</th>
                                    <th style="text-align:center;">Featured</th>
                                    <th style="width:160px;text-align:center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        var oTable = $('#biz_datatable_ajax').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            "order": [[2, "asc"]],
            ajax: {
                url: '{!! route('admin.fetch.businesses') !!}',
                data: function (d) {
                    d.name = $('#name').val();
                    d.category_id = $('#category_id').val();
                    d.verification_status = $('#verification_status').val();
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                {data: 'logo', name: 'logo', orderable: false, searchable: false, className: 'text-center'},
                {data: 'name', name: 'name'},
                {data: 'category_name', name: 'category_name'},
                {data: 'city_name', name: 'city_name'},
                {data: 'package_badge', name: 'package_badge', className: 'text-center'},
                {data: 'verification_badge', name: 'verification_badge', className: 'text-center'},
                {data: 'featured_badge', name: 'featured_badge', className: 'text-center'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
            ],
            drawCallback: function(settings) {
                $('#selectAllCheckbox').prop('checked', false);
                updateBulkDeleteBtn();
            }
        });

        $('#selectAllCheckbox').on('change', function () {
            $('.row-checkbox').prop('checked', $(this).is(':checked'));
            updateBulkDeleteBtn();
        });

        $(document).on('change', '.row-checkbox', function () {
            var total = $('.row-checkbox').length;
            var checked = $('.row-checkbox:checked').length;
            $('#selectAllCheckbox').prop('checked', total > 0 && total === checked);
            updateBulkDeleteBtn();
        });

        function updateBulkDeleteBtn() {
            var checkedCount = $('.row-checkbox:checked').length;
            $('#selectedCount').text(checkedCount);
            if (checkedCount > 0) {
                $('#btnBulkDelete').css('display', 'inline-flex').fadeIn(150);
            } else {
                $('#btnBulkDelete').fadeOut(150);
            }
        }

        $('#btnBulkDelete').on('click', function () {
            var selectedIds = [];
            $('.row-checkbox:checked').each(function () { selectedIds.push($(this).val()); });
            if (selectedIds.length === 0) return;

            if (confirm('Are you sure you want to delete ' + selectedIds.length + ' selected business(es)?')) {
                $.post("{{ route('admin.bulk.delete.businesses') }}", {
                    _token: "{{ csrf_token() }}",
                    ids: selectedIds
                }, function(res) {
                    if (res.status === 'ok') {
                        oTable.draw(false);
                    }
                });
            }
        });

        $('#name, #category_id, #verification_status').on('keyup change', function () {
            oTable.draw();
        });
    });

    function toggleVerify(id, newStatus) {
        $.post("{{ route('admin.toggle.business.verify') }}", {
            _token: "{{ csrf_token() }}",
            id: id,
            status: newStatus
        }, function() {
            $('#biz_datatable_ajax').DataTable().draw(false);
        });
    }

    function toggleFeatured(id) {
        $.post("{{ route('admin.toggle.business.featured') }}", {
            _token: "{{ csrf_token() }}",
            id: id
        }, function() {
            $('#biz_datatable_ajax').DataTable().draw(false);
        });
    }

    function delete_biz(id) {
        if (confirm('Are you sure you want to delete this business?')) {
            $.post("{{ route('admin.delete.business') }}", {
                _method: 'DELETE',
                _token: "{{ csrf_token() }}",
                id: id
            }, function() {
                $('#biz_datatable_ajax').DataTable().draw(false);
            });
        }
    }
</script>
@endpush
