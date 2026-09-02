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
                <li> <span>Companies</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR --> 
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title">Manage Companies <small>Companies</small> </h3>
        <!-- END PAGE TITLE--> 
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12"> 
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption"> <i class="icon-settings font-dark"></i> <span class="caption-subject font-dark sbold uppercase">Companies</span> </div>
                        <div class="actions">
                            <button type="button" id="btnBulkDelete" class="btn btn-xs btn-danger" style="display:none;font-weight:600;margin-right:8px;border-radius:4px;padding:4px 10px;"><i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)</button>
                            <a href="{{ route('create.company') }}" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-plus"></i> Add New Company</a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <form method="post" role="form" id="datatable-search-form">
                                <table class="table table-striped table-bordered table-hover"  id="companyDatatableAjax">
                                    <thead>
                                        <tr role="row" class="filter">
                                            <td style="text-align:center;vertical-align:middle;">-</td>
                                            <td><input type="text" class="form-control" name="name" id="name" autocomplete="off" placeholder="Company Name"></td>
                                            <td><input type="text" class="form-control" name="email" id="email" autocomplete="off" placeholder="Company Email"></td>
                                            <td><select name="is_active" id="is_active" class="form-control">
                                                    <option value="-1" selected="selected">All Status</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">In Active</option>
                                                </select></td>
                                            <td><select name="is_featured" id="is_featured" class="form-control">
                                                    <option value="-1">Is Featured?</option>
                                                    <option value="1">Featured</option>
                                                    <option value="0">Not Featured</option>
                                                </select></td>
                                            <td></td>
                                        </tr>
                                        <tr role="row" class="heading">
                                            <th style="width:35px;min-width:35px;text-align:center;"><input type="checkbox" id="selectAllCheckbox" style="cursor:pointer;width:17px;height:17px;" title="Select All" /></th>
                                            <th style="min-width:160px;">Name</th>
                                            <th style="min-width:180px;">Email</th>
                                            <th style="min-width:100px;text-align:center;">Is Active?</th>
                                            <th style="min-width:100px;text-align:center;">Is Featured?</th>
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
        var oTable = $('#companyDatatableAjax').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            drawCallback: function () {
                $('#selectAllCheckbox').prop('checked', false);
                updateBulkDeleteBtn();
            },
            ajax: {
                url: '{!! route('fetch.data.companies') !!}',
                data: function (d) {
                    d.name = $('#name').val();
                    d.email = $('#email').val();
                    d.is_active = $('#is_active').val();
                    d.is_featured = $('#is_featured').val();
                }
            }, columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'is_active', name: 'is_active', className: 'text-center'},
                {data: 'is_featured', name: 'is_featured', className: 'text-center'},
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
                alert('Please select at least one company to delete.');
                return;
            }

            if (confirm('Are you sure you want to permanently delete ' + selectedIds.length + ' selected company(s) and their associated jobs?')) {
                $('#btnBulkDelete').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
                $.ajax({
                    url: "{{ route('bulk.delete.companies') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selectedIds
                    },
                    success: function (response) {
                        $('#btnBulkDelete').prop('disabled', false).html('<i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)');
                        if (response.status === 'ok') {
                            alert(response.count + ' company(s) deleted successfully!');
                            oTable.draw(false);
                        } else {
                            alert('Failed to delete selected companies: ' + (response.message || 'Unknown error'));
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
        $('#name').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#email').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#is_active').on('change', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#is_featured').on('change', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });
    function deleteCompany(id) {
        var msg = 'Are you sure?';
        if (confirm(msg)) {
            $.post("{{ route('delete.company') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        if (response == 'ok')
                        {
                            var table = $('#companyDatatableAjax').DataTable();
                            table.row('companyDtRow' + id).remove().draw(false);
                        } else
                        {
                            alert('Request Failed!');
                        }
                    });
        }
    }
    function makeActive(id) {
        $.post("{{ route('make.active.company') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#companyDatatableAjax').DataTable();
                        table.row('companyDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function makeNotActive(id) {
        $.post("{{ route('make.not.active.company') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#companyDatatableAjax').DataTable();
                        table.row('companyDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function makeFeatured(id) {
        $.post("{{ route('make.featured.company') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#companyDatatableAjax').DataTable();
                        table.row('companyDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function makeNotFeatured(id) {
        $.post("{{ route('make.not.featured.company') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#companyDatatableAjax').DataTable();
                        table.row('companyDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
</script> 
@endpush