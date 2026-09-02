@extends('admin.layouts.admin_layout')
@section('content')
<style type="text/css">
    .table td, .table th {
        font-size: 12.5px;
        vertical-align: middle !important;
    }	
    tr.filter td {
        padding: 6px 4px !important;
        background-color: #F8FAFC !important;
    }
    tr.filter td .form-control {
        height: 32px !important;
        padding: 3px 6px !important;
        font-size: 12px !important;
        margin-bottom: 3px !important;
        border-radius: 4px !important;
    }
    .table-container {
        overflow-x: auto;
        padding-bottom: 70px;
    }
</style>
<div class="page-content-wrapper"> 
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content"> 
        <!-- BEGIN PAGE HEADER--> 
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li> <a href="{{ route('admin.home') }}">Home</a> <i class="fa fa-circle"></i> </li>
                <li> <span>Packages</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR --> 
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title">Manage Packages <small>Packages</small> </h3>
        <!-- END PAGE TITLE--> 
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12"> 
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption"> <i class="icon-settings font-dark"></i> <span class="caption-subject font-dark sbold uppercase">Packages</span> </div>
                        <div class="actions">
                            <button type="button" id="btnBulkDelete" class="btn btn-xs btn-danger" style="display:none;font-weight:600;margin-right:8px;border-radius:4px;padding:4px 10px;"><i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)</button>
                            <a href="{{ route('create.package') }}" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-plus"></i> Add New Package</a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <form method="post" role="form" id="datatable-search-form">
                                <table class="table table-striped table-bordered table-hover"  id="packageDatatableAjax">
                                    <thead>
                                        <tr role="row" class="filter">
                                            <td style="text-align:center;vertical-align:middle;">-</td>
                                            <td><input type="text" class="form-control" name="package_title" id="package_title" autocomplete="off" placeholder="Package Title"></td>
                                            <td><input type="text" class="form-control" name="package_price" id="package_price" autocomplete="off" placeholder="package price"></td>
                                            <td><input type="text" class="form-control" name="package_num_days" id="package_num_days" autocomplete="off" placeholder="package num days"></td>
                                            <td><input type="text" class="form-control" name="package_num_listings" id="package_num_listings" autocomplete="off" placeholder="package num listings"></td>
                                            <td><select name="package_for" id="package_for" class="form-control">
                                                    <option value="">Package For?</option>
                                                    <option value="job_seeker">Job Seeker</option>
                                                    <option value="employer">Employer</option>
                                                </select></td>
                                            <td></td>
                                        </tr>
                                        <tr role="row" class="heading">
                                            <th style="width:35px;min-width:35px;text-align:center;"><input type="checkbox" id="selectAllCheckbox" style="cursor:pointer;width:17px;height:17px;" title="Select All" /></th>
                                            <th style="min-width:180px;">Title</th>
                                            <th style="min-width:90px;">Price</th>
                                            <th style="min-width:90px;">Num Days</th>
                                            <th style="min-width:100px;">Num Listings</th>
                                            <th style="min-width:110px;">For</th>                                            
                                            <th style="min-width:120px;text-align:center;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY --> 
</div>
@endsection
@push('scripts') 
<script>
    $(function () {
        var oTable = $('#packageDatatableAjax').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            drawCallback: function () {
                $('#selectAllCheckbox').prop('checked', false);
                updateBulkDeleteBtn();
            },
            ajax: {
                url: '{!! route('fetch.data.packages') !!}',
                data: function (d) {
                    d.package_title = $('#package_title').val();
                    d.package_price = $('#package_price').val();
                    d.package_num_days = $('#package_num_days').val();
                    d.package_num_listings = $('#package_num_listings').val();
                    d.package_for = $('#package_for').val();
                }
            }, columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                {data: 'package_title', name: 'package_title'},
                {data: 'package_price', name: 'package_price'},
                {data: 'package_num_days', name: 'package_num_days'},
                {data: 'package_num_listings', name: 'package_num_listings'},
                {data: 'package_for', name: 'package_for'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
            ]
        });

        // Select / Deselect All
        $('#selectAllCheckbox').on('change', function () {
            var isChecked = $(this).is(':checked');
            $('.row-checkbox').prop('checked', isChecked);
            updateBulkDeleteBtn();
        });

        // Individual row checkbox change
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
                $('#btnBulkDelete').fadeIn(150);
            } else {
                $('#btnBulkDelete').fadeOut(150);
            }
        }

        // Bulk Delete Button Click
        $('#btnBulkDelete').on('click', function () {
            var selectedIds = [];
            $('.row-checkbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert('Please select at least one package to delete.');
                return;
            }

            if (confirm('Are you sure you want to permanently delete ' + selectedIds.length + ' selected package(s)?')) {
                $('#btnBulkDelete').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
                $.ajax({
                    url: "{{ route('bulk.delete.packages') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selectedIds
                    },
                    success: function (response) {
                        $('#btnBulkDelete').prop('disabled', false).html('<i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)');
                        if (response.status === 'ok') {
                            alert(response.count + ' package(s) deleted successfully!');
                            oTable.draw(false);
                        } else {
                            alert('Failed to delete selected packages: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function () {
                        $('#btnBulkDelete').prop('disabled', false).html('<i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)');
                        alert('An error occurred while performing bulk delete.');
                    }
                });
            }
        });
        $('#datatable-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#package_title').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#package_price').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#package_num_days').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#package_num_listings').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#package_for').on('change', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });
    function deletePackage(id) {
        var msg = 'Are you sure?';
        if (confirm(msg)) {
            $.post("{{ route('delete.package') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        if (response == 'ok')
                        {
                            var table = $('#packageDatatableAjax').DataTable();
                            table.row('packageDtRow' + id).remove().draw(false);
                        } else
                        {
                            alert('Request Failed!');
                        }
                    });
        }
    }
</script> 
@endpush