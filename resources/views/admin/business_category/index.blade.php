@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div>
                <h1 style="font-size:22px;font-weight:800;color:#0F172A;margin:0;">
                    <i class="fa fa-folder-open text-primary"></i> Manage Business Categories
                </h1>
                <div style="font-size:13px;color:#94A3B8;">Independent categories for local business directory</div>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="button" id="btnBulkDelete" class="btn btn-danger btn-xs" style="display:none;font-weight:600;border-radius:6px;padding:6px 12px;">
                    <i class="fa fa-trash"></i> Delete Selected (<span id="selectedCount">0</span>)
                </button>
                <a href="{{ route('admin.create.business_category') }}" class="btn btn-primary btn-xs" style="font-weight:600;border-radius:6px;padding:6px 14px;">
                    <i class="fa fa-plus"></i> Add Category
                </a>
            </div>
        </div>

        @include('flash::message')

        <div class="portlet light bordered" style="border-radius:12px;">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="cat_datatable_ajax">
                    <thead>
                        <tr role="row" class="heading">
                            <th style="width:35px;text-align:center;">
                                <input type="checkbox" id="selectAllCheckbox" style="cursor:pointer;" />
                            </th>
                            <th>Category Name</th>
                            <th>Slug</th>
                            <th style="width:120px;text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        var oTable = $('#cat_datatable_ajax').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.fetch.business_categories') !!}',
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                {data: 'icon_display', name: 'name'},
                {data: 'slug', name: 'slug'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
            ]
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
                $('#btnBulkDelete').fadeIn(150);
            } else {
                $('#btnBulkDelete').fadeOut(150);
            }
        }

        $('#btnBulkDelete').on('click', function () {
            var selectedIds = [];
            $('.row-checkbox:checked').each(function () { selectedIds.push($(this).val()); });
            if (selectedIds.length === 0) return;

            if (confirm('Delete ' + selectedIds.length + ' categories?')) {
                $.post("{{ route('admin.bulk.delete.business_categories') }}", {
                    _token: "{{ csrf_token() }}",
                    ids: selectedIds
                }, function(res) {
                    if (res.status === 'ok') {
                        oTable.draw(false);
                    }
                });
            }
        });
    });

    function delete_category(id) {
        if (confirm('Delete this category?')) {
            $.post("{{ route('admin.delete.business_category') }}", {
                _method: 'DELETE',
                _token: "{{ csrf_token() }}",
                id: id
            }, function() {
                $('#cat_datatable_ajax').DataTable().draw(false);
            });
        }
    }
</script>
@endpush
