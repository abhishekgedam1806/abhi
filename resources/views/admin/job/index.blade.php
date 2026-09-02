@extends('admin.layouts.admin_layout')
@section('content')
<style type="text/css">
    /* Ultra-Modern Job Index Styling */
    .admin-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
        flex-wrap: wrap;
        gap: 14px;
    }
    .admin-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .admin-title-wrap h3 {
        margin: 0 !important;
        font-family: 'Poppins', sans-serif !important;
        font-size: 23px !important;
        font-weight: 700 !important;
        color: #0F172A !important;
        letter-spacing: -0.4px !important;
    }
    .admin-count-badge {
        background: #EEF2FF;
        color: #1D4ED8;
        font-size: 12.5px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        border: 1px solid #C7D2FE;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .admin-actions-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .btn-create-primary {
        background: #2563EB !important;
        color: #FFFFFF !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        padding: 10px 20px !important;
        border-radius: 10px !important;
        border: none !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        text-decoration: none !important;
        transition: all 0.25s ease !important;
    }
    .btn-create-primary:hover {
        background: #1D4ED8 !important;
        color: #FFFFFF !important;
        transform: translateY(-1.5px) !important;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.45) !important;
    }
    .btn-bulk-danger {
        background: #FEE2E2 !important;
        color: #DC2626 !important;
        border: 1.5px solid #FECACA !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        padding: 9px 16px !important;
        border-radius: 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 7px !important;
        transition: all 0.2s ease !important;
    }
    .btn-bulk-danger:hover {
        background: #DC2626 !important;
        color: #FFFFFF !important;
        border-color: #DC2626 !important;
    }

    /* Table Container & Layout */
    .table-container {
        overflow-x: auto !important;
        padding-bottom: 90px !important;
        border-radius: 12px !important;
    }
    #jobDatatableAjax {
        width: 100% !important;
        min-width: 1050px !important;
    }
    #jobDatatableAjax thead tr.heading th {
        background: #F8FAFC !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.6px !important;
        padding: 14px 16px !important;
        border-bottom: 2px solid #E2E8F0 !important;
        vertical-align: middle !important;
    }
    #jobDatatableAjax thead tr.filter td {
        background: #F1F5F9 !important;
        padding: 10px 8px !important;
        vertical-align: top !important;
    }
    #jobDatatableAjax thead tr.filter td .form-control {
        height: 36px !important;
        padding: 6px 10px !important;
        font-size: 12.5px !important;
        border-radius: 7px !important;
        border: 1.5px solid #CBD5E1 !important;
        background-color: #FFFFFF !important;
        margin-bottom: 4px !important;
        box-shadow: none !important;
    }
    #jobDatatableAjax thead tr.filter td .form-control:focus {
        border-color: #1B4FD8 !important;
        box-shadow: 0 0 0 3px rgba(27, 79, 216, 0.12) !important;
    }
    #jobDatatableAjax tbody tr td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        border-top: 1px solid #F1F5F9 !important;
        font-size: 13px !important;
    }
    #jobDatatableAjax tbody tr:hover {
        background-color: #F8FAFC !important;
    }

    /* Action Buttons in Table */
    .btn-job-view {
        background-color: #F1F5F9 !important;
        border: 1.5px solid #CBD5E1 !important;
        color: #0284C7 !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        padding: 6px 12px !important;
        border-radius: 7px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
    }
    .btn-job-view:hover {
        background-color: #0284C7 !important;
        border-color: #0284C7 !important;
        color: #FFFFFF !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 3px 10px rgba(2, 132, 199, 0.25) !important;
    }
    .btn-job-action {
        background-color: #1B4FD8 !important;
        border: 1.5px solid #1B4FD8 !important;
        color: #FFFFFF !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        padding: 6px 12px !important;
        border-radius: 7px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        box-shadow: 0 2px 6px rgba(27, 79, 216, 0.2) !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
    }
    .btn-job-action:hover, .open > .btn-job-action {
        background-color: #1440B0 !important;
        border-color: #1440B0 !important;
        color: #FFFFFF !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(27, 79, 216, 0.3) !important;
    }
    .dropdown-menu.job-action-menu {
        min-width: 180px !important;
        padding: 6px !important;
        border-radius: 10px !important;
        border: 1.5px solid #E2E8F0 !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12) !important;
        z-index: 10050 !important;
        background: #FFFFFF !important;
    }
    .dropdown-menu.job-action-menu > li > a {
        padding: 8px 12px !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        border-radius: 6px !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        transition: all 0.15s ease !important;
    }
    .dropdown-menu.job-action-menu > li > a:hover {
        background-color: #EEF2FF !important;
        color: #1B4FD8 !important;
    }
    .dropdown-menu.job-action-menu > li > a.text-danger:hover {
        background-color: #FEE2E2 !important;
        color: #DC2626 !important;
    }
</style>

<div class="page-content-wrapper"> 
    <div class="page-content"> 
        {{-- Modern Breadcrumb --}}
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li> <a href="{{ route('admin.home') }}"><i class="fa fa-home"></i> Home</a> <i class="fa fa-circle"></i> </li>
                <li> <span>Jobs Management</span> </li>
            </ul>
        </div>

        {{-- Top Title & Actions Header --}}
        <div class="admin-header-flex">
            <div class="admin-title-wrap">
                <h3>Manage Jobs</h3>
                <span class="admin-count-badge"><i class="fa fa-briefcase"></i> Total Active & Inactive Postings</span>
            </div>
            <div class="admin-actions-wrap">
                <button type="button" id="btnBulkDelete" class="btn-bulk-danger" style="display:none;">
                    <i class="fa fa-trash-o"></i> Delete Selected (<span id="selectedCount">0</span>)
                </button>
                <a href="{{ route('create.job') }}" class="btn-create-primary">
                    <i class="fa fa-plus-circle"></i> Add New Job
                </a>
            </div>
        </div>

        {{-- Main Filter Toolbar Card Above Table --}}
        <div class="portlet light bordered" style="padding: 22px 24px; border-radius: 14px; margin-bottom: 24px; background: #FFFFFF; border: 1.5px solid #E2E8F0; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <form method="post" role="form" id="job-search-form">
                <input type="hidden" name="is_featured" id="is_featured" value="-1">
                
                <div class="row">
                    <div class="col-md-3 col-sm-6" style="margin-bottom: 14px;">
                        <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-search text-primary"></i> Search Job Title
                        </label>
                        <input type="text" class="form-control" name="title" id="title" autocomplete="off" placeholder="e.g. Flutter, SEO Manager..." style="height: 42px; border-radius: 9px; border: 1.5px solid #E2E8F0; font-size: 13.5px;">
                    </div>
                    <div class="col-md-3 col-sm-6" style="margin-bottom: 14px;">
                        <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-building text-primary"></i> Company
                        </label>
                        {!! Form::select('company_id', ['' => 'All Companies']+$companies, null, array('id'=>'company_id', 'class'=>'form-control', 'style'=>'height: 42px; border-radius: 9px; border: 1.5px solid #E2E8F0; font-size: 13.5px;')) !!}
                    </div>
                    <div class="col-md-2 col-sm-6" style="margin-bottom: 14px;">
                        <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-globe text-primary"></i> Country
                        </label>
                        {!! Form::select('country_id', ['' => 'All Countries']+$countries, null, array('id'=>'country_id', 'class'=>'form-control', 'style'=>'height: 42px; border-radius: 9px; border: 1.5px solid #E2E8F0; font-size: 13.5px;')) !!}
                    </div>
                    <div class="col-md-2 col-sm-6" style="margin-bottom: 14px;">
                        <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-map-marker text-danger"></i> State
                        </label>
                        <span id="default_state_dd">
                            {!! Form::select('state_id', ['' => 'All States'], null, array('id'=>'state_id', 'class'=>'form-control', 'style'=>'height: 42px; border-radius: 9px; border: 1.5px solid #E2E8F0; font-size: 13.5px;')) !!}
                        </span>
                    </div>
                    <div class="col-md-2 col-sm-6" style="margin-bottom: 14px;">
                        <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-toggle-on text-success"></i> Status
                        </label>
                        <select name="is_active" id="is_active" class="form-control" style="height: 42px; border-radius: 9px; border: 1.5px solid #E2E8F0; font-size: 13.5px;">
                            <option value="-1">All Status</option>
                            <option value="1">Active Only</option>
                            <option value="0">Inactive Only</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 4px; padding-top: 16px; border-top: 1.5px dashed #E2E8F0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <div style="display: flex; align-items: center;">
                        <label style="margin: 0; font-size: 13.5px; font-weight: 600; color: #334155; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; background: #FFFBEB; border: 1px solid #FDE68A; padding: 7px 14px; border-radius: 8px;">
                            <input type="checkbox" id="filter_featured_toggle" style="width: 16px; height: 16px; accent-color: #F59E0B; cursor: pointer;">
                            <span style="color: #B45309;">⭐ Show Featured Jobs Only</span>
                        </label>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <button type="button" id="btnResetFilter" class="btn btn-default" style="height: 40px; padding: 0 18px; border-radius: 8px; font-weight: 600; color: #475569; background: #F8FAFC; border: 1.5px solid #CBD5E1; transition: all 0.2s ease;">
                            <i class="fa fa-refresh"></i> Reset Filters
                        </button>
                        <button type="submit" class="btn btn-primary" style="height: 40px; padding: 0 22px; border-radius: 8px; font-weight: 600; background: #2563EB; border: none; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25); transition: all 0.2s ease;">
                            <i class="fa fa-search"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Main Datatable Portlet Card --}}
        <div class="row">
            <div class="col-md-12"> 
                <div class="portlet light bordered" style="padding: 24px; border-radius: 14px;">
                    <div class="portlet-body">
                        <div class="table-container">
                            <table class="table table-striped table-hover" id="jobDatatableAjax">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th style="width:40px;text-align:center;">
                                            <input type="checkbox" id="selectAllCheckbox" style="cursor:pointer;width:17px;height:17px;accent-color:#1B4FD8;" title="Select All" />
                                        </th>
                                        <th style="min-width:180px;">Company</th>
                                        <th style="min-width:250px;">Job Title & Status</th>
                                        <th style="min-width:220px;">Description</th>
                                        <th style="min-width:180px;">Location</th>
                                        <th style="width:170px;min-width:170px;text-align:center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
        var oTable = $('#jobDatatableAjax').DataTable({
            processing: true,
            serverSide: true,
            stateSave: false,
            searching: false,
            drawCallback: function () {
                $('#selectAllCheckbox').prop('checked', false);
                updateBulkDeleteBtn();
            },
            ajax: {
                url: '{!! route('fetch.data.jobs') !!}',
                data: function (d) {
                    d.company_id = $('#company_id').val();
                    d.title = $('#title').val();
                    d.country_id = $('#country_id').val();
                    d.state_id = $('#state_id').val();
                    d.city_id = $('#city_id').val();
                    d.is_active = $('#is_active').val();
                    d.is_featured = $('#is_featured').val();
                }
            }, columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                {data: 'company_id', name: 'company_id'},
                {data: 'title', name: 'title'},
                {data: 'description', name: 'description'},
                {data: 'city_id', name: 'city_id'},
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
                alert('Please select at least one job to delete.');
                return;
            }

            if (confirm('Are you sure you want to permanently delete ' + selectedIds.length + ' selected job(s)?')) {
                $('#btnBulkDelete').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
                $.ajax({
                    url: "{{ route('bulk.delete.jobs') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selectedIds
                    },
                    success: function (response) {
                        $('#btnBulkDelete').prop('disabled', false).html('<i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)');
                        if (response.status === 'ok') {
                            alert(response.count + ' job(s) deleted successfully!');
                            oTable.draw(false);
                        } else {
                            alert('Failed to delete selected jobs: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function () {
                        $('#btnBulkDelete').prop('disabled', false).html('<i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)');
                        alert('An error occurred while performing bulk delete.');
                    }
                });
            }
        });
        $('#job-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#company_id').on('change', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#title').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#country_id').on('change', function (e) {
            var cid = $(this).val();
            if (cid) {
                filterDefaultStates(0);
            } else {
                $('#state_id').html('<option value="">All States</option>');
                $('#city_id').html('<option value="">All Cities</option>');
            }
            oTable.draw();
            e.preventDefault();
        });
        $(document).on('change', '#state_id', function (e) {
            var sid = $(this).val();
            if (sid) {
                filterDefaultCities(0);
            } else {
                $('#city_id').html('<option value="">All Cities</option>');
            }
            oTable.draw();
            e.preventDefault();
        });
        $(document).on('change', '#city_id', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#is_active').on('change', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#filter_featured_toggle').on('change', function () {
            $('#is_featured').val($(this).is(':checked') ? 1 : -1);
            oTable.draw();
        });

        $('#btnResetFilter').on('click', function () {
            $('#title').val('');
            $('#company_id').val('');
            $('#country_id').val('');
            $('#state_id').html('<option value="">All States</option>');
            $('#city_id').html('<option value="">All Cities</option>');
            $('#is_active').val('-1');
            $('#is_featured').val('-1');
            $('#filter_featured_toggle').prop('checked', false);
            oTable.draw();
        });
    });
    function deleteJob(id, is_default) {
        var msg = 'Are you sure?';
        if (confirm(msg)) {
            $.post("{{ route('delete.job') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        if (response == 'ok')
                        {
                            var table = $('#jobDatatableAjax').DataTable();
                            table.row('jobDtRow' + id).remove().draw(false);
                        } else
                        {
                            alert('Request Failed!');
                        }
                    });
        }
    }
    function makeActive(id) {
        $.post("{{ route('make.active.job') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#jobDatatableAjax').DataTable();
                        table.row('jobDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function makeNotActive(id) {
        $.post("{{ route('make.not.active.job') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#jobDatatableAjax').DataTable();
                        table.row('jobDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function makeFeatured(id) {
        $.post("{{ route('make.featured.job') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#jobDatatableAjax').DataTable();
                        table.row('jobDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function makeNotFeatured(id) {
        $.post("{{ route('make.not.featured.job') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#jobDatatableAjax').DataTable();
                        table.row('jobDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function filterDefaultStates(state_id)
    {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("{{ route('filter.default.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_state_dd').html(response);
                    });
        }
    }
    function filterDefaultCities(city_id)
    {
        var state_id = $('#state_id').val();
        if (state_id != '') {
            $.post("{{ route('filter.default.cities.dropdown') }}", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_city_dd').html(response);
                    });
        }
    }
</script>
@endpush