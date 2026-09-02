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
                <li> <span>Testimonials</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR --> 
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title">Manage Testimonials <small>Testimonials</small> </h3>
        <!-- END PAGE TITLE--> 
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12"> 
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption"> <i class="icon-settings font-dark"></i> <span class="caption-subject font-dark sbold uppercase">Testimonials</span> </div>
                        <div class="actions">
                            <button type="button" id="btnBulkDelete" class="btn btn-xs btn-danger" style="display:none;font-weight:600;margin-right:8px;border-radius:4px;padding:4px 10px;"><i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)</button>
                            <a href="{{ route('create.testimonial') }}" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-plus"></i> Add New Testimonial</a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <form method="post" role="form" id="testimonial-search-form">
                                <table class="table table-striped table-bordered table-hover"  id="testimonialDatatableAjax">
                                    <thead>
                                        <tr role="row" class="filter">
                                            <td style="text-align:center;vertical-align:middle;">-</td>
                                            <td>{!! Form::select('lang', ['' => 'Select Language']+$languages, config('default_lang'), array('id'=>'lang', 'class'=>'form-control')) !!}</td>
                                            <td><input type="text" class="form-control" name="testimonial_by" id="testimonial_by" autocomplete="off" placeholder="Testimonial by"></td>
                                            <td><input type="text" class="form-control" name="testimonial" id="testimonial" autocomplete="off" placeholder="Testimonial"></td>
                                            <td><select name="is_active" id="is_active"  class="form-control">
                                                    <option value="-1">Is Active?</option>
                                                    <option value="1" selected="selected">Active</option>
                                                    <option value="0">In Active</option>
                                                </select></td>
                                        </tr>
                                        <tr role="row" class="heading">
                                            <th style="width:35px;min-width:35px;text-align:center;"><input type="checkbox" id="selectAllCheckbox" style="cursor:pointer;width:17px;height:17px;" title="Select All" /></th>
                                            <th style="width:100px;">Language</th>
                                            <th style="min-width:180px;">Testimonial By</th>
                                            <th style="min-width:260px;">Testimonial</th>
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
        var oTable = $('#testimonialDatatableAjax').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            drawCallback: function () {
                $('#selectAllCheckbox').prop('checked', false);
                updateBulkDeleteBtn();
            },
            ajax: {
                url: '{!! route('fetch.data.testimonials') !!}',
                data: function (d) {
                    d.lang = $('#lang').val();
                    d.testimonial_by = $('#testimonial_by').val();
                    d.testimonial = $('#testimonial').val();
                    d.is_active = $('#is_active').val();
                }
            }, columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                {data: 'lang', name: 'lang'},
                {data: 'testimonial_by', name: 'testimonial_by'},
                {data: 'testimonial', name: 'testimonial'},
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
                alert('Please select at least one testimonial to delete.');
                return;
            }

            if (confirm('Are you sure you want to permanently delete ' + selectedIds.length + ' selected testimonial(s)?')) {
                $('#btnBulkDelete').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
                $.ajax({
                    url: "{{ route('bulk.delete.testimonials') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selectedIds
                    },
                    success: function (response) {
                        $('#btnBulkDelete').prop('disabled', false).html('<i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)');
                        if (response.status === 'ok') {
                            alert(response.count + ' testimonial(s) deleted successfully!');
                            oTable.draw(false);
                        } else {
                            alert('Failed to delete selected testimonials: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function () {
                        $('#btnBulkDelete').prop('disabled', false).html('<i class="glyphicon glyphicon-trash"></i> Delete Selected (<span id="selectedCount">0</span>)');
                        alert('An error occurred while performing bulk delete.');
                    }
                });
            }
        });
        $('#testimonial-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#lang').on('change', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#testimonial_by').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#testimonial').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#is_active').on('change', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });
    function deleteTestimonial(id, is_default) {
        var msg = 'Are you sure?';
        if (is_default == 1) {
            msg = 'Are you sure? You are going to delete default Testimonial, all other non default Testimonials will be deleted too!';
        }
        if (confirm(msg)) {
            $.post("{{ route('delete.testimonial') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'}, null, 'text')
                    .done(function (response) {
                        if (response == 'ok')
                        {
                            var table = $('#testimonialDatatableAjax').DataTable();
                            table.row('testimonialDtRow' + id).remove().draw(false);
                        } else
                        {
                            alert('Request Failed!');
                        }
                    });
        }
    }
    function makeActive(id) {
        $.post("{{ route('make.active.testimonial') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#testimonialDatatableAjax').DataTable();
                        table.row('testimonialDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
    function makeNotActive(id) {
        $.post("{{ route('make.not.active.testimonial') }}", {id: id, _method: 'PUT', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok')
                    {
                        var table = $('#testimonialDatatableAjax').DataTable();
                        table.row('testimonialDtRow' + id).remove().draw(false);
                    } else
                    {
                        alert('Request Failed!');
                    }
                });
    }
</script> 
@endpush 