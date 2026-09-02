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
    background: #2563EB;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 17px;
    box-shadow: 0 4px 12px rgba(37,99,235,0.25);
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
    padding: 12px 16px;
    border-bottom: 1px solid #E2E8F0;
}
.table-menu td {
    padding: 14px 16px;
    border-bottom: 1px solid #F1F5F9;
    font-size: 13.5px;
    vertical-align: middle;
}
.table-menu tr:hover td {
    background: #F8FAFC;
}

.badge-aud {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
}
.badge-aud-all { background: #EFF6FF; color: #2563EB; }
.badge-aud-seeker { background: #ECFDF5; color: #03855c; }
.badge-aud-company { background: #FEF3C7; color: #D97706; }
.badge-aud-guest { background: #F1F5F9; color: #475569; }

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
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 13px;
    font-weight: 700;
    padding: 9px 18px;
    border-radius: 8px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    text-decoration: none !important;
    box-shadow: 0 2px 6px rgba(37,99,235,0.25);
    transition: all 0.15s ease;
}
.btn-add-primary:hover {
    background: #1D4ED8;
    transform: translateY(-1px);
}

.btn-action-icon {
    width: 32px;
    height: 32px;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #E2E8F0;
    background: #FFFFFF;
    color: #475569;
    font-size: 13px;
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
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
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
                <span style="color: #0F172A; font-weight: 700;">Header Management</span>
            </div>

            @include('flash::message')

            <!-- Header Row -->
            <div class="menu-header-row">
                <h1 class="menu-page-title">
                    <span class="title-icon"><i class="fa fa-compass"></i></span>
                    Header Menu & Navigation Management
                </h1>
                <div>
                    <button type="button" class="btn-add-primary" data-toggle="modal" data-target="#modalAddHeaderItem">
                        <i class="fa fa-plus"></i> Add New Header Link
                    </button>
                </div>
            </div>

            <!-- Notice Alert Banner -->
            <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: flex-start; gap: 12px;">
                <i class="fa fa-info-circle text-primary" style="font-size: 20px; margin-top: 2px;"></i>
                <div style="font-size: 13px; color: #1E3A8A; line-height: 1.5;">
                    <strong>Header Menu Control:</strong> Yahan se aap top header me dikhne wale sabhi menu links ko apni marzi se <strong>Add, Edit, Delete, Aage-Peeche (Order)</strong> aur <strong>Active / Inactive Switch</strong> se on/off kar sakte hain.
                </div>
            </div>

            <div class="row">
                <!-- Main Header Links Table -->
                <div class="col-lg-8 col-12">
                    <div class="card-box">
                        <div class="card-box-header">
                            <h3 class="card-box-title"><i class="fa fa-list-ol text-primary"></i> Active Header Links ({{ count($headerItems) }})</h3>
                            <span style="font-size: 12px; color: #64748B;">Change Order No. and click Save</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table-menu">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">Order</th>
                                        <th>Link Title</th>
                                        <th>Target URL</th>
                                        <th>Audience</th>
                                        <th style="text-align: center; width: 110px;">Status</th>
                                        <th style="text-align: right; width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($headerItems as $item)
                                    <tr id="item-row-{{ $item->id }}">
                                        <td>
                                            <input type="number" class="form-control-custom order-input" data-id="{{ $item->id }}" value="{{ $item->order_num }}" style="width: 55px; padding: 4px 6px; text-align: center; font-weight: 700;">
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; color: #0F172A; display: flex; align-items: center; gap: 8px;">
                                                @if(!empty($item->icon))
                                                <i class="{{ $item->icon }}" style="color: #2563EB; font-size: 14px;"></i>
                                                @endif
                                                <span>{{ $item->title }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <code style="background: #F1F5F9; color: #0F172A; padding: 3px 6px; border-radius: 4px; font-size: 12px;">{{ $item->url }}</code>
                                            @if($item->target === '_blank')
                                            <span style="font-size: 11px; color: #64748B;" title="Opens in new tab"><i class="fa fa-external-link"></i></span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->audience === 'seeker')
                                            <span class="badge-aud badge-aud-seeker"><i class="fa fa-user"></i> Seekers</span>
                                            @elseif($item->audience === 'company')
                                            <span class="badge-aud badge-aud-company"><i class="fa fa-building"></i> Employers</span>
                                            @elseif($item->audience === 'guest')
                                            <span class="badge-aud badge-aud-guest"><i class="fa fa-lock"></i> Guests</span>
                                            @else
                                            <span class="badge-aud badge-aud-all"><i class="fa fa-globe"></i> Everyone</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="status-toggle-btn {{ $item->is_active ? 'active' : '' }}" onclick="toggleItemStatus(this, {{ $item->id }})" title="Click to toggle Active / Inactive">
                                                <span class="toggle-track">
                                                    <span class="toggle-thumb"></span>
                                                </span>
                                                <span class="toggle-text">{{ $item->is_active ? 'Active' : 'Inactive' }}</span>
                                            </button>
                                        </td>
                                        <td style="text-align: right;">
                                            <button type="button" class="btn-action-icon" onclick='openEditModal(@json($item))' title="Edit Link">
                                                <i class="fa fa-pencil text-primary"></i>
                                            </button>
                                            <form action="{{ route('admin.menu.delete', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this header link?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-icon text-danger" title="Delete Link">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: #94A3B8; padding: 30px;">
                                            <i class="fa fa-bars" style="font-size: 32px; margin-bottom: 8px; display: block;"></i>
                                            No header menu items found. Click "Add New Header Link" to create one.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(count($headerItems) > 0)
                        <div style="margin-top: 16px; text-align: right;">
                            <button type="button" class="btn-action-icon" onclick="saveOrderNums()" style="width: auto; padding: 6px 14px; font-weight: 700; color: #2563EB;">
                                <i class="fa fa-save"></i> Save Order Changes
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Header Actions & Global Controls -->
                <div class="col-lg-4 col-12">
                    <div class="card-box">
                        <div class="card-box-header">
                            <h3 class="card-box-title"><i class="fa fa-sliders text-primary"></i> Header Global Features</h3>
                        </div>

                        <form action="{{ route('admin.header.settings.update') }}" method="POST">
                            @csrf
                            <div style="display: flex; flex-direction: column; gap: 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px;">
                                    <div>
                                        <div style="font-size: 13.5px; font-weight: 700; color: #0F172A;">"Post a Job" Button</div>
                                        <div style="font-size: 11.5px; color: #64748B;">Show recruiter Post a Job button in header</div>
                                    </div>
                                    <div>
                                        <label style="cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 700; color: #03855c; font-size: 13px; margin: 0;">
                                            <input type="checkbox" name="header_show_post_job" value="1" {{ ($siteSetting->header_show_post_job ?? 1) ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;"> Enable
                                        </label>
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px;">
                                    <div>
                                        <div style="font-size: 13.5px; font-weight: 700; color: #0F172A;">Notification Bell</div>
                                        <div style="font-size: 11.5px; color: #64748B;">Show alerts counter for logged-in users</div>
                                    </div>
                                    <div>
                                        <label style="cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 700; color: #03855c; font-size: 13px; margin: 0;">
                                            <input type="checkbox" name="header_show_notifications" value="1" {{ ($siteSetting->header_show_notifications ?? 1) ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;"> Enable
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn-add-primary" style="justify-content: center; width: 100%; margin-top: 6px;">
                                    <i class="fa fa-check-circle"></i> Save Header Features
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Preset Links Helper -->
                    <div class="card-box" style="background: #F8FAFC;">
                        <h4 style="font-size: 14px; font-weight: 700; color: #334155; margin-bottom: 12px;">
                            <i class="fa fa-link text-primary"></i> Common Quick Links
                        </h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                            <button type="button" class="btn-action-icon" style="width: auto; padding: 4px 10px; font-size: 12px;" onclick="fillQuickLink('Jobs', '/jobs', 'fa fa-briefcase')">+ Jobs</button>
                            <button type="button" class="btn-action-icon" style="width: auto; padding: 4px 10px; font-size: 12px;" onclick="fillQuickLink('Companies', '/companies', 'fa fa-building-o')">+ Companies</button>
                            <button type="button" class="btn-action-icon" style="width: auto; padding: 4px 10px; font-size: 12px;" onclick="fillQuickLink('Businesses', '/businesses', 'fa fa-handshake-o')">+ Businesses</button>
                            <button type="button" class="btn-action-icon" style="width: auto; padding: 4px 10px; font-size: 12px;" onclick="fillQuickLink('Pricing', '/pricing', 'fa fa-tags')">+ Pricing</button>
                            <button type="button" class="btn-action-icon" style="width: auto; padding: 4px 10px; font-size: 12px;" onclick="fillQuickLink('Blog', '/blog', 'fa fa-newspaper-o')">+ Blog</button>
                            <button type="button" class="btn-action-icon" style="width: auto; padding: 4px 10px; font-size: 12px;" onclick="fillQuickLink('Contact Us', '/contact-us', 'fa fa-envelope-o')">+ Contact Us</button>
                            <button type="button" class="btn-action-icon" style="width: auto; padding: 4px 10px; font-size: 12px;" onclick="fillQuickLink('FAQs', '/faq', 'fa fa-question-circle')">+ FAQs</button>
                            <button type="button" class="btn-action-icon" style="width: auto; padding: 4px 10px; font-size: 12px;" onclick="fillQuickLink('About Us', '/cms/about-us', 'fa fa-info-circle')">+ About Us</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal: Add Header Link -->
<div class="modal fade" id="modalAddHeaderItem" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 1px solid #F1F5F9; padding: 16px 22px;">
                <h5 class="modal-title" style="font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-plus-circle text-primary"></i> Add New Header Link
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.menu.store') }}" method="POST">
                @csrf
                <input type="hidden" name="menu_type" value="header">
                <div class="modal-body" style="padding: 22px;">
                    <div class="form-group mb-3">
                        <label class="form-label-custom">Link Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="add_title" class="form-control-custom" placeholder="e.g. Remote Jobs / Offers" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label-custom">Target URL / Path <span class="text-danger">*</span></label>
                        <input type="text" name="url" id="add_url" class="form-control-custom" placeholder="e.g. /jobs or https://..." required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">FontAwesome Icon</label>
                            <input type="text" name="icon" id="add_icon" class="form-control-custom" placeholder="e.g. fa fa-briefcase">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">Target Window</label>
                            <select name="target" class="form-control-custom">
                                <option value="_self">Same Tab (_self)</option>
                                <option value="_blank">New Tab (_blank)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">Visible To Audience</label>
                            <select name="audience" class="form-control-custom">
                                <option value="all">Everyone (Public)</option>
                                <option value="seeker">Job Seekers Only</option>
                                <option value="company">Employers Only</option>
                                <option value="guest">Guests Only (Logged Out)</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">Sort Order Number</label>
                            <input type="number" name="order_num" class="form-control-custom" value="{{ (count($headerItems) + 1) }}">
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

<!-- Modal: Edit Header Link -->
<div class="modal fade" id="modalEditHeaderItem" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 1px solid #F1F5F9; padding: 16px 22px;">
                <h5 class="modal-title" style="font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-pencil-square-o text-primary"></i> Edit Header Link
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditHeaderItem" method="POST">
                @csrf
                <div class="modal-body" style="padding: 22px;">
                    <div class="form-group mb-3">
                        <label class="form-label-custom">Link Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_title" class="form-control-custom" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label-custom">Target URL / Path <span class="text-danger">*</span></label>
                        <input type="text" name="url" id="edit_url" class="form-control-custom" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">FontAwesome Icon</label>
                            <input type="text" name="icon" id="edit_icon" class="form-control-custom">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">Target Window</label>
                            <select name="target" id="edit_target" class="form-control-custom">
                                <option value="_self">Same Tab (_self)</option>
                                <option value="_blank">New Tab (_blank)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">Visible To Audience</label>
                            <select name="audience" id="edit_audience" class="form-control-custom">
                                <option value="all">Everyone (Public)</option>
                                <option value="seeker">Job Seekers Only</option>
                                <option value="company">Employers Only</option>
                                <option value="guest">Guests Only (Logged Out)</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label-custom">Sort Order Number</label>
                            <input type="number" name="order_num" id="edit_order_num" class="form-control-custom">
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
function fillQuickLink(title, url, icon) {
    $('#add_title').val(title);
    $('#add_url').val(url);
    $('#add_icon').val(icon);
    $('#modalAddHeaderItem').modal('show');
}

function openEditModal(item) {
    $('#edit_title').val(item.title);
    $('#edit_url').val(item.url);
    $('#edit_icon').val(item.icon || '');
    $('#edit_target').val(item.target || '_self');
    $('#edit_audience').val(item.audience || 'all');
    $('#edit_order_num').val(item.order_num);
    $('#formEditHeaderItem').attr('action', '{{ url("admin/menu-item/update") }}/' + item.id);
    $('#modalEditHeaderItem').modal('show');
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
