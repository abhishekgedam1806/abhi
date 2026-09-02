@extends('admin.layouts.admin_layout')
@section('content')
<style>
/* ============================================================
   SEO Management — Modern Admin UI with Bulk Actions
   ============================================================ */
.seo-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
    padding: 0 0 20px 0;
}

/* Breadcrumb */
.seo-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #94A3B8;
    margin-bottom: 20px;
    font-weight: 500;
}
.seo-breadcrumb a {
    color: #64748B;
    text-decoration: none;
    transition: color 0.15s;
}
.seo-breadcrumb a:hover { color: #3B82F6; }
.seo-breadcrumb i { font-size: 8px; color: #CBD5E1; }

/* Page Header Row */
.seo-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.seo-page-title {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: -0.3px;
}
.seo-page-title .title-icon {
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
.seo-page-subtitle {
    font-size: 13px;
    color: #94A3B8;
    font-weight: 500;
    margin-top: 2px;
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

/* Main Card */
.seo-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.seo-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1px solid #F1F5F9;
    background: #FAFBFC;
}
.seo-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #0F172A;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.seo-card-title i {
    color: #0EA5E9;
    font-size: 15px;
}
.seo-record-badge {
    background: #F0F9FF;
    color: #0284C7;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    border: 1px solid #BAE6FD;
}

/* DataTable Overrides */
.seo-card .dataTables_wrapper {
    padding: 0;
}
.seo-card .dataTables_length {
    padding: 14px 22px 4px;
}
.seo-card .dataTables_length label {
    font-size: 13px;
    color: #64748B;
    font-weight: 500;
}
.seo-card .dataTables_length select {
    border: 1px solid #E2E8F0;
    border-radius: 7px;
    padding: 3px 8px;
    font-size: 13px;
    color: #1E293B;
    background: #fff;
    margin: 0 6px;
}
.seo-card .dataTables_info {
    padding: 14px 22px;
    font-size: 12.5px;
    color: #94A3B8;
    font-weight: 500;
}
.seo-card .dataTables_paginate {
    padding: 14px 22px;
}
.seo-card .paginate_button {
    border-radius: 7px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 4px 10px !important;
    margin: 0 2px !important;
}
.seo-card .paginate_button.current,
.seo-card .paginate_button.current:hover {
    background: #0284C7 !important;
    border-color: #0284C7 !important;
    color: #fff !important;
}
.seo-card .paginate_button:hover {
    background: #F1F5F9 !important;
    border-color: #E2E8F0 !important;
    color: #1E293B !important;
}

/* Table */
#seo_datatable_ajax {
    width: 100% !important;
    border-collapse: collapse !important;
}
#seo_datatable_ajax thead tr.heading th {
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
#seo_datatable_ajax thead tr.filter td {
    padding: 10px 16px;
    background: #FAFBFC;
    border-bottom: 1px solid #F1F5F9;
}
#seo_datatable_ajax thead tr.filter td input {
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
#seo_datatable_ajax thead tr.filter td input:focus {
    border-color: #0284C7;
    box-shadow: 0 0 0 3px rgba(2,132,199,0.08);
}
#seo_datatable_ajax tbody tr {
    border-bottom: 1px solid #F1F5F9;
    transition: background 0.15s;
}
#seo_datatable_ajax tbody tr:hover {
    background: #F8FAFC;
}
#seo_datatable_ajax tbody td {
    padding: 14px 16px;
    font-size: 13.5px;
    color: #334155;
    vertical-align: middle;
    border: none;
}

/* ID Badge */
.seo-id-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 30px;
    height: 26px;
    background: #F0F9FF;
    color: #0284C7;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid #BAE6FD;
    padding: 0 8px;
}

/* Page Tag */
.seo-page-tag {
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
.seo-page-tag i { color: #0EA5E9; font-size: 12px; }

/* Action Buttons */
.seo-action-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
}
.btn-seo-edit {
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
.btn-seo-edit:hover {
    background: #2563EB;
    color: #fff !important;
    border-color: #2563EB;
}
.btn-seo-delete {
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
.btn-seo-delete:hover {
    background: #DC2626;
    color: #fff !important;
    border-color: #DC2626;
}

/* Empty state */
.seo-empty {
    text-align: center;
    padding: 48px 20px;
    color: #94A3B8;
}
.seo-empty i { font-size: 40px; margin-bottom: 12px; display: block; }
.seo-empty p { font-size: 14px; font-weight: 500; margin: 0; }
</style>

<div class="page-content-wrapper">
    <div class="page-content">

        <div class="seo-wrapper">
            {{-- Breadcrumb --}}
            <div class="seo-breadcrumb">
                <a href="{{ route('admin.home') }}"><i class="fa fa-home"></i> Home</a>
                <i class="fa fa-angle-right"></i>
                <span>SEO Management</span>
            </div>

            {{-- Page Header --}}
            <div class="seo-header-row">
                <div>
                    <h1 class="seo-page-title">
                        <span class="title-icon"><i class="fa fa-search-plus"></i></span>
                        Manage SEO Meta Tags
                    </h1>
                    <div class="seo-page-subtitle">Configure search engine titles, descriptions, and keywords for core pages</div>
                </div>
                <div>
                    <button type="button" id="btnBulkDelete" class="btn-bulk-delete">
                        <i class="fa fa-trash-o"></i> Delete Selected (<span id="selectedCount">0</span>)
                    </button>
                </div>
            </div>

            {{-- Flash Messages --}}
            @include('flash::message')

            {{-- Main Card --}}
            <div class="seo-card">
                {{-- Card Header --}}
                <div class="seo-card-header">
                    <h2 class="seo-card-title">
                        <i class="fa fa-globe"></i> Core System Pages SEO
                    </h2>
                    <span class="seo-record-badge" id="seo-total-badge">Loading...</span>
                </div>

                {{-- DataTable --}}
                <div style="padding: 0;">
                    <form method="post" role="form" id="seo-search-form">
                        <table class="table" id="seo_datatable_ajax" style="margin:0;">
                            <thead>
                                <tr role="row" class="filter">
                                    <td style="width:45px;text-align:center;">-</td>
                                    <td style="width:100px;"><input type="text" class="form-control" name="id" id="id" autocomplete="off" placeholder="ID..."></td>
                                    <td><input type="text" class="form-control" name="page_title" id="page_title" autocomplete="off" placeholder="Search page name..."></td>
                                    <td style="width:170px;"></td>
                                </tr>
                                <tr role="row" class="heading">
                                    <th style="width:45px;text-align:center;">
                                        <input type="checkbox" id="selectAllCheckbox" style="cursor:pointer;width:17px;height:17px;" title="Select All" />
                                    </th>
                                    <th style="width:100px;"><i class="fa fa-hashtag" style="margin-right:5px;color:#94A3B8;"></i>ID</th>
                                    <th><i class="fa fa-file-text-o" style="margin-right:5px;color:#94A3B8;"></i>Page Name</th>
                                    <th style="width:170px;"><i class="fa fa-bolt" style="margin-right:5px;color:#94A3B8;"></i>Actions</th>
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
        var oTable = $('#seo_datatable_ajax').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            "order": [[1, "asc"]],
            ajax: {
                url: '{!! route('fetch.data.seo') !!}',
                data: function (d) {
                    d.id = $('input[name=id]').val();
                    d.page_title = $('input[name=page_title]').val();
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                {
                    data: 'id',
                    name: 'id',
                    render: function(data) {
                        return '<span class="seo-id-badge">' + data + '</span>';
                    }
                },
                {
                    data: 'page_title',
                    name: 'page_title',
                    render: function(data) {
                        var formatted = data.replace(/_/g, ' ').toUpperCase();
                        return '<span class="seo-page-tag"><i class="fa fa-globe"></i>' + formatted + '</span>';
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            drawCallback: function(settings) {
                var total = settings.fnRecordsTotal();
                $('#seo-total-badge').text(total + ' Pages Configured');
                $('#selectAllCheckbox').prop('checked', false);
                updateBulkDeleteBtn();
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin" style="color:#0284C7;font-size:20px;"></i>',
                emptyTable: '<div class="seo-empty"><i class="fa fa-globe"></i><p>No SEO entries found</p></div>',
                zeroRecords: '<div class="seo-empty"><i class="fa fa-search"></i><p>No matching pages found</p></div>',
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

            if (confirm('Are you sure you want to delete ' + selectedIds.length + ' selected SEO entry(s)?')) {
                $('#btnBulkDelete').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
                $.ajax({
                    url: "{{ route('bulk.delete.seos') }}",
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

        $('#seo-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#id').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $('#page_title').on('keyup', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });

    function delete_seo(id) {
        if (confirm('Are you sure you want to delete this SEO entry?')) {
            $.post("{{ route('delete.seo') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response === 'ok') {
                        var table = $('#seo_datatable_ajax').DataTable();
                        table.ajax.reload();
                    } else {
                        alert('Request Failed!');
                    }
                });
        }
    }
</script>
@endpush