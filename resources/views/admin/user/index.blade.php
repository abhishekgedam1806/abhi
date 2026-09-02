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
                <li> <span>Users</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR --> 
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title">Manage Users <small>Users</small> </h3>
        <!-- END PAGE TITLE--> 
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12"> 
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption"> <i class="icon-settings font-dark"></i> <span class="caption-subject font-dark sbold uppercase">Users</span> </div>
                        <div class="actions">
                            <button type="button" id="btnBulkDelete" class="btn btn-xs btn-danger" style="display:none;font-weight:600;margin-right:8px;border-radius:4px;padding:4px 10px;"><i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)</button>
                            <a href="{{ route('create.user') }}" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-plus"></i> Add New User</a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <form method="post" role="form" id="user-search-form">
                                <table class="table table-striped table-bordered table-hover"  id="user_datatable_ajax">
                                    <thead>
                                        <tr role="row" class="filter">                  
                                            <td style="text-align:center;vertical-align:middle;">-</td>
                                            <td><input type="text" class="form-control" name="id" id="id" autocomplete="off" placeholder="User ID"></td>                    
                                            <td><input type="text" class="form-control" name="name" id="name" autocomplete="off" placeholder="Name"></td>
                                            <td><input type="text" class="form-control" name="email" id="email" autocomplete="off" placeholder="Email"></td>
                                            <td></td>
                                        </tr>
                                        <tr role="row" class="heading"> 
                                            <th style="width:35px;min-width:35px;text-align:center;"><input type="checkbox" id="selectAllCheckbox" style="cursor:pointer;width:17px;height:17px;" title="Select All" /></th>
                                            <th style="width:60px;">Id</th>                                        
                                            <th style="min-width:180px;">Name</th>
                                            <th style="min-width:200px;">Email</th>                                        
                                            <th style="min-width:120px;text-align:center;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table></form>
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
        var oTable = $('#user_datatable_ajax').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            "order": [[1, "desc"]],
            drawCallback: function () {
                $('#selectAllCheckbox').prop('checked', false);
                updateBulkDeleteBtn();
            },
            ajax: {
                url: '{!! route('fetch.data.users') !!}',
                data: function (d) {
                    d.id = $('input[name=id]').val();
                    d.name = $('input[name=name]').val();
                    d.email = $('input[name=email]').val();
                }
            }, columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
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
                alert('Please select at least one user to delete.');
                return;
            }

            if (confirm('Are you sure you want to permanently delete ' + selectedIds.length + ' selected user(s)?')) {
                $('#btnBulkDelete').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
                $.ajax({
                    url: "{{ route('bulk.delete.users') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selectedIds
                    },
                    success: function (response) {
                        $('#btnBulkDelete').prop('disabled', false).html('<i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)');
                        if (response.status === 'ok') {
                            alert(response.count + ' user(s) deleted successfully!');
                            oTable.draw(false);
                        } else {
                            alert('Failed to delete selected users: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function () {
                        $('#btnBulkDelete').prop('disabled', false).html('<i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)');
                        alert('An error occurred while performing bulk delete.');
                    }
                });
            }
        });
        $('#user-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#id').on('keyup', function (e) {
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
    });
    function delete_user(id) {
        if (confirm('Are you sure! you want to delete?')) {
            $.post("{{ route('delete.user') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        if (response == 'ok')
                        {
                            var table = $('#user_datatable_ajax').DataTable();
                            table.row('user_dt_row_' + id).remove().draw(false);
                        } else
                        {
                            alert('Request Failed!');
                        }
                    });
        }
    }
    function make_active(id) {
        $.post("{{ route('make.active.user') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        $('#onclick_active_' + id).attr("onclick", "make_not_active(" + id + ")");
                        $('#onclick_active_' + id).html("<i class=\"fa fa-check-square-o\" aria-hidden=\"true\"></i>Make InActive");
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function make_not_active(id) {
        $.post("{{ route('make.not.active.user') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        $('#onclick_active_' + id).attr("onclick", "make_active(" + id + ")");
                        $('#onclick_active_' + id).html("<i class=\"fa fa-square-o\" aria-hidden=\"true\"></i>Make Active");
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function make_verified(id) {
        $.post("{{ route('make.verified.user') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        $('#onclick_verified_' + id).attr("onclick", "make_not_verified(" + id + ")");
                        $('#onclick_verified_' + id).html("<i class=\"fa fa-check-square-o\" aria-hidden=\"true\"></i>Verified");
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function make_not_verified(id) {
        $.post("{{ route('make.not.verified.user') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        $('#onclick_verified_' + id).attr("onclick", "make_verified(" + id + ")");
                        $('#onclick_verified_' + id).html("<i class=\"fa fa-square-o\" aria-hidden=\"true\"></i>Not Verified");
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
</script> 
@endpush