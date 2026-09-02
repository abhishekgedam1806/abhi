@extends('admin.layouts.admin_layout')
@section('content')
<style>
/* ============================================================
   CMS Page — Modern Admin UI with Bulk Actions
   ============================================================ */
.cms-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
    padding: 0 0 20px 0;
}

/* Breadcrumb */
.cms-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #94A3B8;
    margin-bottom: 20px;
    font-weight: 500;
}
.cms-breadcrumb a {
    color: #64748B;
    text-decoration: none;
    transition: color 0.15s;
}
.cms-breadcrumb a:hover { color: #3B82F6; }
.cms-breadcrumb i { font-size: 8px; color: #CBD5E1; }

/* Page Header Row */
.cms-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.cms-page-title {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: -0.3px;
}
.cms-page-title .title-icon {
    width: 38px;
    height: 38px;
    background: #2563EB;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 16px;
    box-shadow: 0 4px 12px rgba(99,102,241,0.25);
}
.cms-page-subtitle {
    font-size: 13px;
    color: #94A3B8;
    font-weight: 500;
    margin-top: 2px;
}

/* Header Actions */
.cms-header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Bulk Delete Button */
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
    transition: all 0.15s ease;
    box-shadow: 0 2px 8px rgba(220,38,38,0.1);
}
.btn-bulk-delete:hover {
    background: #DC2626;
    color: #fff !important;
    border-color: #DC2626;
}

/* Add Button */
.btn-add-cms {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #2563EB;
    color: #fff !important;
    font-size: 13.5px;
    font-weight: 600;
    padding: 9px 18px;
    border-radius: 10px;
    border: none;
    text-decoration: none !important;
    box-shadow: 0 4px 14px rgba(99,102,241,0.3);
    transition: all 0.2s ease;
    cursor: pointer;
}
.btn-add-cms:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(99,102,241,0.4);
    color: #fff !important;
}
.btn-add-cms i { font-size: 13px; }

/* Main Card */
.cms-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.cms-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1px solid #F1F5F9;
    background: #FAFBFC;
}
.cms-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #0F172A;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.cms-card-title i {
    color: #6366F1;
    font-size: 15px;
}
.cms-record-badge {
    background: #EFF6FF;
    color: #3B82F6;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    border: 1px solid #BFDBFE;
}

/* DataTable Overrides */
.cms-card .dataTables_wrapper {
    padding: 0;
}
.cms-card .dataTables_length {
    padding: 14px 22px 4px;
}
.cms-card .dataTables_length label {
    font-size: 13px;
    color: #64748B;
    font-weight: 500;
}
.cms-card .dataTables_length select {
    border: 1px solid #E2E8F0;
    border-radius: 7px;
    padding: 3px 8px;
    font-size: 13px;
    color: #1E293B;
    background: #fff;
    margin: 0 6px;
}
.cms-card .dataTables_info {
    padding: 14px 22px;
    font-size: 12.5px;
    color: #94A3B8;
    font-weight: 500;
}
.cms-card .dataTables_paginate {
    padding: 14px 22px;
}
.cms-card .paginate_button {
    border-radius: 7px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 4px 10px !important;
    margin: 0 2px !important;
}
.cms-card .paginate_button.current,
.cms-card .paginate_button.current:hover {
    background: #3B82F6 !important;
    border-color: #3B82F6 !important;
    color: #fff !important;
}
.cms-card .paginate_button:hover {
    background: #F1F5F9 !important;
    border-color: #E2E8F0 !important;
    color: #1E293B !important;
}

/* Table */
#cms_datatable_ajax {
    width: 100% !important;
    border-collapse: collapse !important;
}
#cms_datatable_ajax thead tr.heading th {
    background: #F8FAFC;
    color: #64748B;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 12px 16px;
    border-bottom: 2px solid #E2E8F0;
    border-top: none;
    white-space: nowrap;
}
#cms_datatable_ajax thead tr.filter td {
    padding: 10px 16px;
    background: #FAFBFC;
    border-bottom: 1px solid #F1F5F9;
}
#cms_datatable_ajax thead tr.filter td input {
    height: 34px;
    border: 1px solid #E2E8F0;
    border-radius: 7px;
    padding: 0 10px;
    font-size: 12.5px;
    color: #1E293B;
    background: #fff;
    width: 100%;
    outline: none;
    transition: border-color 0.15s;
}
#cms_datatable_ajax thead tr.filter td input:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.08);
}
#cms_datatable_ajax tbody tr {
    border-bottom: 1px solid #F1F5F9;
    transition: background 0.15s;
}
#cms_datatable_ajax tbody tr:hover {
    background: #F8FAFC;
}
#cms_datatable_ajax tbody td {
    padding: 14px 16px;
    font-size: 13.5px;
    color: #334155;
    vertical-align: middle;
    border: none;
}

/* ID Badge */
.cms-id-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 30px;
    height: 26px;
    background: #EFF6FF;
    color: #3B82F6;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid #BFDBFE;
    padding: 0 8px;
}

/* Slug Badge */
.cms-slug-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #F1F5F9;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 5px 12px;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
}
.cms-slug-badge i { color: #94A3B8; font-size: 12px; }

/* Action Buttons */
.cms-action-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: nowrap;
}
.btn-cms-view {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #F8FAFC;
    color: #475569 !important;
    border: 1px solid #CBD5E1;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 11px;
    text-decoration: none !important;
    transition: all 0.15s;
    white-space: nowrap;
}
.btn-cms-view:hover {
    background: #475569;
    color: #fff !important;
    border-color: #475569;
}
.btn-cms-edit {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #EFF6FF;
    color: #2563EB !important;
    border: 1px solid #BFDBFE;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 12px;
    text-decoration: none !important;
    transition: all 0.15s;
    white-space: nowrap;
}
.btn-cms-edit:hover {
    background: #2563EB;
    color: #fff !important;
    border-color: #2563EB;
}
.btn-cms-delete {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #FEF2F2;
    color: #DC2626 !important;
    border: 1px solid #FCA5A5;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 10px;
    cursor: pointer;
    border: 1px solid #FCA5A5;
    transition: all 0.15s;
    white-space: nowrap;
}
.btn-cms-delete:hover {
    background: #DC2626;
    color: #fff !important;
    border-color: #DC2626;
}

/* Empty state */
.cms-empty {
    text-align: center;
    padding: 48px 20px;
    color: #94A3B8;
}
.cms-empty i { font-size: 40px; margin-bottom: 12px; display: block; }
.cms-empty p { font-size: 14px; font-weight: 500; margin: 0; }
</style>

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="cms-wrapper">
            {{-- Breadcrumb --}}
            <div class="cms-breadcrumb">
                <a href="{{ route('admin.home') }}"><i class="fa fa-home"></i> Home</a>
                <i class="fa fa-angle-right"></i>
                <span>CMS Pages</span>
            </div>

            {{-- Page Header --}}
            <div class="cms-header-row">
                <div>
                    <h1 class="cms-page-title">
                        <span class="title-icon"><i class="fa fa-file-text-o"></i></span>
                        Manage CMS Pages
                    </h1>
                    <div class="cms-page-subtitle">Manage all static content pages (About, Terms, Privacy, etc.)</div>
                </div>
                <div class="cms-header-actions">
                    <button type="button" id="btnBulkDelete" class="btn-bulk-delete">
                        <i class="fa fa-trash-o"></i> Delete Selected (<span id="selectedCount">0</span>)
                    </button>
                    <a href="{{ route('create.cms') }}" class="btn-add-cms">
                        <i class="fa fa-plus"></i> Add New CMS Page
                    </a>
                </div>
            </div>

            {{-- Flash Messages --}}
            @include('flash::message')

            {{-- Main Card --}}
            <div class="cms-card">
                {{-- Card Header --}}
                <div class="cms-card-header">
                    <h2 class="cms-card-title">
                        <i class="fa fa-list-alt"></i> All CMS Pages
                    </h2>
                    <span class="cms-record-badge" id="cms-total-badge">Loading...</span>
                </div>

                {{-- DataTable --}}
                <div style="padding: 0;">
                    <form method="post" role="form" id="cms-search-form">
                        <table class="table" id="cms_datatable_ajax" style="margin:0;">
                            <thead>
                                <tr role="row" class="filter">
                                    <td style="width:45px;text-align:center;">-</td>
                                    <td style="width:100px;"><input type="text" class="form-control" name="id" id="id" autocomplete="off" placeholder="ID..."></td>
                                    <td><input type="text" class="form-control" name="page_slug" id="page_slug" autocomplete="off" placeholder="Search slug..."></td>
                                    <td style="width:200px;"></td>
                                </tr>
                                <tr role="row" class="heading">
                                    <th style="width:45px;text-align:center;">
                                        <input type="checkbox" id="selectAllCheckbox" style="cursor:pointer;width:17px;height:17px;" title="Select All" />
                                    </th>
                                    <th style="width:100px;"><i class="fa fa-hashtag" style="margin-right:5px;color:#94A3B8;"></i>ID</th>
                                    <th><i class="fa fa-link" style="margin-right:5px;color:#94A3B8;"></i>Page Slug</th>
                                    <th style="width:200px;"><i class="fa fa-bolt" style="margin-right:5px;color:#94A3B8;"></i>Actions</th>
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
        var oTable = $('#cms_datatable_ajax').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            "order": [[1, "desc"]],
            ajax: {
                url: '{!! route('fetch.data.cms') !!}',
                data: function (d) {
                    d.id = $('#id').val();
                    d.page_slug = $('#page_slug').val();
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                {
                    data: 'id',
                    name: 'id',
                    render: function(data) {
                        return '<span class="cms-id-badge">' + data + '</span>';
                    }
                },
                {
                    data: 'page_slug',
                    name: 'page_slug',
                    render: function(data) {
                        return '<span class="cms-slug-badge"><i class="fa fa-link"></i>' + data + '</span>';
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            drawCallback: function(settings) {
                var total = settings.fnRecordsTotal();
                $('#cms-total-badge').text(total + ' Pages');
                $('#selectAllCheckbox').prop('checked', false);
                updateBulkDeleteBtn();
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin" style="color:#3B82F6;font-size:20px;"></i>',
                emptyTable: '<div class="cms-empty"><i class="fa fa-file-o"></i><p>No CMS pages found</p></div>',
                zeroRecords: '<div class="cms-empty"><i class="fa fa-search"></i><p>No matching pages found</p></div>',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ pages',
                infoEmpty: 'No pages available',
                paginate: {
                    previous: '<i class="fa fa-angle-left"></i>',
                    next: '<i class="fa fa-angle-right"></i>'
                }
            }
        });

        // Select All Checkbox
        $('#selectAllCheckbox').on('change', function () {
            var isChecked = $(this).is(':checked');
            $('.row-checkbox').prop('checked', isChecked);
            updateBulkDeleteBtn();
        });

        // Individual Checkbox Change
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

        // Bulk Delete Button Click
        $('#btnBulkDelete').on('click', function () {
            var selectedIds = [];
            $('.row-checkbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert('Please select at least one item.');
                return;
            }

            if (confirm('Are you sure you want to delete ' + selectedIds.length + ' selected CMS page(s)? All associated content will also be removed.')) {
                $('#btnBulkDelete').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
                $.ajax({
                    url: "{{ route('bulk.delete.cms') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selectedIds
                    },
                    success: function (response) {
                        $('#btnBulkDelete').prop('disabled', false).html('<i class="fa fa-trash-o"></i> Delete Selected (<span id="selectedCount">0</span>)');
                        if (response.status === 'ok') {
                            oTable.draw(false);
                        } else {
                            alert('Failed to delete: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function () {
                        $('#btnBulkDelete').prop('disabled', false).html('<i class="fa fa-trash-o"></i> Delete Selected (<span id="selectedCount">0</span>)');
                        alert('An error occurred while deleting.');
                    }
                });
            }
        });

        $('#cms-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#id').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#page_slug').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });

    function delete_cms(id) {
        if (confirm('Are you sure you want to delete this CMS page? All related content will be removed too.')) {
            $.post("{{ route('delete.cms') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response === 'ok') {
                        var table = $('#cms_datatable_ajax').DataTable();
                        table.ajax.reload();
                    } else {
                        alert('Request Failed! Please try again.');
                    }
                })
                .fail(function() {
                    alert('Server error. Please try again.');
                });
        }
    }
</script>
@endpush