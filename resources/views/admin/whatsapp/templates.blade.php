@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content" style="background-color: #F8FAFC; min-height: 100vh; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
        <div class="wa-dashboard-container" style="max-width: 1400px; margin: 0 auto; padding-bottom: 50px;">
            
            <!-- Breadcrumb & Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #64748B; margin-bottom: 4px;">
                        <a href="{{ route('admin.home') }}" style="color: #64748B; text-decoration: none;">Dashboard</a>
                        <span>/</span>
                        <a href="{{ route('admin.whatsapp.index') }}" style="color: #64748B; text-decoration: none;">WhatsApp Desk</a>
                        <span>/</span>
                        <span style="color: #0F172A; font-weight: 600;">Templates</span>
                    </div>
                    <h1 style="font-size: 24px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.5px;">
                        Pre-Approved Notification Templates
                    </h1>
                </div>

                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('admin.whatsapp.index') }}" class="btn btn-default" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 10px; font-weight: 700; color: #334155; padding: 9px 18px;">
                        <i class="fa fa-arrow-left" style="margin-right: 6px;"></i> Back to Overview
                    </a>
                </div>
            </div>

            @include('flash::message')

            <!-- Info Bar -->
            <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 20px 24px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #F5F3FF; display: flex; align-items: center; justify-content: center; color: #7C3AED;">
                        <i class="fa fa-file-text-o" style="font-size: 18px;"></i>
                    </div>
                    <div>
                        <div style="font-size: 15px; font-weight: 800; color: #0F172A;">
                            Standard Transactional Template Registry ({{ $templates->count() }})
                        </div>
                        <div style="font-size: 12.5px; color: #64748B;">
                            Pre-approved for utility alerts outside the 24h conversation window with dynamic variable injection.
                        </div>
                    </div>
                </div>
                <div>
                    <span style="background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 20px;">
                        ₹0 Gemini AI Cost
                    </span>
                </div>
            </div>

            <!-- Templates Grid -->
            <div class="row">
                @foreach($templates as $tmpl)
                    <div class="col-lg-6 col-md-12" style="margin-bottom: 25px;">
                        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <!-- Header -->
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px;">
                                    <div>
                                        <h4 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0;">
                                            {{ $tmpl->title }}
                                        </h4>
                                        <code style="font-size: 11.5px; color: #4F46E5; background: #EEF2FF; border: 1px solid #E0E7FF; padding: 2px 8px; border-radius: 6px; font-weight: 600;">
                                            {{ $tmpl->template_key }}
                                        </code>
                                    </div>
                                    <div style="display: flex; gap: 6px; align-items: center;">
                                        <span style="background: #F8FAFC; color: #475569; border: 1px solid #E2E8F0; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 12px; text-transform: uppercase;">
                                            {{ $tmpl->category }}
                                        </span>
                                        @if($tmpl->is_active)
                                            <span style="background: #ECFDF5; color: #065F46; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">
                                                Active
                                            </span>
                                        @else
                                            <span style="background: #F1F5F9; color: #64748B; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">
                                                Disabled
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- WhatsApp Realistic Chat Bubble -->
                                <div style="background: #E5DDD5; border-radius: 12px; padding: 16px; margin-bottom: 16px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);">
                                    <div style="background: #FFFFFF; border-radius: 8px 8px 8px 2px; padding: 14px; box-shadow: 0 1px 2px rgba(0,0,0,0.12); font-size: 13px; color: #111827; line-height: 1.55; white-space: pre-wrap; word-break: break-word;">
@if(!empty($tmpl->header_text))<b>{{ $tmpl->header_text }}</b>

@endif{{ $tmpl->body_text }}
@if(!empty($tmpl->footer_text))
<span style="font-size: 11px; color: #6B7280; display: block; margin-top: 6px;">{{ $tmpl->footer_text }}</span>@endif
                                        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 4px; margin-top: 4px; font-size: 11px; color: #9CA3AF;">
                                            <span>10:30 AM</span>
                                            <span style="color: #38BDF8; font-weight: 800;">&#10003;&#10003;</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Variable Pills -->
                                <div style="margin-bottom: 16px;">
                                    <span style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase;">Variables:</span>
                                    <div style="display: flex; gap: 5px; flex-wrap: wrap; margin-top: 5px;">
                                        @if(!empty($tmpl->variables_schema))
                                            @foreach($tmpl->variables_schema as $v)
                                                <span style="background: #F1F5F9; color: #334155; font-size: 11.5px; font-weight: 600; padding: 2px 8px; border-radius: 6px; border: 1px solid #E2E8F0;">
                                                    @{{ {{ $v }} }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span style="font-size: 11.5px; color: #94A3B8;">None</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Action -->
                            <div style="border-top: 1.5px solid #F1F5F9; padding-top: 14px; text-align: right;">
                                <button type="button" class="btn btn-default btn-edit-template" 
                                        data-id="{{ $tmpl->id }}"
                                        data-title="{{ $tmpl->title }}"
                                        data-provider-name="{{ $tmpl->provider_template_name }}"
                                        data-header="{{ $tmpl->header_text }}"
                                        data-body="{{ $tmpl->body_text }}"
                                        data-footer="{{ $tmpl->footer_text }}"
                                        data-active="{{ $tmpl->is_active ? '1' : '0' }}"
                                        style="background: #FFFFFF; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 700; font-size: 12.5px; color: #334155; padding: 6px 14px;">
                                    <i class="fa fa-pencil" style="margin-right: 4px;"></i> Edit Template Content
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

<!-- Edit Template Modal -->
<div class="modal fade" id="modalEditTemplate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 18px; border: none; box-shadow: 0 15px 50px rgba(0,0,0,0.15); overflow: hidden;">
            <form id="formEditTemplate" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header" style="background: #0F172A; color: #FFFFFF; padding: 20px 24px; border: none;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #FFF; opacity: 0.8; font-size: 24px;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 800; font-size: 18px; margin: 0;">
                        <i class="fa fa-pencil-square-o"></i> Edit Template Definition
                    </h4>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">Display Title</label>
                        <input type="text" name="title" id="editTmplTitle" class="form-control" required style="border: 1.5px solid #CBD5E1; border-radius: 8px; height: 42px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">Provider Registered Name (Meta/Gupshup)</label>
                        <input type="text" name="provider_template_name" id="editTmplProviderName" class="form-control" style="border: 1.5px solid #CBD5E1; border-radius: 8px; height: 42px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">Header Text (Optional)</label>
                        <input type="text" name="header_text" id="editTmplHeader" class="form-control" style="border: 1.5px solid #CBD5E1; border-radius: 8px; height: 42px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">Message Body Text <span class="text-danger">*</span></label>
                        <textarea name="body_text" id="editTmplBody" rows="6" class="form-control" required style="font-family: monospace; font-size: 13px; border: 1.5px solid #CBD5E1; border-radius: 8px;"></textarea>
                        <span class="help-block" style="font-size: 11.5px; color: #64748B;">Keep placeholders formatted as <code>@{{variable_name}}</code>.</span>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">Footer Text (Optional)</label>
                        <input type="text" name="footer_text" id="editTmplFooter" class="form-control" style="border: 1.5px solid #CBD5E1; border-radius: 8px; height: 42px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 13.5px; color: #334155; cursor: pointer;">
                            <input type="checkbox" name="is_active" id="editTmplActive" value="1" style="width: 18px; height: 18px;">
                            Template Active & Ready for Dispatches
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="background: #F8FAFC; border-top: 1.5px solid #E2E8F0; padding: 16px 24px; display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 700;">Cancel</button>
                    <button type="submit" class="btn" style="background: #059669; color: #FFFFFF; font-weight: 700; border-radius: 8px; padding: 9px 22px;">
                        <i class="fa fa-save" style="margin-right: 4px;"></i> Update Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-edit-template').on('click', function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var providerName = $(this).data('provider-name');
        var header = $(this).data('header');
        var body = $(this).data('body');
        var footer = $(this).data('footer');
        var active = $(this).data('active');

        var actionUrl = "{{ url('admin/whatsapp/templates') }}/" + id + "/update";
        $('#formEditTemplate').attr('action', actionUrl);

        $('#editTmplTitle').val(title);
        $('#editTmplProviderName').val(providerName);
        $('#editTmplHeader').val(header);
        $('#editTmplBody').val(body);
        $('#editTmplFooter').val(footer);
        $('#editTmplActive').prop('checked', active == '1');

        $('#modalEditTemplate').modal('show');
    });
});
</script>
@endpush
@endsection
