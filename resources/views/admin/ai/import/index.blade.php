@extends('admin.layouts.admin_layout')

@section('content')
<style>
    /* SaaS AI Import Tokens */
    :root {
        --import-primary: #2563EB;
        --import-primary-hover: #1D4ED8;
        --import-primary-light: #EFF6FF;
        --import-success: #03855c;
        --import-success-light: #ECFDF5;
        --import-warning: #D97706;
        --import-warning-light: #FFFBEB;
        --import-danger: #DC2626;
        --import-danger-light: #FEF2F2;
        --import-border: #E2E8F0;
        --import-card-bg: #FFFFFF;
        --import-text-main: #0F172A;
        --import-text-muted: #64748B;
    }

    .ai-import-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: var(--import-text-main);
    }

    /* Cards */
    .import-card {
        background: var(--import-card-bg);
        border: 1px solid var(--import-border);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .import-card-header {
        padding: 16px 20px;
        background: #FFFFFF;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .import-card-body {
        padding: 20px;
    }

    /* Input Tabs */
    .import-nav-tabs {
        display: flex;
        border-bottom: 1px solid var(--import-border);
        background: #F8FAFC;
        padding: 4px 12px 0 12px;
        gap: 8px;
        list-style: none;
        margin: 0;
    }
    .import-nav-tabs li a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        font-weight: 700;
        font-size: 13px;
        color: var(--import-text-muted);
        text-decoration: none;
        border-radius: 8px 8px 0 0;
        border: 1px solid transparent;
        border-bottom: none;
        transition: all 0.15s;
    }
    .import-nav-tabs li.active a {
        background: #FFFFFF;
        color: var(--import-primary);
        border-color: var(--import-border);
        border-bottom-color: #FFFFFF;
        margin-bottom: -1px;
    }
    .import-tab-number {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #E2E8F0;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
    }
    .import-nav-tabs li.active .import-tab-number {
        background: var(--import-primary);
        color: #FFFFFF;
    }

    /* Dropzone Upload Area */
    .image-dropzone {
        border: 2px dashed #CBD5E1;
        border-radius: 12px;
        padding: 32px 20px;
        text-align: center;
        background: #F8FAFC;
        cursor: pointer;
        transition: all 0.2s;
    }
    .image-dropzone:hover, .image-dropzone.dragover {
        border-color: var(--import-primary);
        background: var(--import-primary-light);
    }
    .image-preview-box {
        border: 1px solid var(--import-border);
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        background: #F8FAFC;
        margin-top: 14px;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .image-preview-box img {
        max-height: 200px;
        max-width: 100%;
        border-radius: 6px;
        object-fit: contain;
    }

    /* Extraction Metrics Stats — compact inline pill style */
    .metrics-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }
    .metric-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border-radius: 20px;
        padding: 5px 12px 5px 8px;
        border: 1px solid transparent;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        line-height: 1;
    }
    .metric-pill .pill-icon {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 800;
        flex-shrink: 0;
    }
    .metric-pill .pill-count {
        font-size: 15px;
        font-weight: 800;
    }
    .metric-pill .pill-label {
        font-size: 11px;
        font-weight: 600;
        opacity: 0.85;
    }
    .pill-green { background: #ECFDF5; border-color: #A7F3D0; color: #065F46; }
    .pill-green .pill-icon { background: #03855c; color: #fff; }
    .pill-amber { background: #FFFBEB; border-color: #FDE68A; color: #92400E; }
    .pill-amber .pill-icon { background: #D97706; color: #fff; }
    .pill-rose  { background: #FEF2F2; border-color: #FECACA; color: #991B1B; }
    .pill-rose  .pill-icon { background: #DC2626; color: #fff; }

    /* Form Fields & Confidence Badges */
    .field-row {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        gap: 10px;
    }
    .field-label {
        width: 140px;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        margin: 0;
        flex-shrink: 0;
    }
    .field-input-wrap {
        flex: 1;
        position: relative;
    }
    .field-input-wrap .form-control {
        border-radius: 6px;
        height: 36px;
        font-size: 12px;
        border: 1.5px solid var(--import-border);
        color: #0F172A;
    }
    .field-input-wrap textarea.form-control {
        height: auto;
    }
    .conf-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 12px;
        width: 65px;
        text-align: center;
        flex-shrink: 0;
    }
    .conf-high   { background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }
    .conf-review { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
    .conf-none   { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }

    /* Skills Tag Chips */
    .skill-input-container {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        padding: 6px 8px;
        border: 1.5px solid var(--import-border);
        border-radius: 6px;
        background: #FFFFFF;
        min-height: 36px;
        align-items: center;
    }
    .skill-tag-chip {
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #DBEAFE;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .skill-tag-chip .remove-skill-btn {
        cursor: pointer;
        color: #93C5FD;
        font-weight: bold;
    }
    .skill-tag-chip .remove-skill-btn:hover {
        color: #1D4ED8;
    }
    .skill-tag-input {
        border: none;
        outline: none;
        font-size: 12px;
        flex: 1;
        min-width: 80px;
        background: transparent;
    }

    /* Notes Box */
    .notes-box {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 12px;
        color: #78350F;
    }
    .notes-box ul {
        margin: 6px 0 0 0;
        padding-left: 18px;
    }
    .notes-box li {
        margin-bottom: 4px;
    }
</style>

<div class="page-content-wrapper ai-import-wrap">
    <div class="page-content" style="background: #F8FAFC; min-height: 100vh;">
        
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0; letter-spacing: -0.5px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-magic" style="color: #2563EB;"></i> AI Job Import
                </h2>
                <div style="font-size: 13px; color: #64748B;">
                    Paste a job posting or upload an image. AI will extract the information and fill the existing job form for review.
                </div>
            </div>
            <div>
                <a href="{{ route('admin.ai.pipeline') }}" class="btn btn-default" style="border-radius: 8px; font-weight: 600; background: #FFFFFF; border: 1px solid #CBD5E1; color: #334155;">
                    <i class="fa fa-arrow-left" style="margin-right: 4px;"></i> Back to AI Pipeline
                </a>
            </div>
        </div>

        <div class="row">
            <!-- LEFT COLUMN: INPUT METHODS -->
            <div class="col-lg-5 col-md-5">
                <div class="import-card">
                    <!-- Tab Header -->
                    <ul class="import-nav-tabs">
                        <li class="active" id="tab_btn_text">
                            <a href="javascript:void(0);" onclick="switchTab('text')">
                                <span class="import-tab-number">1</span> Paste Job Content
                            </a>
                        </li>
                        <li id="tab_btn_image">
                            <a href="javascript:void(0);" onclick="switchTab('image')">
                                <span class="import-tab-number">2</span> Upload Job Image
                            </a>
                        </li>
                    </ul>

                    <div class="import-card-body">
                        <!-- TAB 1: PASTE CONTENT -->
                        <div id="tab_content_text">
                            <div style="margin-bottom: 12px;">
                                <h4 style="font-weight: 800; font-size: 14px; color: #0F172A; margin: 0 0 4px 0;">Paste Raw Job Content</h4>
                                <p style="font-size: 12px; color: #64748B; margin: 0;">
                                    Paste the complete job posting content here. AI will analyze and extract the details.
                                </p>
                            </div>

                            <div class="form-group" style="margin-bottom: 8px;">
                                <textarea id="raw_job_text" class="form-control" rows="12" style="border-radius: 8px; font-size: 12px; border: 1.5px solid #CBD5E1; resize: vertical;" placeholder="Paste the complete job posting here...

Example:
WE'RE HIRING - OFF-PAGE SEO SPECIALIST (REMOTE)
WK Tech is looking for a skilled Off-Page SEO Specialist with 3+ years experience.
Skills: Ahrefs, Semrush, Moz, Link Building.
Email: example@company.com" oninput="updateCharCount(this)"></textarea>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                                <span style="font-size: 11px; color: #94A3B8;" id="char_counter">0 / 10000</span>
                            </div>

                            <div class="alert alert-info" style="border-radius: 8px; font-size: 11px; padding: 10px 12px; background: #EFF6FF; border-color: #BFDBFE; color: #1E40AF; margin-bottom: 16px;">
                                <i class="fa fa-info-circle"></i> <strong>Tip:</strong> For best results, include job title, company name, location, requirements, responsibilities, skills, experience, salary (if any), and contact details.
                            </div>

                            <button type="button" id="btn_extract_text" class="btn btn-primary btn-block" onclick="runExtraction('text')" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; font-weight: 700; height: 42px; font-size: 13px; box-shadow: 0 2px 5px rgba(37,99,235,0.25);">
                                <i class="fa fa-magic"></i> Extract & Fill Job Form
                            </button>
                        </div>

                        <!-- TAB 2: UPLOAD IMAGE -->
                        <div id="tab_content_image" style="display: none;">
                            <div style="margin-bottom: 12px;">
                                <h4 style="font-weight: 800; font-size: 14px; color: #0F172A; margin: 0 0 4px 0;">Upload Job Image</h4>
                                <p style="font-size: 12px; color: #64748B; margin: 0;">
                                    Upload a job poster, flyer, screenshot or any job posting image.
                                </p>
                            </div>

                            <input type="file" id="job_image_file" accept="image/jpeg,image/png,image/webp,image/jpg" style="display: none;" onchange="handleImageSelect(this)">

                            <div class="image-dropzone" id="image_dropzone" onclick="document.getElementById('job_image_file').click()">
                                <div style="font-size: 32px; color: #64748B; margin-bottom: 8px;">
                                    <i class="fa fa-cloud-upload"></i>
                                </div>
                                <div style="font-weight: 700; font-size: 13px; color: #0F172A;">
                                    Drag & drop an image here or <span style="color: #2563EB;">Choose Image</span>
                                </div>
                                <div style="font-size: 11px; color: #94A3B8; margin-top: 4px;">
                                    Supports: JPG, JPEG, PNG, WEBP &nbsp;|&nbsp; Max size: 5MB
                                </div>
                            </div>

                            <div style="margin-top: 14px;">
                                <label style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px; display: block;">Uploaded Image Preview</label>
                                <div class="image-preview-box" id="image_preview_container">
                                    <div id="image_preview_placeholder" style="color: #94A3B8; font-size: 12px;">
                                        <i class="fa fa-picture-o" style="font-size: 28px; display: block; margin-bottom: 4px;"></i>
                                        No image selected
                                    </div>
                                    <img id="image_preview_img" src="#" alt="Job Poster Preview" style="display: none;">
                                </div>
                                <button type="button" id="btn_remove_image" class="btn btn-xs btn-default btn-block" onclick="removeSelectedImage()" style="margin-top: 6px; border-radius: 6px; color: #DC2626; border-color: #FECACA; display: none;">
                                    <i class="fa fa-trash"></i> Remove Image
                                </button>
                            </div>

                            <button type="button" id="btn_extract_image" class="btn btn-primary btn-block" onclick="runExtraction('image')" style="margin-top: 16px; background: #2563EB; border-color: #2563EB; border-radius: 8px; font-weight: 700; height: 42px; font-size: 13px; box-shadow: 0 2px 5px rgba(37,99,235,0.25);">
                                <i class="fa fa-bolt"></i> Extract From Image
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Important Notes Box -->
                <div class="notes-box">
                    <strong><i class="fa fa-info-circle"></i> Important Notes</strong>
                    <ul>
                        <li>AI extracts visible information strictly from the content/image you provide.</li>
                        <li>Please review all fields carefully before saving or publishing.</li>
                        <li>Missing information is marked as <em>"Not specified"</em> and can be edited manually.</li>
                        <li>The final decision and accuracy are always the responsibility of the administrator.</li>
                    </ul>
                </div>
            </div>

            <!-- RIGHT COLUMN: AI EXTRACTION RESULTS & REVIEW FORM -->
            <div class="col-lg-7 col-md-7">
                <div class="import-card">
                    <!-- Extraction Summary Header -->
                    <div class="import-card-header">
                        <div>
                            <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0 0 2px 0;">
                                AI Extraction Results
                            </h3>
                            <div style="font-size: 12px; color: #64748B;">
                                Please review and edit the extracted information before saving.
                            </div>
                        </div>
                        <div id="extraction_status_badge">
                            <span style="background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                                Awaiting Input
                            </span>
                        </div>
                    </div>

                    <div class="import-card-body">
                        <!-- 3 Metric Summary Pills -->
                        <div class="metrics-row">
                            <div class="metric-pill pill-green">
                                <span class="pill-icon"><i class="fa fa-check"></i></span>
                                <span class="pill-count" id="stat_extracted_count">0</span>
                                <span class="pill-label">Fields Extracted</span>
                            </div>
                            <div class="metric-pill pill-amber">
                                <span class="pill-icon"><i class="fa fa-exclamation"></i></span>
                                <span class="pill-count" id="stat_review_count">0</span>
                                <span class="pill-label">Need Review</span>
                            </div>
                            <div class="metric-pill pill-rose">
                                <span class="pill-icon"><i class="fa fa-times"></i></span>
                                <span class="pill-count" id="stat_not_found_count">0</span>
                                <span class="pill-label">Not Found</span>
                            </div>
                        </div>

                        <!-- Interactive Form Fields with Confidence Badges -->
                        <form id="aiJobForm">
                            @csrf

                            <!-- 1. Job Title -->
                            <div class="field-row">
                                <label class="field-label">Job Title <span class="text-danger">*</span></label>
                                <div class="field-input-wrap">
                                    <input type="text" name="title" id="form_title" class="form-control" placeholder="e.g. Off-Page SEO Specialist" required>
                                </div>
                                <span class="conf-badge conf-none" id="badge_title">—</span>
                            </div>

                            <!-- 2. Company Name -->
                            <div class="field-row">
                                <label class="field-label">Company Name <span class="text-danger">*</span></label>
                                <div class="field-input-wrap">
                                    <input type="text" name="company_name" id="form_company_name" class="form-control" placeholder="e.g. WK Tech" required>
                                </div>
                                <span class="conf-badge conf-none" id="badge_company_name">—</span>
                            </div>

                            <!-- 3. Category / Functional Area -->
                            <div class="field-row">
                                <label class="field-label">Category <span class="text-danger">*</span></label>
                                <div class="field-input-wrap">
                                    <select name="functional_area_id" id="form_functional_area_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach($functionalAreas as $faId => $faName)
                                            <option value="{{ $faId }}">{{ $faName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="conf-badge conf-none" id="badge_category">—</span>
                            </div>

                            <!-- 4. Location / City -->
                            <div class="field-row">
                                <label class="field-label">Location <span class="text-danger">*</span></label>
                                <div class="field-input-wrap" style="display: flex; gap: 6px;">
                                    <input type="text" id="form_location_display" class="form-control" placeholder="e.g. Remote / Online" style="flex: 2;">
                                    <select name="country_id" id="form_country_id" class="form-control" style="flex: 1;">
                                        @foreach($countries as $cId => $cName)
                                            <option value="{{ $cId }}" {{ $cId == 101 ? 'selected' : '' }}>{{ $cName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="conf-badge conf-none" id="badge_location">—</span>
                            </div>

                            <!-- 5. Job Type -->
                            <div class="field-row">
                                <label class="field-label">Job Type</label>
                                <div class="field-input-wrap">
                                    <select name="job_type_id" id="form_job_type_id" class="form-control">
                                        <option value="">Not specified</option>
                                        @foreach($jobTypes as $jtId => $jtName)
                                            <option value="{{ $jtId }}">{{ $jtName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="conf-badge conf-none" id="badge_job_type">—</span>
                            </div>

                            <!-- 6. Work Mode -->
                            <div class="field-row">
                                <label class="field-label">Work Mode</label>
                                <div class="field-input-wrap">
                                    <select name="is_freelance" id="form_is_freelance" class="form-control">
                                        <option value="0">On-site / Office</option>
                                        <option value="1">Remote / Online / Freelance</option>
                                    </select>
                                </div>
                                <span class="conf-badge conf-none" id="badge_work_mode">—</span>
                            </div>

                            <!-- 7. Experience Required -->
                            <div class="field-row">
                                <label class="field-label">Experience</label>
                                <div class="field-input-wrap">
                                    <select name="job_experience_id" id="form_job_experience_id" class="form-control">
                                        <option value="">Not specified</option>
                                        @foreach($jobExperiences as $expId => $expName)
                                            <option value="{{ $expId }}">{{ $expName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="conf-badge conf-none" id="badge_experience">—</span>
                            </div>

                            <!-- 8. Salary -->
                            <div class="field-row">
                                <label class="field-label">Salary</label>
                                <div class="field-input-wrap" style="display: flex; gap: 6px; align-items: center;">
                                    <input type="number" name="salary_from" id="form_salary_from" class="form-control" placeholder="Min" style="flex: 1;">
                                    <span>-</span>
                                    <input type="number" name="salary_to" id="form_salary_to" class="form-control" placeholder="Max" style="flex: 1;">
                                    <select name="salary_currency" id="form_salary_currency" class="form-control" style="width: 75px;">
                                        <option value="INR" selected>INR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                                <span class="conf-badge conf-none" id="badge_salary">—</span>
                            </div>

                            <!-- 9. Skills Chips -->
                            <div class="field-row" style="align-items: flex-start;">
                                <label class="field-label" style="padding-top: 6px;">Skills</label>
                                <div class="field-input-wrap">
                                    <div class="skill-input-container" id="skills_chips_box">
                                        <input type="text" class="skill-tag-input" id="skill_quick_input" placeholder="Type skill & press Enter..." onkeydown="handleSkillInput(event)">
                                    </div>
                                    <input type="hidden" name="skills" id="form_skills_hidden" value="">
                                </div>
                                <span class="conf-badge conf-none" id="badge_skills" style="margin-top: 6px;">—</span>
                            </div>

                            <!-- 10. Contact Email -->
                            <div class="field-row">
                                <label class="field-label">Contact Email</label>
                                <div class="field-input-wrap">
                                    <input type="email" name="contact_email" id="form_contact_email" class="form-control" placeholder="Not specified">
                                </div>
                                <span class="conf-badge conf-none" id="badge_contact_email">—</span>
                            </div>

                            <!-- 11. Contact Phone -->
                            <div class="field-row">
                                <label class="field-label">Contact Phone</label>
                                <div class="field-input-wrap">
                                    <input type="text" name="contact_phone" id="form_contact_phone" class="form-control" placeholder="Not specified">
                                </div>
                                <span class="conf-badge conf-none" id="badge_contact_phone">—</span>
                            </div>

                            <!-- 12. Application URL -->
                            <div class="field-row">
                                <label class="field-label">Application URL</label>
                                <div class="field-input-wrap">
                                    <input type="url" name="application_url" id="form_application_url" class="form-control" placeholder="Not specified">
                                </div>
                                <span class="conf-badge conf-none" id="badge_application_url">—</span>
                            </div>

                            <!-- 13. Job Description -->
                            <div class="field-row" style="align-items: flex-start;">
                                <label class="field-label" style="padding-top: 6px;">Description <span class="text-danger">*</span></label>
                                <div class="field-input-wrap">
                                    <textarea name="description" id="form_description" class="form-control" rows="8" placeholder="Structured job description, responsibilities, and requirements will be placed here..." required></textarea>
                                </div>
                                <span class="conf-badge conf-none" id="badge_description" style="margin-top: 6px;">—</span>
                            </div>

                            <!-- Confidence Legend -->
                            <div style="display: flex; gap: 14px; font-size: 11px; color: #64748B; margin-top: 14px; padding-top: 10px; border-top: 1px solid #F1F5F9;">
                                <span><span style="color: #03855c;">●</span> High Confidence</span>
                                <span><span style="color: #D97706;">●</span> Needs Review</span>
                                <span><span style="color: #DC2626;">●</span> Not Found</span>
                            </div>

                            <!-- Bottom Actions -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--import-border);">
                                <button type="button" class="btn btn-default" onclick="resetForm()" style="border-radius: 8px; font-weight: 600; color: #475569;">
                                    <i class="fa fa-refresh"></i> Reset All
                                </button>
                                <div style="display: flex; gap: 10px;">
                                    <button type="button" class="btn btn-primary" onclick="submitJob('draft')" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; font-weight: 700; padding: 9px 18px;">
                                        <i class="fa fa-floppy-o"></i> Save as Draft
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="submitJob('publish')" style="background: #03855c; border-color: #03855c; border-radius: 8px; font-weight: 700; padding: 9px 20px;">
                                        <i class="fa fa-rocket"></i> Publish Job
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var currentSkills = [];

    // Tab Switching
    function switchTab(tab) {
        if (tab === 'text') {
            $('#tab_btn_text').addClass('active');
            $('#tab_btn_image').removeClass('active');
            $('#tab_content_text').show();
            $('#tab_content_image').hide();
        } else {
            $('#tab_btn_image').addClass('active');
            $('#tab_btn_text').removeClass('active');
            $('#tab_content_image').show();
            $('#tab_content_text').hide();
        }
    }

    function updateCharCount(el) {
        var len = el.value.length;
        $('#char_counter').text(len + ' / 10000');
    }

    // Image Upload & Preview Handling
    function handleImageSelect(input) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            if (file.size > 5 * 1024 * 1024) {
                alert('Image size exceeds 5MB limit.');
                input.value = '';
                return;
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image_preview_img').attr('src', e.target.result).show();
                $('#image_preview_placeholder').hide();
                $('#btn_remove_image').show();
            }
            reader.readAsDataURL(file);
        }
    }

    function removeSelectedImage() {
        $('#job_image_file').val('');
        $('#image_preview_img').attr('src', '#').hide();
        $('#image_preview_placeholder').show();
        $('#btn_remove_image').hide();
    }

    // Drag and drop support
    var dropzone = document.getElementById('image_dropzone');
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.add('dragover');
            }, false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
            }, false);
        });
        dropzone.addEventListener('drop', (e) => {
            var dt = e.dataTransfer;
            var files = dt.files;
            if (files.length > 0) {
                document.getElementById('job_image_file').files = files;
                handleImageSelect(document.getElementById('job_image_file'));
            }
        });
    }

    // Extraction Trigger
    function runExtraction(type) {
        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');

        if (type === 'text') {
            var text = $('#raw_job_text').val().trim();
            if (!text) {
                alert('Please paste raw job content first.');
                return;
            }
            formData.append('raw_text', text);
            $('#btn_extract_text').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Analyzing with AI...');
        } else {
            var fileInput = document.getElementById('job_image_file');
            if (!fileInput.files || !fileInput.files[0]) {
                alert('Please choose or drop an image first.');
                return;
            }
            formData.append('job_image', fileInput.files[0]);
            $('#btn_extract_image').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Reading Image with AI Vision...');
        }

        var ajaxTimeout = (type === 'image') ? 130000 : 60000; // 130s for image, 60s for text

        $.ajax({
            url: "{{ route('admin.ai.job_import.extract') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            timeout: ajaxTimeout,
            success: function(res) {
                if (type === 'text') {
                    $('#btn_extract_text').prop('disabled', false).html('<i class="fa fa-magic"></i> Extract & Fill Job Form');
                } else {
                    $('#btn_extract_image').prop('disabled', false).html('<i class="fa fa-bolt"></i> Extract From Image');
                }

                if (res.success && res.data) {
                    populateFormWithAIData(res.data, res.stats);
                } else {
                    alert(res.message || 'AI extraction failed.');
                }
            },
            error: function(xhr, status, error) {
                if (type === 'text') {
                    $('#btn_extract_text').prop('disabled', false).html('<i class="fa fa-magic"></i> Extract & Fill Job Form');
                } else {
                    $('#btn_extract_image').prop('disabled', false).html('<i class="fa fa-bolt"></i> Extract From Image');
                }

                if (status === 'timeout') {
                    alert('⏱️ AI is taking longer than expected. This can happen with large images.\n\nPlease try again with a smaller/clearer image, or paste the job text manually.');
                } else {
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Error communicating with AI service. Please try again.';
                    alert(msg);
                }
            }
        });
    }

    // Populate Fields
    function populateFormWithAIData(data, stats) {
        // Stats
        if (stats) {
            $('#stat_extracted_count').text(stats.extracted_count || 0);
            $('#stat_review_count').text(stats.review_count || 0);
            $('#stat_not_found_count').text(stats.not_found_count || 0);
        }

        $('#extraction_status_badge').html('<span style="background: #ECFDF5; color: #03855c; border: 1px solid #A7F3D0; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;"><i class="fa fa-check-circle"></i> Extraction Complete</span>');

        // Fields
        $('#form_title').val(data.title || '');
        $('#form_company_name').val(data.company_name || '');
        if (data.functional_area_id) {
            $('#form_functional_area_id').val(data.functional_area_id);
        }
        $('#form_location_display').val(data.location || '');
        if (data.job_type_id) {
            $('#form_job_type_id').val(data.job_type_id);
        }
        $('#form_is_freelance').val(data.is_freelance ? '1' : '0');
        if (data.job_experience_id) {
            $('#form_job_experience_id').val(data.job_experience_id);
        }
        $('#form_salary_from').val(data.salary_from || '');
        $('#form_salary_to').val(data.salary_to || '');
        $('#form_contact_email').val(data.contact_email && data.contact_email !== 'Not specified' ? data.contact_email : '');
        $('#form_contact_phone').val(data.contact_phone && data.contact_phone !== 'Not specified' ? data.contact_phone : '');
        $('#form_application_url').val(data.application_url && data.application_url !== 'Not specified' ? data.application_url : '');
        $('#form_description').val(data.description || '');

        // Skills
        currentSkills = data.skills_array || [];
        renderSkillChips();

        // Confidence Badges
        var conf = data.confidence || {};
        setBadge('badge_title', conf.title || 'high');
        setBadge('badge_company_name', conf.company_name || 'high');
        setBadge('badge_category', conf.category || 'high');
        setBadge('badge_location', conf.location || 'high');
        setBadge('badge_job_type', conf.job_type || 'review');
        setBadge('badge_work_mode', conf.work_mode || 'high');
        setBadge('badge_experience', conf.experience || 'review');
        setBadge('badge_salary', conf.salary || 'review');
        setBadge('badge_skills', conf.skills || 'high');
        setBadge('badge_contact_email', conf.contact_email || 'high');
        setBadge('badge_contact_phone', conf.contact_phone || 'review');
        setBadge('badge_application_url', conf.application_url || 'review');
        setBadge('badge_description', 'high');
    }

    function setBadge(elemId, status) {
        var el = $('#' + elemId);
        el.removeClass('conf-high conf-review conf-none');
        var s = (status || '').toLowerCase();
        if (s === 'high') {
            el.addClass('conf-high').text('High');
        } else if (s === 'review' || s === 'medium' || s === 'low') {
            el.addClass('conf-review').text('Review');
        } else {
            el.addClass('conf-none').text('Missing');
        }
    }

    // Skills Tag Management
    function renderSkillChips() {
        var box = $('#skills_chips_box');
        box.find('.skill-tag-chip').remove();

        currentSkills.forEach(function(sk, index) {
            var chip = $('<span class="skill-tag-chip">' + sk + ' <span class="remove-skill-btn" onclick="removeSkill(' + index + ')">&times;</span></span>');
            $('#skill_quick_input').before(chip);
        });

        $('#form_skills_hidden').val(currentSkills.join(','));
    }

    function handleSkillInput(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            var val = $('#skill_quick_input').val().replace(',', '').trim();
            if (val && currentSkills.indexOf(val) === -1) {
                currentSkills.push(val);
                renderSkillChips();
                $('#skill_quick_input').val('');
            }
        }
    }

    function removeSkill(index) {
        currentSkills.splice(index, 1);
        renderSkillChips();
    }

    function resetForm() {
        if (confirm('Reset and clear all extracted fields?')) {
            document.getElementById('aiJobForm').reset();
            currentSkills = [];
            renderSkillChips();
            $('.conf-badge').removeClass('conf-high conf-review').addClass('conf-none').text('—');
            $('#stat_extracted_count').text('0');
            $('#stat_review_count').text('0');
            $('#stat_not_found_count').text('0');
            $('#extraction_status_badge').html('<span style="background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">Awaiting Input</span>');
        }
    }

    // Save or Publish Job
    function submitJob(actionType) {
        var title = $('#form_title').val().trim();
        var company = $('#form_company_name').val().trim();
        var cat = $('#form_functional_area_id').val();
        var desc = $('#form_description').val().trim();

        if (!title || !company || !cat || !desc) {
            alert('Please fill all required fields marked with * (Job Title, Company Name, Category, Description).');
            return;
        }

        var postData = {
            _token: '{{ csrf_token() }}',
            action_type: actionType,
            title: title,
            company_name: company,
            functional_area_id: cat,
            country_id: $('#form_country_id').val(),
            job_type_id: $('#form_job_type_id').val(),
            is_freelance: $('#form_is_freelance').val(),
            job_experience_id: $('#form_job_experience_id').val(),
            salary_from: $('#form_salary_from').val(),
            salary_to: $('#form_salary_to').val(),
            salary_currency: $('#form_salary_currency').val(),
            skills: currentSkills,
            contact_email: $('#form_contact_email').val(),
            contact_phone: $('#form_contact_phone').val(),
            application_url: $('#form_application_url').val(),
            description: desc,
        };

        $.ajax({
            url: "{{ route('admin.ai.job_import.save') }}",
            type: "POST",
            data: postData,
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    if (actionType === 'publish' && res.live_url) {
                        window.open(res.live_url, '_blank');
                    }
                    window.location.href = "{{ route('admin.ai.pipeline') }}?tab=published";
                } else {
                    alert(res.message || 'Failed to save job.');
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error saving job.';
                alert(msg);
            }
        });
    }
</script>
@endsection
