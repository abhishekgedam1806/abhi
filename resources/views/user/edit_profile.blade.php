@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('My Profile')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper user-dashboard-redesign" style="background:#F8FAFC; padding:32px 0 60px; min-height: 85vh;">
    <div class="container" style="max-width: 1320px;">
        <div class="row">
            @include('includes.user_dashboard_menu')

            <div class="col-lg-9 col-md-8"> 
                {{-- 1. Personal Information Card --}}
                <div class="userccount mb-4" style="background:#FFFFFF; border:1.5px solid #E2E8F0; border-radius:16px; padding:28px 24px; box-shadow:0 4px 20px rgba(0,0,0,0.02);">
                    <div class="formpanel mt0">
                        @include('flash::message') 
                        <!-- Personal Information -->
                        @include('user.inc.profile')                              
                    </div>
                </div>
                
                {{-- 2. Professional Summary Card --}}
                <div class="userccount mb-4" style="background:#FFFFFF; border:1.5px solid #E2E8F0; border-radius:16px; padding:28px 24px; box-shadow:0 4px 20px rgba(0,0,0,0.02);">
                    <div class="formpanel mt0">
                        <!-- Summary Information -->
                        @include('user.inc.summary')                                
                    </div>
                </div>
                
                {{-- 3. Professional Profile Sub-Sections Card (CVs, Projects, Experience, Education, Skills, Languages) --}}
                <div class="userccount mb-4" style="background:#FFFFFF; border:1.5px solid #E2E8F0; border-radius:16px; padding:28px 26px; box-shadow:0 4px 20px rgba(0,0,0,0.02);">
                    <div class="formpanel mt0">
                        @include('user.forms.cv.cvs')
                        @include('user.forms.project.projects')
                        @include('user.forms.experience.experience')
                        @include('user.forms.education.education')
                        @include('user.forms.skill.skills')
                        @include('user.forms.language.languages')
                    </div>
                </div>

                {{-- 4. WhatsApp Notification Preferences Card --}}
                @include('user.inc.whatsapp_preferences')
            </div>
        </div>
    </div>  
</div>

{{-- Root-Level Modals for 100% Viewport Centering --}}
<div class="modal fade" id="add_cv_modal" role="dialog" tabindex="-1" aria-hidden="true"></div>
<div class="modal fade" id="add_project_modal" role="dialog" tabindex="-1" aria-hidden="true"></div>
<div class="modal fade" id="add_experience_modal" role="dialog" tabindex="-1" aria-hidden="true"></div>
<div class="modal fade" id="add_education_modal" role="dialog" tabindex="-1" aria-hidden="true"></div>
<div class="modal fade" id="add_skill_modal" role="dialog" tabindex="-1" aria-hidden="true"></div>
<div class="modal fade" id="add_language_modal" role="dialog" tabindex="-1" aria-hidden="true"></div>

@include('includes.footer')
@endsection

@push('styles')
<style type="text/css">
    .userccount p { text-align:left !important; }

    /* ==========================================================================
       ADVANCED CANDIDATE PROFILE SUB-SECTIONS SYSTEM
       ========================================================================== */

    /* Sub-section Wrapper */
    .profile-sub-section {
        padding: 24px 0 !important;
        border-bottom: 1px solid #F1F5F9 !important;
    }
    .profile-sub-section:last-child {
        border-bottom: none !important;
        padding-bottom: 0 !important;
    }
    .profile-sub-section:first-child {
        padding-top: 0 !important;
    }

    /* Header Flex Row */
    .profile-section-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 16px !important;
        gap: 12px !important;
        flex-wrap: nowrap !important;
    }

    .profile-section-title-wrap {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        flex: 1 !important;
    }

    .profile-section-icon {
        width: 40px !important;
        height: 40px !important;
        border-radius: 10px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 17px !important;
        flex-shrink: 0 !important;
    }

    .profile-section-title {
        font-size: 16px !important;
        font-weight: 700 !important;
        color: #0F172A !important;
        margin: 0 !important;
        line-height: 1.3 !important;
    }

    .profile-section-subtitle {
        font-size: 12.5px !important;
        color: #64748B !important;
        margin: 2px 0 0 0 !important;
        line-height: 1.3 !important;
    }

    /* Elegant + Add Button (Compact Pill) */
    .profile-section-actions {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        flex-shrink: 0 !important;
    }

    .btn-add-section {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        padding: 7px 16px !important;
        background: #ECFDF5 !important;
        color: #03855c !important;
        border: 1.5px solid #A7F3D0 !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        white-space: nowrap !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 1px 2px rgba(3, 133, 92, 0.05) !important;
        flex-shrink: 0 !important;
        text-decoration: none !important;
        width: auto !important;
    }

    .btn-add-section:hover,
    .btn-add-section:focus {
        background: #03855c !important;
        color: #FFFFFF !important;
        border-color: #03855c !important;
        box-shadow: 0 4px 12px rgba(3, 133, 92, 0.25) !important;
        transform: translateY(-1px) !important;
        text-decoration: none !important;
    }

    .btn-ai-suggest {
        background: #EFF6FF !important;
        color: #1D4ED8 !important;
        border: 1.5px solid #BFDBFE !important;
        font-weight: 700 !important;
    }
    .btn-ai-suggest:hover,
    .btn-ai-suggest:focus {
        background: #2563EB !important;
        color: #FFFFFF !important;
        border-color: #2563EB !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
    }
    .btn-ai-suggest i {
        color: #2563EB !important;
        transition: color 0.15s ease !important;
    }
    .btn-ai-suggest:hover i,
    .btn-ai-suggest:focus i {
        color: #FFFFFF !important;
    }

    .btn-add-primary {
        background: #ECFDF5 !important;
        color: #03855c !important;
        border: 1.5px solid #A7F3D0 !important;
        font-weight: 700 !important;
    }

    /* Modern Minimalist Empty State Card */
    .profile-empty-state {
        background: #FAFCFF !important;
        border: 1.5px dashed #CBD5E1 !important;
        border-radius: 12px !important;
        padding: 22px 24px !important;
        text-align: center !important;
        transition: all 0.2s ease !important;
        margin: 4px 0 8px !important;
    }

    .profile-empty-state:hover {
        border-color: #94A3B8 !important;
        background: #F8FAFC !important;
    }

    .profile-empty-state-text {
        font-size: 13.5px !important;
        color: #64748B !important;
        font-weight: 500 !important;
        line-height: 1.5 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        margin: 0 !important;
    }

    /* Clean Profile Item Cards (Naukri/Indeed Style) */
    .profile-card-item {
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 12px !important;
        padding: 16px 20px !important;
        margin-bottom: 12px !important;
        transition: all 0.2s ease !important;
        position: relative !important;
    }

    .profile-card-item:hover {
        border-color: #CBD5E1 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
    }

    .profile-timeline-card {
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 12px !important;
        padding: 18px 20px !important;
        margin-bottom: 14px !important;
        transition: all 0.2s ease !important;
        position: relative !important;
    }

    .profile-timeline-card:hover {
        border-color: #CBD5E1 !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05) !important;
    }

    /* Modern Table for Resume / Skills / Languages */
    .profile-modern-table {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        margin-bottom: 8px !important;
    }

    .profile-modern-table thead th {
        background: #F8FAFC !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        padding: 12px 18px !important;
        border-bottom: 1px solid #E2E8F0 !important;
    }

    .profile-modern-table tbody tr {
        transition: background 0.15s ease !important;
    }

    .profile-modern-table tbody tr:not(:last-child) td {
        border-bottom: 1px solid #F1F5F9 !important;
    }

    .profile-modern-table tbody tr:hover {
        background: #F8FAFC !important;
    }

    .profile-modern-table td {
        padding: 14px 18px !important;
        font-size: 13.5px !important;
        color: #1E293B !important;
        vertical-align: middle !important;
    }

    /* Responsive CV Cards (Mobile & Desktop) */
    .profile-cvs-list-container {
        display: flex !important;
        flex-direction: column !important;
        gap: 12px !important;
    }
    .profile-cv-card-item {
        background: #FFFFFF !important;
        border: 1.5px solid #E2E8F0 !important;
        border-radius: 12px !important;
        padding: 14px 18px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 16px !important;
        flex-wrap: wrap !important;
        transition: all 0.2s ease !important;
    }
    .profile-cv-card-item:hover {
        border-color: #CBD5E1 !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03) !important;
    }
    .cv-card-main-info {
        display: flex !important;
        align-items: center !important;
        gap: 14px !important;
        flex: 1 !important;
        min-width: 200px !important;
    }
    .cv-card-icon {
        width: 42px !important;
        height: 42px !important;
        min-width: 42px !important;
        border-radius: 10px !important;
        background: #EFF6FF !important;
        color: #2563EB !important;
        border: 1px solid #DBEAFE !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 18px !important;
        flex-shrink: 0 !important;
    }
    .cv-card-details {
        flex: 1 !important;
        min-width: 0 !important;
    }
    .cv-card-title-link {
        font-size: 14.5px !important;
        font-weight: 700 !important;
        color: #0F172A !important;
        text-decoration: none !important;
        display: block !important;
        word-break: break-word !important;
        overflow-wrap: break-word !important;
        line-height: 1.35 !important;
        margin-bottom: 2px !important;
    }
    .cv-card-title-link:hover {
        color: #2563EB !important;
        text-decoration: underline !important;
    }
    .cv-card-subtitle {
        font-size: 12px !important;
        color: #64748B !important;
        display: flex !important;
        align-items: center !important;
        gap: 4px !important;
    }
    .cv-card-meta-actions {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        flex-wrap: wrap !important;
    }
    .badge-default-cv {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        background: #ECFDF5 !important;
        color: #047857 !important;
        border: 1px solid #A7F3D0 !important;
        border-radius: 50px !important;
        padding: 4px 10px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
    }
    .badge-additional-cv {
        display: inline-flex !important;
        align-items: center !important;
        background: #F1F5F9 !important;
        color: #475569 !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 50px !important;
        padding: 4px 10px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
    }
    @media (max-width: 767px) {
        .profile-cv-card-item {
            padding: 12px 14px !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px !important;
        }
        .cv-card-main-info {
            width: 100% !important;
        }
        .cv-card-meta-actions {
            width: 100% !important;
            justify-content: space-between !important;
            padding-top: 10px !important;
            border-top: 1px solid #F1F5F9 !important;
        }
    }

    /* Action Button Group */
    .profile-actions-wrap {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
    }

    .btn-action-edit {
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        padding: 5px 12px !important;
        background: #EFF6FF !important;
        color: #2563EB !important;
        border: 1px solid #BFDBFE !important;
        border-radius: 7px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        transition: all 0.15s ease !important;
        cursor: pointer !important;
    }
    .btn-action-edit:hover {
        background: #DBEAFE !important;
        color: #1D4ED8 !important;
        border-color: #93C5FD !important;
        text-decoration: none !important;
    }

    .btn-action-delete {
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        padding: 5px 12px !important;
        background: #FEF2F2 !important;
        color: #DC2626 !important;
        border: 1px solid #FECACA !important;
        border-radius: 7px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        transition: all 0.15s ease !important;
        cursor: pointer !important;
    }
    .btn-action-delete:hover {
        background: #FEE2E2 !important;
        color: #B91C1C !important;
        border-color: #FCA5A5 !important;
        text-decoration: none !important;
    }

    /* Skill & Language Badges */
    .profile-badge-pill {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        background: #F1F5F9 !important;
        color: #334155 !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 50px !important;
        padding: 6px 14px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        margin: 4px 4px 4px 0 !important;
    }

    .profile-badge-skill {
        background: #EFF6FF !important;
        color: #1E40AF !important;
        border-color: #BFDBFE !important;
    }

    .profile-badge-lang {
        background: #F5F3FF !important;
        color: #5B21B6 !important;
        border-color: #DDD6FE !important;
    }

    /* ==========================================================================
       MODERN PROFILE PHOTO CARD & AVATAR UPLOADER
       ========================================================================== */
    .profile-photo-card {
        display: flex !important;
        align-items: center !important;
        gap: 24px !important;
        background: #F8FAFC !important;
        border: 1.5px solid #E2E8F0 !important;
        border-radius: 14px !important;
        padding: 20px 24px !important;
        margin-bottom: 24px !important;
    }

    .photo-avatar-container {
        position: relative !important;
        width: 100px !important;
        height: 100px !important;
        flex-shrink: 0 !important;
    }

    .profile-photo-img {
        width: 100px !important;
        height: 100px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
        border: 3px solid #FFFFFF !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        background: #E2E8F0 !important;
    }

    .photo-cam-badge {
        position: absolute !important;
        bottom: 0 !important;
        right: 0 !important;
        background: #03855c !important;
        color: #FFFFFF !important;
        width: 32px !important;
        height: 32px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 2.5px solid #FFFFFF !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15) !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        margin: 0 !important;
    }

    .photo-cam-badge:hover {
        background: #047857 !important;
        transform: scale(1.1) !important;
    }

    .photo-info-container {
        flex: 1 !important;
    }

    .photo-card-title {
        font-size: 16px !important;
        font-weight: 800 !important;
        color: #0F172A !important;
        margin: 0 0 4px 0 !important;
    }

    .photo-card-desc {
        font-size: 13px !important;
        color: #64748B !important;
        margin: 0 0 12px 0 !important;
        line-height: 1.4 !important;
    }

    .btn-choose-photo {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        background: #0F172A !important;
        color: #FFFFFF !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        padding: 8px 18px !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        margin: 0 !important;
        border: none !important;
    }

    .btn-choose-photo:hover {
        background: #1E293B !important;
        color: #FFFFFF !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2) !important;
    }

    .photo-upload-indicator {
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #03855c !important;
        margin-left: 12px !important;
    }

    @media (max-width: 576px) {
        .profile-photo-card {
            flex-direction: column !important;
            text-align: center !important;
            padding: 18px !important;
        }
        .btn-choose-photo {
            width: 100% !important;
            justify-content: center !important;
        }
    }

    /* ==========================================================================
       MODAL & POPUP BULLETPROOF VIEWPORT CENTERING & DESIGN SYSTEM
       ========================================================================== */
    .modal-backdrop {
        background-color: #0F172A !important;
        opacity: 0.65 !important;
    }

    .modal-dialog-centered {
        display: flex !important;
        align-items: center !important;
        min-height: calc(100% - 3.5rem) !important;
        margin: 1.75rem auto !important;
    }

    .modal-dialog {
        position: relative !important;
        width: 100% !important;
        max-width: 620px !important;
        margin: 1.75rem auto !important;
        pointer-events: auto !important;
    }

    .modal-dialog.modal-lg {
        max-width: 740px !important;
    }

    .custom-profile-modal {
        width: 100% !important;
        border-radius: 16px !important;
        border: 1px solid #E2E8F0 !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35) !important;
        overflow: hidden !important;
        background: #FFFFFF !important;
    }

    .custom-profile-modal .modal-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 18px 24px !important;
        border-bottom: 1px solid #F1F5F9 !important;
        background: #FFFFFF !important;
        position: relative !important;
    }

    .custom-profile-modal .modal-title {
        font-size: 18px !important;
        font-weight: 800 !important;
        color: #0F172A !important;
        margin: 0 !important;
        line-height: 1.3 !important;
    }

    .btn-modal-close {
        background: #F1F5F9 !important;
        border: 1px solid #E2E8F0 !important;
        font-size: 22px !important;
        line-height: 1 !important;
        color: #64748B !important;
        cursor: pointer !important;
        width: 34px !important;
        height: 34px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.2s ease !important;
        margin: 0 !important;
        padding: 0 !important;
        flex-shrink: 0 !important;
        position: relative !important;
        z-index: 100 !important;
        pointer-events: auto !important;
    }

    .btn-modal-close:hover {
        background: #E2E8F0 !important;
        color: #0F172A !important;
        transform: scale(1.08) !important;
    }

    .custom-profile-modal .modal-body {
        padding: 22px 24px !important;
    }

    .custom-profile-modal .modal-footer {
        padding: 14px 24px !important;
        border-top: 1px solid #F1F5F9 !important;
        background: #FFFFFF !important;
        display: flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
        gap: 12px !important;
    }

    .custom-profile-modal .form-control,
    .custom-profile-modal select.form-control,
    .custom-profile-modal input[type="text"].form-control,
    .custom-profile-modal textarea.form-control {
        border: 1.5px solid #CBD5E1 !important;
        border-radius: 8px !important;
        padding: 10px 14px !important;
        font-size: 14px !important;
        color: #0F172A !important;
        height: auto !important;
        background: #FFFFFF !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
        margin-bottom: 4px !important;
    }

    .custom-profile-modal .form-control:focus {
        border-color: #03855c !important;
        box-shadow: 0 0 0 3px rgba(3, 133, 92, 0.12) !important;
    }

    .custom-profile-modal label {
        font-size: 13.5px !important;
        font-weight: 700 !important;
        color: #334155 !important;
        margin-bottom: 6px !important;
        display: block !important;
    }

    .btn-modal-save {
        background: #03855c !important;
        color: #FFFFFF !important;
        font-weight: 700 !important;
        font-size: 14.5px !important;
        border-radius: 8px !important;
        padding: 10px 24px !important;
        border: none !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        box-shadow: 0 4px 12px rgba(3, 133, 92, 0.25) !important;
        transition: all 0.15s ease !important;
        min-width: 140px !important;
    }

    .btn-modal-save:hover,
    .btn-modal-save:focus {
        background: #047857 !important;
        color: #FFFFFF !important;
        box-shadow: 0 6px 16px rgba(3, 133, 92, 0.35) !important;
        transform: translateY(-1px) !important;
    }

    .btn-modal-cancel {
        background: #F1F5F9 !important;
        color: #475569 !important;
        border: 1.5px solid #CBD5E1 !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        border-radius: 8px !important;
        padding: 10px 22px !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .btn-modal-cancel:hover,
    .btn-modal-cancel:focus {
        background: #E2E8F0 !important;
        color: #0F172A !important;
        border-color: #94A3B8 !important;
        text-decoration: none !important;
    }

    /* Responsive tweaks */
    @media (max-width: 767px) {
        .profile-section-header {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 12px !important;
        }
        .profile-section-title-wrap {
            width: 100% !important;
        }
        .profile-section-actions {
            width: 100% !important;
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 8px !important;
        }
        .profile-section-actions .btn-add-section {
            width: 100% !important;
            justify-content: center !important;
            padding: 8px 10px !important;
            font-size: 12.5px !important;
            white-space: nowrap !important;
        }
        .profile-section-header > .btn-add-section {
            width: 100% !important;
            justify-content: center !important;
        }
    }

    @media (max-width: 420px) {
        .profile-section-actions {
            grid-template-columns: 1fr !important;
        }
    }

    /* Sidebar Navigation Styles */
    .sidebar-nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        border-radius: 10px;
        color: #475569;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .sidebar-nav-link:hover {
        background: #F1F5F9;
        color: #0F172A;
        text-decoration: none;
    }
    .nav-item-active .sidebar-nav-link,
    .sidebar-nav-list .nav-item-active > a {
        background: #2563EB !important;
        color: #FFFFFF !important;
        font-weight: 700 !important;
    }
    .nav-item-active .sidebar-nav-link i,
    .sidebar-nav-list .nav-item-active > a i {
        color: #FFFFFF !important;
    }
    .modern-form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #CBD5E1;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        transition: all 0.15s ease;
    }
    .modern-form-control:focus {
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
    }
    @media (max-width: 991px) {
        .dashboard-menu-collapsible {
            display: none;
            padding-top: 10px;
        }
        .dashboard-menu-collapsible.show {
            display: block !important;
        }
        .dashboard-sidebar-card {
            padding: 10px !important;
            margin-bottom: 16px !important;
        }
    }
    @media (min-width: 992px) {
        .mobile-sidebar-toggle-btn {
            display: none !important;
        }
        .dashboard-menu-collapsible {
            display: block !important;
        }
    }
</style>
@endpush

@push('scripts')
@include('includes.immediate_available_btn')
<script type="text/javascript">
    function toggleMobileDashboardMenu() {
        var menu = document.getElementById('mobileDashboardMenuContent');
        var caret = document.getElementById('mobileMenuCaret');
        if (menu) {
            if (menu.classList.contains('show')) {
                menu.classList.remove('show');
                if (caret) caret.style.transform = 'rotate(0deg)';
            } else {
                menu.classList.add('show');
                if (caret) caret.style.transform = 'rotate(180deg)';
            }
        }
    }



    window.openProfileModal = function(modalSelector) {
        var $modal = $(modalSelector);
        
        // Remove any old lingering backdrops
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css({'padding-right': '', 'overflow': ''});

        // Cleanly reset any old Bootstrap modal instance
        try {
            $modal.modal('dispose');
        } catch (e) {}
        $modal.removeData('bs.modal');

        // Ensure modal element is visible to flex layout and initialize
        $modal.css('display', 'block').modal({
            backdrop: 'static',
            keyboard: true,
            show: true
        });
    };

    window.closeActiveModal = function() {
        $('#add_cv_modal, #add_project_modal, #add_experience_modal, #add_education_modal, #add_skill_modal, #add_language_modal, .modal').each(function() {
            var $m = $(this);
            try {
                $m.modal('hide');
                $m.modal('dispose');
            } catch (err) {}
            $m.removeData('bs.modal');
            $m.removeClass('show in').attr('aria-hidden', 'true').css('display', 'none');
        });

        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css({'padding-right': '', 'overflow': ''});
    };

    // Global click listener for cross (x), cancel, close buttons
    $(document).on('click', '.btn-modal-close, [data-dismiss="modal"], .modal .close, .btn-modal-cancel', function(e) {
        closeActiveModal();
    });

    // Close when clicking modal backdrop outside dialog
    $(document).on('click', '.modal', function(e) {
        if ($(e.target).hasClass('modal') || $(e.target).hasClass('modal-dialog-centered')) {
            closeActiveModal();
        }
    });

    // Close on Escape key press
    $(document).on('keydown', function(e) {
        if (e.key === "Escape" || e.keyCode === 27) {
            closeActiveModal();
        }
    });

    // Safely cleanup backdrop on modal hidden without breaking subsequent opens
    $(document).on('hidden.bs.modal', '.modal', function () {
        $('body').removeClass('modal-open').css({'padding-right': '', 'overflow': ''});
        $('.modal-backdrop').remove();
    });

    // Smooth scroll and highlight anchored section (e.g. #cvs for Manage Resume)
    function scrollToHash() {
        var hash = window.location.hash;
        if (hash) {
            var target = $(hash);
            if (target.length) {
                setTimeout(function() {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 110
                    }, 500);
                    target.css('transition', 'background-color 0.4s ease, box-shadow 0.4s ease')
                          .css('background-color', '#EFF6FF')
                          .css('box-shadow', '0 0 0 2px #93C5FD')
                          .css('border-radius', '12px');
                    setTimeout(function() {
                        target.css('background-color', 'transparent')
                              .css('box-shadow', 'none');
                    }, 2000);
                }, 350);
            }
        }
    }

    $(document).ready(function() {
        scrollToHash();
    });

    $(window).on('hashchange', function() {
        scrollToHash();
    });
</script>
@endpush