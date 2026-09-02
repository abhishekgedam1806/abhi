@extends('admin.layouts.admin_layout')

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- Breadcrumbs -->
        <div class="page-bar" style="margin-bottom: 20px;">
            <ul class="page-breadcrumb">
                <li><a href="{{ route('admin.home') }}">Dashboard</a><i class="fa fa-circle"></i></li>
                <li><a href="{{ route('admin.ai.providers') }}">AI Providers</a><i class="fa fa-circle"></i></li>
                <li><span>Add AI Provider</span></li>
            </ul>
        </div>

        @include('flash::message')

        <div class="portlet light bordered" style="border-radius: 10px; max-width: 850px;">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="fa fa-plus-circle font-dark"></i>
                    <span class="caption-subject bold uppercase">Add New AI Provider</span>
                </div>
                <div class="actions">
                    <a href="{{ route('admin.ai.providers') }}" class="btn btn-default btn-sm" style="border-radius: 6px;">
                        <i class="fa fa-arrow-left"></i> Back to Providers
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <form action="{{ route('admin.ai.providers.store') }}" method="POST" class="form-horizontal">
                    @csrf

                    <div class="form-body" style="padding: 20px 10px;">
                        @if ($errors->any())
                            <div class="alert alert-danger" style="border-radius: 6px;">
                                <ul style="margin: 0; padding-left: 20px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Provider Display Name -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>Provider Name <span class="text-danger">*</span></strong></label>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" placeholder="e.g. Primary Gemini Flash, Production OpenAI" value="{{ old('name') }}" required style="border-radius: 6px;">
                                <span class="help-block" style="font-size: 12px; color: #64748B;">An admin-friendly reference name for this AI configuration.</span>
                            </div>
                        </div>

                        <!-- Provider Vendor Type -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>AI Provider Engine <span class="text-danger">*</span></strong></label>
                            <div class="col-md-8">
                                <select name="provider_type" id="provider_type" class="form-control" required style="border-radius: 6px;">
                                    <option value="">-- Select AI Provider --</option>
                                    @foreach($supportedProviders as $key => $prov)
                                        <option value="{{ $key }}" {{ old('provider_type') == $key ? 'selected' : '' }}>
                                            {{ $prov['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Model Selection -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>Model <span class="text-danger">*</span></strong></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select id="model_preset" class="form-control" style="border-radius: 6px 0 0 6px;">
                                        <option value="">-- Select Recommended Model --</option>
                                    </select>
                                    <span class="input-group-addon" style="background: #F1F5F9; font-size: 11px; font-weight: 600;">Or Custom:</span>
                                    <input type="text" name="model" id="model_input" class="form-control" placeholder="e.g. gemini-1.5-flash, gpt-4o-mini" value="{{ old('model') }}" required style="border-radius: 0 6px 6px 0;">
                                </div>
                                <span class="help-block" style="font-size: 12px; color: #64748B;">Choose from preset models or type any custom model ID supported by the provider.</span>
                            </div>
                        </div>

                        <!-- API Key -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>API Key / Token <span class="text-danger">*</span></strong></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="password" name="api_key" id="api_key_input" class="form-control" placeholder="Enter API Key from Provider Console" required autocomplete="new-password" style="border-radius: 6px 0 0 6px;">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" id="toggle_key_btn" style="border-radius: 0 6px 6px 0;">
                                            <i class="fa fa-eye" id="toggle_key_icon"></i>
                                        </button>
                                    </span>
                                </div>
                                <span class="help-block" style="font-size: 12px; color: #03855c;">
                                    <i class="fa fa-shield"></i> Your API Key is encrypted with AES-256 before saving and will never be exposed.
                                </span>
                            </div>
                        </div>

                        <!-- Optional Base URL -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Base URL <span class="text-muted" style="font-size: 11px;">(Optional)</span></label>
                            <div class="col-md-8">
                                <input type="url" name="base_url" class="form-control" placeholder="https://api.openai.com/v1 (Leave blank for default vendor endpoint)" value="{{ old('base_url') }}" style="border-radius: 6px;">
                                <span class="help-block" style="font-size: 12px; color: #64748B;">Optional custom endpoint, proxy, or Azure OpenAI resource URL.</span>
                            </div>
                        </div>

                        <!-- Timeout -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Timeout (seconds)</label>
                            <div class="col-md-4">
                                <input type="number" name="timeout_sec" class="form-control" value="{{ old('timeout_sec', 30) }}" min="5" max="120" style="border-radius: 6px;">
                                <span class="help-block" style="font-size: 12px; color: #64748B;">Recommended: 30s</span>
                            </div>
                        </div>

                        <!-- Is Active -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">Enable Provider</label>
                            <div class="col-md-8">
                                <label class="checkbox-inline" style="padding-top: 5px;">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <strong>Enabled</strong> (Provider can be tested and selected)
                                </label>
                                <span class="help-block" style="font-size: 12px; color: #64748B;">
                                    <em>Note: Adding a new provider will NOT replace the active provider. You must explicitly click "Set as Active".</em>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 15px 20px; border-radius: 0 0 10px 10px;">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 6px; font-weight: 600; padding: 8px 24px;">
                                    <i class="fa fa-check"></i> Save Provider
                                </button>
                                <a href="{{ route('admin.ai.providers') }}" class="btn btn-default" style="border-radius: 6px; margin-left: 8px;">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
var supportedProviders = @json($supportedProviders);

$(document).ready(function() {
    function updateModels(providerType) {
        var $modelPreset = $('#model_preset');
        $modelPreset.empty().append('<option value="">-- Select Recommended Model --</option>');

        if (providerType && supportedProviders[providerType]) {
            var models = supportedProviders[providerType].models;
            $.each(models, function(val, label) {
                $modelPreset.append($('<option>', {
                    value: val,
                    text: label
                }));
            });

            if (!$('#model_input').val()) {
                var defaultModel = supportedProviders[providerType].default_model;
                $('#model_input').val(defaultModel);
                $modelPreset.val(defaultModel);
            }
        }
    }

    $('#provider_type').on('change', function() {
        var selected = $(this).val();
        updateModels(selected);
    });

    $('#model_preset').on('change', function() {
        var selected = $(this).val();
        if (selected) {
            $('#model_input').val(selected);
        }
    });

    $('#toggle_key_btn').on('click', function() {
        var $input = $('#api_key_input');
        var $icon = $('#toggle_key_icon');
        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $input.attr('type', 'password');
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    var currentType = $('#provider_type').val();
    if (currentType) {
        updateModels(currentType);
    }
});
</script>
@endpush
@endsection
