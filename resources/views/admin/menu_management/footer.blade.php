@extends('admin.layouts.admin_layout')

@section('content')
<style>
.menu-mgmt-wrap {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
    padding: 10px 0 30px 0;
}
.menu-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #94A3B8;
    margin-bottom: 20px;
    font-weight: 500;
}
.menu-breadcrumb a { color: #64748B; text-decoration: none; }
.menu-breadcrumb a:hover { color: #2563EB; }
.menu-breadcrumb i { font-size: 9px; color: #CBD5E1; }

.menu-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.menu-page-title {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.menu-page-title .title-icon {
    width: 38px;
    height: 38px;
    background: #03855c;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 17px;
    box-shadow: 0 4px 12px rgba(3,133,92,0.25);
}

.card-box {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 22px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.card-box-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #F1F5F9;
    padding-bottom: 14px;
    margin-bottom: 18px;
}
.card-box-title {
    font-size: 16px;
    font-weight: 700;
    color: #0F172A;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.table-menu {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.table-menu th {
    background: #F8FAFC;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 10px 14px;
    border-bottom: 1px solid #E2E8F0;
}
.table-menu td {
    padding: 12px 14px;
    border-bottom: 1px solid #F1F5F9;
    font-size: 13px;
    vertical-align: middle;
}
.table-menu tr:hover td {
    background: #F8FAFC;
}

/* Modern Clickable Status Toggle Switch */
.status-toggle-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    cursor: pointer;
    padding: 4px 10px;
    border-radius: 20px;
    outline: none !important;
    transition: all 0.2s ease;
    user-select: none;
}
.status-toggle-btn:hover {
    background: #F1F5F9;
    border-color: #CBD5E1;
}
.toggle-track {
    position: relative;
    display: inline-block;
    width: 32px;
    height: 18px;
    background-color: #CBD5E1;
    border-radius: 20px;
    transition: background-color 0.25s ease;
}
.status-toggle-btn.active .toggle-track {
    background-color: #03855c;
}
.toggle-thumb {
    position: absolute;
    top: 2px;
    left: 2px;
    width: 14px;
    height: 14px;
    background-color: #FFFFFF;
    border-radius: 50%;
    transition: transform 0.25s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.25);
}
.status-toggle-btn.active .toggle-thumb {
    transform: translateX(14px);
}
.toggle-text {
    font-size: 11.5px;
    font-weight: 700;
    color: #64748B;
    min-width: 48px;
    text-align: left;
}
.status-toggle-btn.active .toggle-text {
    color: #03855c;
}

.btn-add-primary {
    background: #03855c;
    color: #FFFFFF !important;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 16px;
    border-radius: 8px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    text-decoration: none !important;
    box-shadow: 0 2px 6px rgba(3,133,92,0.25);
    transition: all 0.15s ease;
}
.btn-add-primary:hover {
    background: #026647;
    transform: translateY(-1px);
}

.btn-action-icon {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #E2E8F0;
    background: #FFFFFF;
    color: #475569;
    font-size: 12px;
    text-decoration: none !important;
    transition: all 0.15s ease;
    cursor: pointer;
}
.btn-action-icon:hover {
    border-color: #CBD5E1;
    background: #F8FAFC;
    color: #0F172A;
}
.btn-action-icon.text-danger:hover {
    background: #FEE2E2;
    border-color: #FECACA;
    color: #DC2626 !important;
}

.nav-tabs-custom {
    display: flex;
    border-bottom: 2px solid #E2E8F0;
    gap: 8px;
    margin-bottom: 22px;
}
.nav-tabs-custom a {
    padding: 10px 18px;
    font-weight: 700;
    font-size: 13.5px;
    color: #64748B;
    text-decoration: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}
.nav-tabs-custom a:hover {
    color: #0F172A;
}
.nav-tabs-custom a.active {
    color: #03855c;
    border-bottom-color: #03855c;
}

.form-label-custom {
    font-size: 12.5px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
    display: block;
}
.form-control-custom {
    width: 100%;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 13.5px;
    color: #0F172A;
    transition: border-color 0.15s;
    outline: none;
}
.form-control-custom:focus {
    border-color: #03855c;
    box-shadow: 0 0 0 3px rgba(3,133,92,0.12);
}
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="menu-mgmt-wrap">
            
            <!-- Breadcrumb -->
            <div class="menu-breadcrumb">
                <a href="{{ route('admin.home') }}"><i class="fa fa-home"></i> Dashboard</a>
                <i class="fa fa-chevron-right"></i>
                <span>Header & Footer</span>
                <i class="fa fa-chevron-right"></i>
                <span style="color: #0F172A; font-weight: 700;">Footer Management</span>
            </div>

            @include('flash::message')

            <!-- Header Row -->
            <div class="menu-header-row">
                <h1 class="menu-page-title">
                    <span class="title-icon"><i class="fa fa-sitemap"></i></span>
                    Footer Layout & Menu Management
                </h1>
            </div>

            <!-- Global Footer Settings Form -->
            <div class="card-box">
                <div class="card-box-header">
                    <h3 class="card-box-title"><i class="fa fa-columns text-primary"></i> Footer Column Titles & Bottom Bar Settings</h3>
                </div>

                <form action="{{ route('admin.footer.settings.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 col-sm-6 form-group mb-3">
                            <label class="form-label-custom">Column 1 Title</label>
                            <input type="text" name="footer_col1_title" class="form-control-custom" value="{{ $siteSetting->footer_col1_title ?? 'Quick Links' }}" required>
                        </div>
                        <div class="col-md-3 col-sm-6 form-group mb-3">
                            <label class="form-label-custom">Column 2 Title</label>
                            <input type="text" name="footer_col2_title" class="form-control-custom" value="{{ $siteSetting->footer_col2_title ?? 'Jobs By Functional Area' }}" required>
                        </div>
                        <div class="col-md-3 col-sm-6 form-group mb-3">
                            <label class="form-label-custom">Column 3 Title</label>
                            <input type="text" name="footer_col3_title" class="form-control-custom" value="{{ $siteSetting->footer_col3_title ?? 'Jobs By Industry' }}" required>
                        </div>
                        <div class="col-md-3 col-sm-6 form-group mb-3">
                            <label class="form-label-custom">Column 4 Title</label>
                            <input type="text" name="footer_col4_title" class="form-control-custom" value="{{ $siteSetting->footer_col4_title ?? 'Contact Us' }}" required>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">Copyright Disclaimer Text (Leave empty for default)</label>
                            <input type="text" name="footer_copyright_text" class="form-control-custom" placeholder="Copyright © {{ date('Y') }} {{ $siteSetting->site_name }}. All Rights Reserved." value="{{ $siteSetting->footer_copyright_text }}">
                        </div>
                        <div class="col-md-3 col-sm-6 form-group mb-3">
                            <label class="form-label-custom">Popular Cities Bar</label>
                            <div style="margin-top: 8px;">
                                <label style="cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 700; color: #03855c; font-size: 13px; margin: 0;">
                                    <input type="checkbox" name="footer_show_popular_cities" value="1" {{ ($siteSetting->footer_show_popular_cities ?? 1) ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;"> Show Cities Bar
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 form-group mb-3">
                            <label class="form-label-custom">Payment Icons</label>
                            <div style="margin-top: 8px;">
                                <label style="cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 700; color: #03855c; font-size: 13px; margin: 0;">
                                    <input type="checkbox" name="footer_show_payment_icons" value="1" {{ ($siteSetting->footer_show_payment_icons ?? 1) ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;"> Show Payment Badges
                                </label>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: right; border-top: 1px solid #F1F5F9; padding-top: 14px; margin-top: 10px;">
                        <button type="submit" class="btn-add-primary">
                            <i class="fa fa-save"></i> Save Column Titles & Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Columns Tabs -->
            <div class="nav-tabs-custom">
                <a href="#tab-col1" class="active" data-toggle="tab"><i class="fa fa-link"></i> Column 1: {{ $siteSetting->footer_col1_title ?? 'Quick Links' }} ({{ count($col1Items) }})</a>
                <a href="#tab-col2" data-toggle="tab"><i class="fa fa-th-large"></i> Column 2: {{ $siteSetting->footer_col2_title ?? 'Categories' }} ({{ count($col2Items) }})</a>
                <a href="#tab-col3" data-toggle="tab"><i class="fa fa-industry"></i> Column 3: {{ $siteSetting->footer_col3_title ?? 'Industries' }} ({{ count($col3Items) }})</a>
                <a href="#tab-cities" data-toggle="tab"><i class="fa fa-map-marker"></i> Popular Cities Bar ({{ count($cityItems) }})</a>
                <a href="#tab-contact" data-toggle="tab"><i class="fa fa-address-card-o"></i> Column 4: Contact Info</a>
            </div>

            <div class="tab-content">
                <!-- Tab: Column 1 -->
                <div class="tab-pane active" id="tab-col1">
                    @include('admin.menu_management.inc.footer_column_table', ['items' => $col1Items, 'menuType' => 'footer_col1', 'colTitle' => $siteSetting->footer_col1_title ?? 'Quick Links'])
                </div>

                <!-- Tab: Column 2 -->
                <div class="tab-pane" id="tab-col2">
                    @include('admin.menu_management.inc.footer_column_table', ['items' => $col2Items, 'menuType' => 'footer_col2', 'colTitle' => $siteSetting->footer_col2_title ?? 'Categories'])
                </div>

                <!-- Tab: Column 3 -->
                <div class="tab-pane" id="tab-col3">
                    @include('admin.menu_management.inc.footer_column_table', ['items' => $col3Items, 'menuType' => 'footer_col3', 'colTitle' => $siteSetting->footer_col3_title ?? 'Industries'])
                </div>

                <!-- Tab: Cities Bar -->
                <div class="tab-pane" id="tab-cities">
                    @include('admin.menu_management.inc.footer_column_table', ['items' => $cityItems, 'menuType' => 'footer_cities', 'colTitle' => 'Popular Cities Links'])
                </div>

                <!-- Tab: Column 4 Contact Details -->
                <div class="tab-pane" id="tab-contact">
                    <div class="card-box">
                        <div class="card-box-header">
                            <h3 class="card-box-title"><i class="fa fa-address-book-o text-primary"></i> Column 4: Contact & Social Info</h3>
                        </div>
                        <div style="font-size: 13.5px; color: #475569; line-height: 1.6;">
                            <p>Footer Column 4 automatically displays your company's official address, support email, phone number, and active social media icons from <strong>Site Settings</strong>.</p>
                            
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px; margin: 16px 0; max-width: 500px;">
                                <div><strong>Address:</strong> {{ $siteSetting->site_street_address ?: 'Not set' }}</div>
                                <div style="margin-top: 6px;"><strong>Email:</strong> {{ $siteSetting->mail_to_address ?: 'Not set' }}</div>
                                <div style="margin-top: 6px;"><strong>Phone:</strong> {{ $siteSetting->site_phone_primary ?: 'Not set' }}</div>
                            </div>

                            <a href="{{ route('edit.site.setting') }}" class="btn-add-primary" style="display: inline-flex;">
                                <i class="fa fa-pencil"></i> Edit Address, Phone & Social Links in Site Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal: Add Footer Link -->
<div class="modal fade" id="modalAddFooterItem" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 1px solid #F1F5F9; padding: 16px 22px;">
                <h5 class="modal-title" style="font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-plus-circle text-primary"></i> <span id="modalAddTitle">Add New Footer Link</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.menu.store') }}" method="POST">
                @csrf
                <input type="hidden" name="menu_type" id="add_menu_type" value="footer_col1">
                <div class="modal-body" style="padding: 22px;">
                    <div class="form-group mb-3">
                        <label class="form-label-custom">Link Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="footer_add_title" class="form-control-custom" placeholder="e.g. Terms of Service / IT Jobs" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label-custom">Target URL / Path <span class="text-danger">*</span></label>
                        <input type="text" name="url" id="footer_add_url" class="form-control-custom" placeholder="e.g. /jobs-in-pune or /cms/privacy-policy" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">Target Window</label>
                            <select name="target" class="form-control-custom">
                                <option value="_self">Same Tab (_self)</option>
                                <option value="_blank">New Tab (_blank)</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">Sort Order Number</label>
                            <input type="number" name="order_num" id="footer_add_order" class="form-control-custom" value="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #F1F5F9; padding: 14px 22px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                    <button type="submit" class="btn-add-primary"><i class="fa fa-plus"></i> Add Link</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Footer Link -->
<div class="modal fade" id="modalEditFooterItem" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 1px solid #F1F5F9; padding: 16px 22px;">
                <h5 class="modal-title" style="font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-pencil-square-o text-primary"></i> Edit Footer Link
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditFooterItem" method="POST">
                @csrf
                <div class="modal-body" style="padding: 22px;">
                    <div class="form-group mb-3">
                        <label class="form-label-custom">Link Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="footer_edit_title" class="form-control-custom" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label-custom">Target URL / Path <span class="text-danger">*</span></label>
                        <input type="text" name="url" id="footer_edit_url" class="form-control-custom" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">Target Window</label>
                            <select name="target" id="footer_edit_target" class="form-control-custom">
                                <option value="_self">Same Tab (_self)</option>
                                <option value="_blank">New Tab (_blank)</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">Sort Order Number</label>
                            <input type="number" name="order_num" id="footer_edit_order_num" class="form-control-custom">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #F1F5F9; padding: 14px 22px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                    <button type="submit" class="btn-add-primary"><i class="fa fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddFooterModal(menuType, colName) {
    $('#add_menu_type').val(menuType);
    $('#modalAddTitle').text('Add Link to ' + colName);
    $('#footer_add_title').val('');
    $('#footer_add_url').val('');
    $('#modalAddFooterItem').modal('show');
}

function openEditFooterModal(item) {
    $('#footer_edit_title').val(item.title);
    $('#footer_edit_url').val(item.url);
    $('#footer_edit_target').val(item.target || '_self');
    $('#footer_edit_order_num').val(item.order_num);
    $('#formEditFooterItem').attr('action', '{{ url("admin/menu-item/update") }}/' + item.id);
    $('#modalEditFooterItem').modal('show');
}

function toggleItemStatus(btn, id) {
    var $btn = $(btn);
    $btn.prop('disabled', true).css('opacity', '0.6');

    $.ajax({
        url: '{{ url("admin/menu-item/toggle-status") }}/' + id,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            $btn.prop('disabled', false).css('opacity', '1');
            if (res.is_active) {
                $btn.addClass('active');
                $btn.find('.toggle-text').text('Active');
            } else {
                $btn.removeClass('active');
                $btn.find('.toggle-text').text('Inactive');
            }
        },
        error: function(err) {
            $btn.prop('disabled', false).css('opacity', '1');
            alert('Could not update status. Please try again.');
        }
    });
}

function saveOrderNums() {
    var orders = [];
    $('.order-input').each(function() {
        orders.push({
            id: $(this).data('id'),
            order_num: $(this).val()
        });
    });

    $.ajax({
        url: '{{ route("admin.menu.reorder") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            orders: orders
        },
        success: function(res) {
            location.reload();
        },
        error: function(err) {
            alert('Failed to update order numbers.');
        }
    });
}
</script>
@endsection
