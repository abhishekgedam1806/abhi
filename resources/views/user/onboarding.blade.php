@extends('layouts.app')

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<div class="onboarding-wrapper" style="background: #F8FAFC; min-height: 88vh; padding: 36px 16px 60px; display: flex; align-items: center; justify-content: center;">
    <div class="onboarding-card" style="width: 100%; max-width: 520px; background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); overflow: hidden; position: relative;">

        <!-- Top Progress Header -->
        <div class="onboarding-top-bar" id="onboardingTopBar" style="padding: 20px 24px 14px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #F1F5F9;">
            <button type="button" id="btnPrevStep" onclick="prevStep()" style="background: none; border: none; font-size: 18px; color: #334155; cursor: pointer; padding: 4px 8px; border-radius: 8px; transition: all 0.15s ease;">
                <i class="fa fa-arrow-left"></i>
            </button>

            <div class="step-dots-container" style="display: flex; gap: 6px; align-items: center;" id="stepDotsContainer"></div>

            <span class="step-counter-text" id="stepCounterText" style="font-size: 12.5px; font-weight: 700; color: #64748B;">
                1 of 10
            </span>
        </div>

        <form id="onboardingForm" onsubmit="return false;" style="padding: 28px 24px;">
            {{ csrf_field() }}
            <input type="hidden" name="current_active_step" id="current_active_step" value="{{ $currentStep }}">

            <!-- ── SCREEN 1: WELCOME ── -->
            <div class="onboarding-screen-step" id="screen_step_1" style="display: none; text-align: center;">
                <div style="width: 130px; height: 130px; margin: 0 auto 20px; background: #EFF6FF; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative;">
                    <i class="fa fa-id-card-o" style="font-size: 54px; color: #2563EB;"></i>
                    <span style="position: absolute; top: 12px; right: 12px; width: 22px; height: 22px; background: #03855c; color: #FFFFFF; border-radius: 50%; font-size: 12px; font-weight: bold; display: flex; align-items: center; justify-content: center;">&#10003;</span>
                </div>
                <h2 style="font-size: 24px; font-weight: 900; color: #0F172A; margin: 0 0 10px 0; letter-spacing: -0.4px;">Let's build your profile</h2>
                <p style="font-size: 14.5px; color: #64748B; line-height: 1.5; margin: 0 0 32px 0;">
                    Help us know more about you so we can find the right job opportunities tailored for you.
                </p>
                <button type="button" onclick="submitCurrentStep(1)" class="btn-primary-ob" style="width: 100%;">Let's Get Started</button>
                <div style="margin-top: 16px;">
                    <a href="{{ route('home') }}" style="font-size: 13.5px; font-weight: 600; color: #64748B; text-decoration: none;">I'll do it later</a>
                </div>
            </div>

            <!-- ── SCREEN 2: PROFILE TYPE ── -->
            <div class="onboarding-screen-step" id="screen_step_2" style="display: none;">
                <h2 style="font-size: 21px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">Choose your profile type</h2>
                <p style="font-size: 13.5px; color: #64748B; margin: 0 0 24px 0;">This helps us customize your experience</p>

                <div class="profile-type-cards" style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 28px;">
                    <label class="selection-card" onclick="selectProfileType('fresher', this)"
                        style="display: flex; align-items: flex-start; gap: 16px; padding: 18px 16px;
                               border: 1.5px solid {{ ($user->profile_type != 'experienced') ? '#2563EB' : '#E2E8F0' }};
                               border-radius: 16px; cursor: pointer;
                               background: {{ ($user->profile_type != 'experienced') ? '#F0F7FF' : '#FFFFFF' }};
                               transition: all 0.15s ease; position: relative;">
                        <input type="radio" name="profile_type" value="fresher" {{ ($user->profile_type != 'experienced') ? 'checked' : '' }} style="display: none;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                            <i class="fa fa-graduation-cap"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 15.5px; font-weight: 800; color: #0F172A; margin: 0 0 3px 0;">I am a student / Fresher</h4>
                            <p style="font-size: 12.5px; color: #64748B; margin: 0; line-height: 1.4;">I am currently studying or have never worked before</p>
                        </div>
                        <span class="card-check-icon" style="width: 20px; height: 20px; border-radius: 50%; background: #2563EB; color: #FFFFFF; font-size: 11px; font-weight: bold; display: {{ ($user->profile_type != 'experienced') ? 'flex' : 'none' }}; align-items: center; justify-content: center;">&#10003;</span>
                    </label>

                    <label class="selection-card" onclick="selectProfileType('experienced', this)"
                        style="display: flex; align-items: flex-start; gap: 16px; padding: 18px 16px;
                               border: 1.5px solid {{ ($user->profile_type == 'experienced') ? '#2563EB' : '#E2E8F0' }};
                               border-radius: 16px; cursor: pointer;
                               background: {{ ($user->profile_type == 'experienced') ? '#F0F7FF' : '#FFFFFF' }};
                               transition: all 0.15s ease; position: relative;">
                        <input type="radio" name="profile_type" value="experienced" {{ ($user->profile_type == 'experienced') ? 'checked' : '' }} style="display: none;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                            <i class="fa fa-briefcase"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 15.5px; font-weight: 800; color: #0F172A; margin: 0 0 3px 0;">I am working / Experienced</h4>
                            <p style="font-size: 12.5px; color: #64748B; margin: 0; line-height: 1.4;">I have work experience in a company or organization</p>
                        </div>
                        <span class="card-check-icon" style="width: 20px; height: 20px; border-radius: 50%; background: #2563EB; color: #FFFFFF; font-size: 11px; font-weight: bold; display: {{ ($user->profile_type == 'experienced') ? 'flex' : 'none' }}; align-items: center; justify-content: center;">&#10003;</span>
                    </label>
                </div>

                <button type="button" onclick="submitCurrentStep(2)" class="btn-primary-ob">Continue</button>
            </div>

            <!-- ── SCREEN 3: LOCATION & DISTANCE ── -->
            <div class="onboarding-screen-step" id="screen_step_3" style="display: none;">
                <h2 style="font-size: 21px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">Current Location</h2>
                <p style="font-size: 13.5px; color: #64748B; margin: 0 0 20px 0;">This helps us show jobs near you</p>

                <div style="margin-bottom: 20px; position: relative;">
                    <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">
                        Current City <span style="color: #EF4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <i class="fa fa-map-marker text-danger" style="position: absolute; left: 14px; top: 14px; font-size: 16px;"></i>
                        <input type="text" name="city_name" id="city_name"
                               value="{{ $user->getCity('city') }}"
                               placeholder="Enter your city (e.g. Nagpur, Pune, Mumbai)"
                               autocomplete="off"
                               style="width: 100%; padding: 12px 38px 12px 38px; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 14.5px; outline: none; font-weight: 600; color: #0F172A;"
                               oninput="onCityInput(this.value); updateDistanceHint()">
                        <button type="button" onclick="clearCityInput()" style="position: absolute; right: 12px; top: 12px; background: none; border: none; color: #94A3B8; font-size: 14px; cursor: pointer;">&#10005;</button>
                        <!-- City autocomplete dropdown -->
                        <div id="city_dropdown" style="display:none; position: absolute; top: 100%; left: 0; right: 0; background: #FFFFFF; border: 1.5px solid #CBD5E1; border-top: none; border-radius: 0 0 12px 12px; z-index: 100; max-height: 220px; overflow-y: auto; box-shadow: 0 8px 24px rgba(0,0,0,0.08);"></div>
                    </div>
                    <div id="city_error" style="display:none; color: #EF4444; font-size: 12.5px; font-weight: 600; margin-top: 6px;">Please enter your city to continue.</div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 8px;">
                        Preferred work distance <span style="color: #EF4444;">*</span>
                    </label>
                    <div class="distance-pill-group" style="display: flex; gap: 8px; flex-wrap: wrap;">
                        @php $savedDist = $user->preferred_job_distance ?: ''; @endphp
                        @foreach(['5 km', '10 km', '20 km', '50 km', 'Anywhere'] as $dst)
                            <button type="button" class="btn-pill {{ $savedDist == $dst ? 'active' : '' }}"
                                    onclick="selectDistance('{{ $dst }}', this)"
                                    style="flex: 1; min-width: 70px; padding: 10px 8px; border-radius: 10px; font-size: 13px; font-weight: 700;
                                           border: 1.5px solid {{ $savedDist == $dst ? '#2563EB' : '#E2E8F0' }};
                                           background: {{ $savedDist == $dst ? '#EFF6FF' : '#FFFFFF' }};
                                           color: {{ $savedDist == $dst ? '#2563EB' : '#475569' }};
                                           cursor: pointer; transition: all 0.15s ease;">
                                {{ $dst }}
                            </button>
                        @endforeach
                        <input type="hidden" name="preferred_job_distance" id="preferred_job_distance" value="{{ $savedDist }}">
                    </div>
                    <div id="distance_error" style="display:none; color: #EF4444; font-size: 12.5px; font-weight: 600; margin-top: 6px;">Please select your preferred work distance.</div>
                </div>

                <div class="location-hint-box" style="padding: 14px 16px; background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 12px; margin-bottom: 28px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa fa-compass" style="color: #03855c; font-size: 18px;"></i>
                    <span id="distanceHintText" style="font-size: 13px; font-weight: 600; color: #166534;">
                        Select a city and distance to see your job reach
                    </span>
                </div>

                <button type="button" onclick="submitCurrentStep(3)" class="btn-primary-ob">Continue</button>
            </div>

            <!-- ── SCREEN 4: HIGHEST QUALIFICATION ── -->
            <div class="onboarding-screen-step" id="screen_step_4" style="display: none;">
                <h2 style="font-size: 21px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">Education</h2>
                <p style="font-size: 13.5px; color: #64748B; margin: 0 0 20px 0;">Tell us about your educational background</p>

                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 12px;">
                    Highest Qualification <span style="color: #EF4444;">*</span>
                </label>

                <div class="grid-pill-selector" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 8px;">
                    @php
                        $savedQual = $user->highest_qualification ?: '';
                        $qualList  = ['Doctorate', 'Post Graduate', 'Graduate', 'Diploma', 'Class XII', 'Class X', 'Below Class X'];
                    @endphp
                    @foreach($qualList as $qItem)
                        <button type="button" class="btn-grid-pill {{ $savedQual == $qItem ? 'active' : '' }}"
                                id="qual_btn_{{ strtolower(str_replace(' ', '_', $qItem)) }}"
                                onclick="selectQualification('{{ $qItem }}', this)"
                                style="padding: 12px 14px; border-radius: 12px; font-size: 13.5px; font-weight: 700;
                                       border: 1.5px solid {{ $savedQual == $qItem ? '#2563EB' : '#E2E8F0' }};
                                       background: {{ $savedQual == $qItem ? '#EFF6FF' : '#FFFFFF' }};
                                       color: {{ $savedQual == $qItem ? '#2563EB' : '#334155' }};
                                       cursor: pointer; text-align: center; transition: all 0.15s ease;">
                            {{ $qItem }}
                        </button>
                    @endforeach
                    <input type="hidden" name="highest_qualification" id="highest_qualification" value="{{ $savedQual }}">
                </div>
                <div id="qual_error" style="display:none; color: #EF4444; font-size: 12.5px; font-weight: 600; margin-bottom: 14px;">Please select your highest qualification.</div>

                <button type="button" onclick="submitCurrentStep(4)" class="btn-primary-ob" style="margin-top: 14px;">Continue</button>
            </div>

            <!-- ── SCREEN 5: COURSE / DEGREE (Dynamic) ── -->
            <div class="onboarding-screen-step" id="screen_step_5" style="display: none;">
                <h2 style="font-size: 21px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">Course / Degree</h2>
                <p style="font-size: 13.5px; color: #64748B; margin: 0 0 20px 0;">Select your course / degree</p>

                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 12px;">
                    Course / Degree <span style="color: #EF4444;">*</span>
                </label>

                <!-- Dynamic course grid — populated by JS when qualification is selected -->
                <div id="course_grid" class="grid-pill-selector" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 8px; min-height: 60px;">
                    <div style="color: #94A3B8; font-size: 13.5px; padding: 12px; grid-column: 1/-1; text-align: center;">
                        <i class="fa fa-spinner fa-spin"></i> Loading courses...
                    </div>
                </div>
                <div id="degree_error" style="display:none; color: #EF4444; font-size: 12.5px; font-weight: 600; margin-bottom: 14px;">Please select your course.</div>
                <input type="hidden" name="course_degree" id="course_degree" value="{{ $user->course_degree }}">

                <button type="button" onclick="submitCurrentStep(5)" class="btn-primary-ob" style="margin-top: 14px;">Continue</button>
            </div>

            <!-- ── SCREEN 6: COURSE TYPE (Dynamic) ── -->
            <div class="onboarding-screen-step" id="screen_step_6" style="display: none;">
                <h2 style="font-size: 21px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">Course Type</h2>
                <p style="font-size: 13.5px; color: #64748B; margin: 0 0 20px 0;">Select your course type</p>

                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 12px;">
                    Course Type <span style="color: #EF4444;">*</span>
                </label>

                <!-- Dynamic list — populated by JS when course is selected -->
                <div id="course_type_list" class="list-pill-selector" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 8px; min-height: 48px;">
                    <div style="color: #94A3B8; font-size: 13.5px; padding: 12px; text-align: center;">
                        <i class="fa fa-spinner fa-spin"></i> Loading...
                    </div>
                </div>
                <div id="ctype_error" style="display:none; color: #EF4444; font-size: 12.5px; font-weight: 600; margin-bottom: 14px;">Please select your course type.</div>
                <input type="hidden" name="course_type" id="course_type" value="{{ $user->course_type }}">

                <button type="button" onclick="submitCurrentStep(6)" class="btn-primary-ob" style="margin-top: 14px;">Continue</button>
            </div>

            <!-- ── SCREEN 7: SPECIALIZATION (Multi-select) ── -->
            <div class="onboarding-screen-step" id="screen_step_7" style="display: none;">
                <h2 style="font-size: 21px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">Specialization</h2>
                <p style="font-size: 13.5px; color: #64748B; margin: 0 0 20px 0;">Select your specialization / branch</p>

                <!-- Spec Search Input -->
                <div style="margin-bottom: 16px;">
                    <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">
                        Add Specialization <span style="color: #EF4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <i class="fa fa-search" style="position: absolute; left: 14px; top: 14px; color: #94A3B8; font-size: 14px;"></i>
                        <input type="text" id="spec_search_input" placeholder="Search or type specialization"
                               style="width: 100%; padding: 12px 14px 12px 38px; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 14px; outline: none; font-weight: 600;"
                               onkeydown="handleSpecKeyDown(event)">
                    </div>
                </div>

                <!-- Selected Spec Chips -->
                <div style="margin-bottom: 16px;">
                    <span style="font-size: 12px; font-weight: 700; color: #64748B; display: block; margin-bottom: 8px;">Selected Specializations</span>
                    <div id="selected_specs_cloud" style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 40px; padding: 8px; background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 12px;">
                        @foreach($savedSpecializations as $sp)
                            <span class="selected-tag-chip" data-spec="{{ $sp }}">
                                {{ $sp }} <i class="fa fa-times" onclick="removeSpecChip('{{ $sp }}', this)"></i>
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Suggested Specs Cloud (dynamic) -->
                <div style="margin-bottom: 28px;">
                    <span style="font-size: 12px; font-weight: 700; color: #64748B; display: block; margin-bottom: 8px;">Suggested Specializations</span>
                    <div id="suggested_specs_cloud" class="chips-cloud" style="display: flex; flex-wrap: wrap; gap: 8px;">
                        <span style="color: #94A3B8; font-size: 13px;">Loading suggestions...</span>
                    </div>
                </div>

                <div id="spec_error" style="display:none; color: #EF4444; font-size: 12.5px; font-weight: 600; margin-bottom: 10px;">Please add at least one specialization.</div>
                <button type="button" onclick="submitCurrentStep(7)" class="btn-primary-ob">Continue</button>
            </div>

            <!-- ── SCREEN 8: COLLEGE / UNIVERSITY (Official Search & Manual Fallback) ── -->
            <div class="onboarding-screen-step" id="screen_step_8" style="display: none;">
                <h2 style="font-size: 21px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">College / University</h2>
                <p style="font-size: 13.5px; color: #64748B; margin: 0 0 20px 0;">Enter your college or university name</p>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">
                        College / University Name <span style="color: #EF4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="text" name="institution_name" id="institution_name"
                               value="{{ $user->institution_name }}"
                               placeholder="Add your college or university"
                               autocomplete="off"
                               style="width: 100%; padding: 12px 38px 12px 14px; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 14px; outline: none; font-weight: 600; color: #0F172A;"
                               oninput="onInstitutionInput(this.value)">
                        <button type="button" onclick="clearInstitutionInput()" style="position: absolute; right: 12px; top: 12px; background: none; border: none; color: #94A3B8; font-size: 14px; cursor: pointer;">&#10005;</button>
                        
                        <!-- Hidden inputs for institution tracking -->
                        <input type="hidden" name="institution_id" id="institution_id" value="{{ $user->institution_id }}">
                        <input type="hidden" name="institution_type" id="institution_type" value="{{ $user->institution_type ?: 'official' }}">
                        <input type="hidden" name="institution_verification_status" id="institution_verification_status" value="{{ $user->institution_verification_status ?: 'verified' }}">
                        
                        <!-- Rich Institution dropdown with official suggestions & manual fallback -->
                        <div id="institution_dropdown" style="display:none; position: absolute; top: 100%; left: 0; right: 0; background: #FFFFFF; border: 1.5px solid #CBD5E1; border-top: none; border-radius: 0 0 12px 12px; z-index: 100; max-height: 280px; overflow-y: auto; box-shadow: 0 10px 28px rgba(0,0,0,0.1);"></div>
                    </div>
                    
                    <!-- Dynamic Verification Status Badge -->
                    <div id="inst_status_badge" style="display: {{ $user->institution_name ? 'flex' : 'none' }}; margin-top: 8px; font-size: 12.5px; font-weight: 600; align-items: center; gap: 6px; color: {{ ($user->institution_type == 'manual') ? '#D97706' : '#03855c' }};">
                        @if($user->institution_type == 'manual')
                            <i class="fa fa-info-circle"></i> Custom / Unverified Institution
                        @else
                            <i class="fa fa-check-circle"></i> Officially Recognized Institution (UGC / AISHE)
                        @endif
                    </div>
                    <div id="inst_error" style="display:none; color: #EF4444; font-size: 12.5px; font-weight: 600; margin-top: 6px;">Please enter or select your college / university.</div>
                </div>

                <div class="tip-box" style="padding: 14px 16px; background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 12px; margin-bottom: 28px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa fa-lightbulb-o" style="color: #D97706; font-size: 18px;"></i>
                    <span style="font-size: 13px; color: #92400E; font-weight: 600;">
                        Tip: Start typing to search from official colleges & universities across India, or add manually if not listed.
                    </span>
                </div>

                <button type="button" onclick="submitCurrentStep(8)" class="btn-primary-ob">Continue</button>
            </div>

            <!-- ── SCREEN 9: YEAR DETAILS & PERCENTAGE ── -->
            <div class="onboarding-screen-step" id="screen_step_9" style="display: none;">
                <h2 style="font-size: 21px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">Year Details</h2>
                <p style="font-size: 13.5px; color: #64748B; margin: 0 0 20px 0;">Enter your course duration</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
                    <div>
                        <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Starting Year <span style="color: #EF4444;">*</span></label>
                        <select name="degree_start_year" id="degree_start_year" style="width: 100%; padding: 12px 14px; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 14px; font-weight: 600; outline: none; background: #FFFFFF; color: #0F172A;">
                            @php $curYear = (int)date('Y'); @endphp
                            @for($y = $curYear + 2; $y >= $curYear - 30; $y--)
                                <option value="{{ $y }}" {{ ($user->degree_start_year == $y || ($user->degree_start_year == '' && $y == $curYear - 4)) ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Passing Year <span style="color: #EF4444;">*</span></label>
                        <select name="degree_end_year" id="degree_end_year" style="width: 100%; padding: 12px 14px; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 14px; font-weight: 600; outline: none; background: #FFFFFF; color: #0F172A;">
                            @for($y = $curYear + 5; $y >= $curYear - 30; $y--)
                                <option value="{{ $y }}" {{ ($user->degree_end_year == $y || ($user->degree_end_year == '' && $y == $curYear)) ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 28px;">
                    <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">
                        CGPA / Percentage <span style="font-size: 12px; font-weight: 500; color: #64748B;">(Optional)</span>
                    </label>
                    <input type="text" name="degree_percentage" id="degree_percentage" value="{{ $user->degree_percentage }}" placeholder="e.g. 8.2 or 78%"
                           style="width: 100%; padding: 12px 14px; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 14px; outline: none; font-weight: 600; color: #0F172A;">
                </div>

                <button type="button" onclick="submitCurrentStep(9)" class="btn-primary-ob">Continue</button>
            </div>

            <!-- ── SCREEN 10: SKILLS (Dynamic suggestions) ── -->
            <div class="onboarding-screen-step" id="screen_step_10" style="display: none;">
                <h2 style="font-size: 21px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">Skills</h2>
                <p style="font-size: 13.5px; color: #64748B; margin: 0 0 20px 0;">Add your skills to get better job matches</p>

                <div style="margin-bottom: 16px;">
                    <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">
                        Add Skills <span style="color: #EF4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <i class="fa fa-search" style="position: absolute; left: 14px; top: 14px; color: #94A3B8; font-size: 14px;"></i>
                        <input type="text" id="skill_search_input" placeholder="Search skills (e.g. React, Tally, SEO)"
                               style="width: 100%; padding: 12px 14px 12px 38px; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 14px; outline: none; font-weight: 600;"
                               onkeydown="handleSkillKeyDown(event)">
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <span style="font-size: 12px; font-weight: 700; color: #64748B; display: block; margin-bottom: 8px;">Selected Skills</span>
                    <div id="selected_skills_cloud" style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 40px; padding: 8px; background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 12px;">
                        @foreach($userSkills as $sName)
                            <span class="selected-tag-chip" data-skill="{{ $sName }}">
                                {{ $sName }} <i class="fa fa-times" onclick="removeSkillChip('{{ $sName }}', this)"></i>
                            </span>
                        @endforeach
                    </div>
                </div>

                <div style="margin-bottom: 28px;">
                    <span style="font-size: 12px; font-weight: 700; color: #64748B; display: block; margin-bottom: 8px;">Suggested Skills</span>
                    <div id="suggested_skills_cloud" class="chips-cloud" style="display: flex; flex-wrap: wrap; gap: 8px;">
                        <span style="color: #94A3B8; font-size: 13px; padding: 4px;">Loading suggestions...</span>
                    </div>
                </div>

                <button type="button" onclick="submitCurrentStep(10)" class="btn-primary-ob">Continue</button>
            </div>

            <!-- ── SCREEN 11: PREFERRED JOB ROLES & WORK EXP ── -->
            <div class="onboarding-screen-step" id="screen_step_11" style="display: none;">
                <h2 style="font-size: 21px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">Preferred Job Roles</h2>
                <p style="font-size: 13.5px; color: #64748B; margin: 0 0 20px 0;">Select the roles you are looking for</p>

                <!-- Work Experience (if experienced) -->
                <div id="experienced_section_box" style="display: {{ ($user->profile_type == 'experienced') ? 'block' : 'none' }}; margin-bottom: 24px; padding: 16px; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px;">
                    <h4 style="font-size: 14.5px; font-weight: 800; color: #0F172A; margin: 0 0 12px 0;">
                        <i class="fa fa-briefcase text-primary"></i> Work Experience Details
                    </h4>
                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12.5px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Company Name</label>
                        <input type="text" name="company_name" id="company_name"
                               value="{{ $userExperience ? $userExperience->company : '' }}"
                               placeholder="e.g. Infosys, TCS, Apex Logistics"
                               style="width: 100%; padding: 10px 12px; border: 1.5px solid #CBD5E1; border-radius: 10px; font-size: 13.5px; outline: none; font-weight: 600;">
                    </div>
                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 12.5px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">Your Job Title</label>
                        <input type="text" name="job_title" id="job_title"
                               value="{{ $userExperience ? $userExperience->title : '' }}"
                               placeholder="e.g. Senior Software Developer, Accountant"
                               style="width: 100%; padding: 10px 12px; border: 1.5px solid #CBD5E1; border-radius: 10px; font-size: 13.5px; outline: none; font-weight: 600;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="is_currently_working" id="is_currently_working" value="1"
                               {{ (!$userExperience || $userExperience->is_currently_working) ? 'checked' : '' }}
                               style="width: 16px; height: 16px; cursor: pointer;">
                        <label for="is_currently_working" style="font-size: 13px; font-weight: 600; color: #334155; margin: 0; cursor: pointer;">Currently working here</label>
                    </div>
                </div>

                <!-- Job Role Search -->
                <div style="margin-bottom: 16px;">
                    <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">
                        Add Job Roles <span style="color: #EF4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <i class="fa fa-search" style="position: absolute; left: 14px; top: 14px; color: #94A3B8; font-size: 14px;"></i>
                        <input type="text" id="role_search_input" placeholder="Search job roles (e.g. Frontend Developer)"
                               style="width: 100%; padding: 12px 14px 12px 38px; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 14px; outline: none; font-weight: 600;"
                               onkeydown="handleRoleKeyDown(event)">
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <span style="font-size: 12px; font-weight: 700; color: #64748B; display: block; margin-bottom: 8px;">Selected Roles</span>
                    <div id="selected_roles_cloud" style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 40px; padding: 8px; background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 12px;">
                        @foreach($userPreferredRoles as $rName)
                            <span class="selected-tag-chip" data-role="{{ $rName }}">
                                {{ $rName }} <i class="fa fa-times" onclick="removeRoleChip('{{ $rName }}', this)"></i>
                            </span>
                        @endforeach
                    </div>
                </div>

                <div style="margin-bottom: 28px;">
                    <span style="font-size: 12px; font-weight: 700; color: #64748B; display: block; margin-bottom: 8px;">Suggested Roles</span>
                    <div id="suggested_roles_cloud" class="chips-cloud" style="display: flex; flex-wrap: wrap; gap: 8px;">
                        <span style="color: #94A3B8; font-size: 13px; padding: 4px;">Loading suggestions...</span>
                    </div>
                </div>

                <button type="button" onclick="submitCurrentStep(11)" class="btn-primary-ob">Continue</button>
            </div>

            <!-- ── SCREEN 12: COMPLETED ── -->
            <div class="onboarding-screen-step" id="screen_step_12" style="display: none; text-align: center;">
                <div style="width: 130px; height: 130px; margin: 0 auto 20px; background: #EFF6FF; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative;">
                    <i class="fa fa-clipboard" style="font-size: 54px; color: #2563EB;"></i>
                    <span style="position: absolute; bottom: 8px; right: 8px; width: 34px; height: 34px; background: #2563EB; color: #FFFFFF; border: 3px solid #FFFFFF; border-radius: 50%; font-size: 18px; font-weight: bold; display: flex; align-items: center; justify-content: center;">&#10003;</span>
                </div>
                <h2 style="font-size: 24px; font-weight: 900; color: #0F172A; margin: 0 0 10px 0; letter-spacing: -0.4px;">Profile Completed!</h2>
                <p style="font-size: 14.5px; color: #64748B; line-height: 1.5; margin: 0 0 32px 0;">
                    Great job! Your profile is ready. We will show you the most relevant job opportunities based on your details.
                </p>
                <a href="{{ route('home') }}" class="btn-primary-ob" style="display: block; width: 100%; text-decoration: none; box-sizing: border-box;">View Recommended Jobs</a>
                <div style="margin-top: 16px;">
                    <a href="{{ route('my.profile') }}" style="font-size: 13.5px; font-weight: 700; color: #2563EB; text-decoration: none;">Edit Profile</a>
                </div>
            </div>

        </form>
    </div>
</div>

<style>
.onboarding-card { animation: fadeInCard 0.25s ease-out; }
@keyframes fadeInCard {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.btn-primary-ob {
    background: #2563EB; color: #FFFFFF; font-size: 15px; font-weight: 700;
    padding: 13px 20px; border-radius: 12px; border: none; cursor: pointer;
    width: 100%; text-align: center;
    box-shadow: 0 4px 14px rgba(37,99,235,0.28); transition: all 0.15s ease;
}
.btn-primary-ob:hover {
    background: #1D4ED8; transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(37,99,235,0.38); color: #FFFFFF;
}
.selected-tag-chip {
    display: inline-flex; align-items: center; gap: 6px;
    background: #EFF6FF; border: 1.5px solid #BFDBFE;
    color: #1E40AF; font-size: 12.5px; font-weight: 700;
    padding: 5px 12px; border-radius: 20px;
}
.selected-tag-chip i { cursor: pointer; color: #60A5FA; font-size: 11px; }
.selected-tag-chip i:hover { color: #EF4444; }
.btn-chip:hover { background: #EFF6FF !important; border-color: #93C5FD !important; color: #1D4ED8 !important; }
.step-dot { width: 8px; height: 8px; border-radius: 50%; background: #E2E8F0; transition: all 0.2s ease; }
.step-dot.active { width: 24px; border-radius: 6px; background: #2563EB; }
.step-dot.completed { background: #03855c; }
.autocomplete-item {
    padding: 10px 14px; font-size: 13.5px; font-weight: 600; color: #334155;
    cursor: pointer; border-bottom: 1px solid #F1F5F9; transition: background 0.1s;
}
.autocomplete-item:hover { background: #EFF6FF; color: #2563EB; }
.autocomplete-item:last-child { border-bottom: none; }
</style>

@include('includes.footer')
@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════
   ONBOARDING STATE
═══════════════════════════════════════════════════════ */
var totalSteps  = 10;
var activeStep  = parseInt(document.getElementById('current_active_step').value) || 1;

// Education context (updated as user progresses)
var eduContext = {
    qualification : '{{ $user->highest_qualification }}',
    course        : '{{ $user->course_degree }}',
    courseTypes   : [],
    specialization: '{{ is_array($savedSpecializations) ? implode(",", $savedSpecializations) : "" }}'
};

document.addEventListener('DOMContentLoaded', function() {
    renderStepDots();
    showStep(activeStep);

    // If resuming, pre-load dynamic content
    if (eduContext.qualification) {
        loadCoursesForQual(eduContext.qualification, false);
    }
    if (eduContext.course) {
        loadSpecsForCourse(eduContext.course);
        loadSkillSuggestions();
        loadRoleSuggestions();
    }
});

/* ═══════════════════════════════════════════════════════
   STEP NAVIGATION
═══════════════════════════════════════════════════════ */
function renderStepDots() {
    var c = document.getElementById('stepDotsContainer');
    if (!c) return;
    c.innerHTML = '';
    for (var i = 1; i <= totalSteps; i++) {
        var d = document.createElement('span');
        d.className = 'step-dot';
        d.id = 'dot_step_' + i;
        c.appendChild(d);
    }
}

function updateStepDots(stepNum) {
    var topBar  = document.getElementById('onboardingTopBar');
    var counter = document.getElementById('stepCounterText');
    if (stepNum === 1 || stepNum === 12) {
        if (topBar) topBar.style.display = 'none';
    } else {
        if (topBar) topBar.style.display = 'flex';
        var idx = Math.min(totalSteps, Math.max(1, stepNum - 1));
        if (counter) counter.innerText = idx + ' of ' + totalSteps;
        for (var i = 1; i <= totalSteps; i++) {
            var d = document.getElementById('dot_step_' + i);
            if (d) {
                d.className = 'step-dot';
                if (i === idx) d.classList.add('active');
                else if (i < idx) d.classList.add('completed');
            }
        }
    }
}

function showStep(stepNum) {
    for (var s = 1; s <= 12; s++) {
        var el = document.getElementById('screen_step_' + s);
        if (el) el.style.display = 'none';
    }
    var target = document.getElementById('screen_step_' + stepNum);
    if (target) {
        target.style.display = 'block';
        activeStep = stepNum;
        updateStepDots(stepNum);

        // Lazy load dynamic content when step is first shown
        if (stepNum === 5 && eduContext.qualification) {
            loadCoursesForQual(eduContext.qualification, false);
        }
        if (stepNum === 6 && eduContext.course) {
            loadCourseTypes(eduContext.course, false);
        }
        if (stepNum === 7 && eduContext.course) {
            loadSpecsForCourse(eduContext.course);
        }
        if (stepNum === 10) { loadSkillSuggestions(); }
        if (stepNum === 11) { loadRoleSuggestions(); }
    }
}

function prevStep() {
    if (activeStep > 1) showStep(activeStep - 1);
}

/* ═══════════════════════════════════════════════════════
   STEP 2: PROFILE TYPE
═══════════════════════════════════════════════════════ */
function selectProfileType(type, el) {
    document.querySelectorAll('.profile-type-cards .selection-card').forEach(function(c) {
        c.style.borderColor = '#E2E8F0'; c.style.background = '#FFFFFF';
        var chk = c.querySelector('.card-check-icon');
        if (chk) chk.style.display = 'none';
    });
    el.style.borderColor = '#2563EB'; el.style.background = '#F0F7FF';
    var chk = el.querySelector('.card-check-icon');
    if (chk) chk.style.display = 'flex';
    el.querySelector('input[type="radio"]').checked = true;
    var expBox = document.getElementById('experienced_section_box');
    if (expBox) expBox.style.display = (type === 'experienced') ? 'block' : 'none';
}

/* ═══════════════════════════════════════════════════════
   STEP 3: LOCATION & DISTANCE
═══════════════════════════════════════════════════════ */
var cityTimer = null;
function onCityInput(val) {
    clearTimeout(cityTimer);
    if (val.length < 2) { hideCityDrop(); return; }
    cityTimer = setTimeout(function() { fetchCitySuggestions(val); }, 300);
}

function fetchCitySuggestions(q) {
    $.getJSON('{{ route("api.cities.search") }}', { q: q }, function(data) {
        var drop = document.getElementById('city_dropdown');
        drop.innerHTML = '';
        if (!data || data.length === 0) { hideCityDrop(); return; }
        data.forEach(function(city) {
            var name = city.city || city;
            var item = document.createElement('div');
            item.className = 'autocomplete-item';
            item.textContent = name;
            item.onclick = function() {
                document.getElementById('city_name').value = name;
                hideCityDrop();
                updateDistanceHint();
            };
            drop.appendChild(item);
        });
        drop.style.display = 'block';
    });
}

function hideCityDrop() {
    var d = document.getElementById('city_dropdown');
    if (d) d.style.display = 'none';
}

function clearCityInput() {
    document.getElementById('city_name').value = '';
    hideCityDrop();
    updateDistanceHint();
}

function selectDistance(dist, btn) {
    document.querySelectorAll('.distance-pill-group .btn-pill').forEach(function(b) {
        b.style.borderColor = '#E2E8F0'; b.style.background = '#FFFFFF'; b.style.color = '#475569';
    });
    btn.style.borderColor = '#2563EB'; btn.style.background = '#EFF6FF'; btn.style.color = '#2563EB';
    document.getElementById('preferred_job_distance').value = dist;
    document.getElementById('distance_error').style.display = 'none';
    updateDistanceHint();
}

function updateDistanceHint() {
    var city = document.getElementById('city_name').value || 'your location';
    var dist = document.getElementById('preferred_job_distance').value || '';
    var hint = document.getElementById('distanceHintText');
    if (hint) {
        if (!dist) {
            hint.innerText = 'Select a city and distance to see your job reach';
        } else if (dist === 'Anywhere') {
            hint.innerText = 'We will show remote and all-India jobs for ' + city;
        } else {
            hint.innerText = 'We will show jobs within ' + dist + ' from ' + city;
        }
    }
}

/* ═══════════════════════════════════════════════════════
   STEP 4: QUALIFICATION
═══════════════════════════════════════════════════════ */
function selectQualification(val, btn) {
    document.querySelectorAll('#screen_step_4 .btn-grid-pill').forEach(function(b) {
        b.style.borderColor = '#E2E8F0'; b.style.background = '#FFFFFF'; b.style.color = '#334155';
    });
    btn.style.borderColor = '#2563EB'; btn.style.background = '#EFF6FF'; btn.style.color = '#2563EB';
    document.getElementById('highest_qualification').value = val;
    document.getElementById('qual_error').style.display = 'none';
    eduContext.qualification = val;

    // Reset downstream
    eduContext.course = '';
    document.getElementById('course_degree').value = '';
    document.getElementById('course_type').value   = '';
    document.getElementById('course_grid').innerHTML = '<div style="color:#94A3B8;font-size:13.5px;padding:12px;grid-column:1/-1;text-align:center;"><i class="fa fa-spinner fa-spin"></i> Loading courses...</div>';
    document.getElementById('course_type_list').innerHTML = '<div style="color:#94A3B8;font-size:13.5px;padding:12px;text-align:center;"><i class="fa fa-spinner fa-spin"></i> Loading...</div>';

    // Pre-fetch courses so step 5 is ready instantly
    loadCoursesForQual(val, false);
}

function loadCoursesForQual(qual, autoSelect) {
    $.getJSON('{{ route("api.onboarding.courses") }}', { qualification: qual }, function(res) {
        renderCourseGrid(res.courses || [], res.course_types || [], autoSelect);
        eduContext.courseTypes = res.course_types || [];
    }).fail(function() {
        document.getElementById('course_grid').innerHTML = '<div style="color:#EF4444;font-size:13px;padding:12px;grid-column:1/-1;">Could not load courses. Please try again.</div>';
    });
}

function renderCourseGrid(courses, courseTypes, autoSelect) {
    var grid = document.getElementById('course_grid');
    var savedDegree = '{{ $user->course_degree }}' || eduContext.course;
    grid.innerHTML = '';
    if (!courses || courses.length === 0) {
        grid.innerHTML = '<div style="color:#94A3B8;font-size:13.5px;padding:12px;grid-column:1/-1;">No courses found for this qualification.</div>';
        return;
    }
    courses.forEach(function(course) {
        var isActive = (savedDegree === course);
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-grid-pill' + (isActive ? ' active' : '');
        btn.textContent = course;
        btn.style.cssText = 'padding:12px 14px;border-radius:12px;font-size:13.5px;font-weight:700;' +
            'border:1.5px solid ' + (isActive ? '#2563EB' : '#E2E8F0') + ';' +
            'background:' + (isActive ? '#EFF6FF' : '#FFFFFF') + ';' +
            'color:' + (isActive ? '#2563EB' : '#334155') + ';cursor:pointer;text-align:center;transition:all 0.15s ease;';
        btn.onclick = function() { selectDegree(course, btn, courseTypes); };
        grid.appendChild(btn);
        if (isActive) {
            eduContext.course = course;
        }
    });
    // Store course types for step 6
    eduContext.courseTypes = courseTypes;
    if (autoSelect && courses.length === 1) { selectDegree(courses[0], grid.firstChild, courseTypes); }
}

/* ═══════════════════════════════════════════════════════
   STEP 5: COURSE / DEGREE
═══════════════════════════════════════════════════════ */
function selectDegree(val, btn, courseTypesArr) {
    document.querySelectorAll('#course_grid .btn-grid-pill').forEach(function(b) {
        b.style.borderColor = '#E2E8F0'; b.style.background = '#FFFFFF'; b.style.color = '#334155';
    });
    btn.style.borderColor = '#2563EB'; btn.style.background = '#EFF6FF'; btn.style.color = '#2563EB';
    document.getElementById('course_degree').value = val;
    document.getElementById('degree_error').style.display = 'none';
    eduContext.course = val;

    // Update course types list immediately
    var ctArr = courseTypesArr || eduContext.courseTypes || ['Full Time', 'Part Time', 'Distance Learning', 'Correspondence'];
    renderCourseTypes(ctArr);

    // Pre-fetch specializations for step 7
    loadSpecsForCourse(val);
}

/* ═══════════════════════════════════════════════════════
   STEP 6: COURSE TYPE
═══════════════════════════════════════════════════════ */
function loadCourseTypes(course, autoSelect) {
    var ctArr = eduContext.courseTypes;
    if (ctArr && ctArr.length) {
        renderCourseTypes(ctArr);
    } else {
        $.getJSON('{{ route("api.onboarding.courses") }}', { qualification: eduContext.qualification }, function(res) {
            eduContext.courseTypes = res.course_types || ['Full Time', 'Part Time'];
            renderCourseTypes(eduContext.courseTypes);
        }).fail(function() {
            renderCourseTypes(['Full Time', 'Part Time', 'Distance Learning', 'Correspondence']);
        });
    }
}

function renderCourseTypes(types) {
    var list       = document.getElementById('course_type_list');
    var savedCType = '{{ $user->course_type }}' || document.getElementById('course_type').value;
    list.innerHTML = '';
    types.forEach(function(ctItem) {
        var isActive = (savedCType === ctItem);
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-list-pill' + (isActive ? ' active' : '');
        btn.style.cssText = 'padding:14px 16px;border-radius:12px;font-size:14px;font-weight:700;' +
            'border:1.5px solid ' + (isActive ? '#2563EB' : '#E2E8F0') + ';' +
            'background:' + (isActive ? '#EFF6FF' : '#FFFFFF') + ';' +
            'color:' + (isActive ? '#2563EB' : '#334155') + ';cursor:pointer;' +
            'text-align:left;display:flex;justify-content:space-between;align-items:center;transition:all 0.15s ease;';
        btn.innerHTML = '<span>' + ctItem + '</span>' +
            '<span class="pill-check" style="font-size:13px;font-weight:bold;color:#2563EB;display:' + (isActive ? 'inline' : 'none') + ';">&#10003;</span>';
        btn.onclick = function() { selectCourseType(ctItem, btn); };
        list.appendChild(btn);
    });
}

function selectCourseType(val, btn) {
    document.querySelectorAll('#course_type_list .btn-list-pill').forEach(function(b) {
        b.style.borderColor = '#E2E8F0'; b.style.background = '#FFFFFF'; b.style.color = '#334155';
        var chk = b.querySelector('.pill-check'); if (chk) chk.style.display = 'none';
    });
    btn.style.borderColor = '#2563EB'; btn.style.background = '#EFF6FF'; btn.style.color = '#2563EB';
    var chk = btn.querySelector('.pill-check'); if (chk) chk.style.display = 'inline';
    document.getElementById('course_type').value = val;
    document.getElementById('ctype_error').style.display = 'none';
}

/* ═══════════════════════════════════════════════════════
   STEP 7: SPECIALIZATION (Multi-select)
═══════════════════════════════════════════════════════ */
function loadSpecsForCourse(course) {
    if (!course) { renderSpecSuggestions([]); return; }
    $.getJSON('{{ route("api.onboarding.specializations") }}', { course: course }, function(res) {
        renderSpecSuggestions(res.specializations || []);
    }).fail(function() { renderSpecSuggestions([]); });
}

function renderSpecSuggestions(specs) {
    var cloud = document.getElementById('suggested_specs_cloud');
    cloud.innerHTML = '';
    if (!specs || specs.length === 0) {
        cloud.innerHTML = '<span style="color:#94A3B8;font-size:13px;">No suggestions for this course.</span>';
        return;
    }
    specs.forEach(function(sp) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-chip';
        btn.textContent = '+ ' + sp;
        btn.style.cssText = 'background:#FFFFFF;border:1px solid #CBD5E1;color:#334155;font-size:12.5px;font-weight:600;padding:5px 12px;border-radius:20px;cursor:pointer;transition:all 0.15s ease;';
        btn.onclick = function() { addSpecChip(sp); };
        cloud.appendChild(btn);
    });
}

function handleSpecKeyDown(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        var inp = document.getElementById('spec_search_input');
        if (inp.value.trim()) { addSpecChip(inp.value.trim()); inp.value = ''; }
    }
}

function addSpecChip(specName) {
    var cloud = document.getElementById('selected_specs_cloud');
    var existing = cloud.querySelectorAll('[data-spec]');
    for (var i = 0; i < existing.length; i++) {
        if (existing[i].getAttribute('data-spec').toLowerCase() === specName.toLowerCase()) return;
    }
    var span = document.createElement('span');
    span.className = 'selected-tag-chip';
    span.setAttribute('data-spec', specName);
    span.innerHTML = specName + ' <i class="fa fa-times" onclick="removeSpecChip(\'' + specName.replace(/'/g, "\\'") + '\', this)"></i>';
    cloud.appendChild(span);
}

function removeSpecChip(specName, icon) {
    var chip = icon.closest('.selected-tag-chip');
    if (chip) chip.remove();
}

function getSelectedSpecs() {
    var specs = [];
    document.querySelectorAll('#selected_specs_cloud [data-spec]').forEach(function(el) {
        specs.push(el.getAttribute('data-spec'));
    });
    return specs;
}

/* ═══════════════════════════════════════════════════════
   STEP 8: OFFICIAL INSTITUTION SEARCH & MANUAL FALLBACK
═══════════════════════════════════════════════════════ */
var instTimer = null;
var lastInstQuery = '';

function onInstitutionInput(val) {
    clearTimeout(instTimer);
    document.getElementById('inst_error').style.display = 'none';
    lastInstQuery = val.trim();
    if (val.trim().length < 2) { 
        hideInstDrop(); 
        return; 
    }
    instTimer = setTimeout(function() { fetchInstitutions(val.trim()); }, 250);
}

function fetchInstitutions(q) {
    $.getJSON('{{ route("api.onboarding.institutions") }}', { q: q }, function(data) {
        var drop = document.getElementById('institution_dropdown');
        drop.innerHTML = '';
        
        if (data && data.length > 0) {
            data.forEach(function(inst) {
                var item = document.createElement('div');
                item.className = 'autocomplete-item';
                item.style.cssText = 'padding: 10px 14px; border-bottom: 1px solid #F1F5F9; cursor: pointer; text-align: left; transition: background 0.1s;';
                
                var locationText = [inst.city, inst.state].filter(Boolean).join(', ');
                var typeBadge = inst.type ? '<span style="color: #2563EB; font-weight: 600; font-size: 11.5px; background: #EFF6FF; padding: 2px 7px; border-radius: 4px; margin-left: 6px;">' + inst.type + '</span>' : '';
                
                item.innerHTML = '<div style="font-weight: 700; color: #0F172A; font-size: 13.5px; line-height: 1.3;">' + inst.name + '</div>' +
                    '<div style="font-size: 12px; color: #64748B; margin-top: 3px; display: flex; align-items: center; flex-wrap: wrap;">' +
                    '<i class="fa fa-map-marker" style="color: #EF4444; font-size: 11px; margin-right: 4px;"></i> ' + (locationText || 'India') +
                    typeBadge +
                    '</div>';
                
                item.onclick = function() {
                    selectOfficialInstitution(inst);
                };
                drop.appendChild(item);
            });
        }
        
        // Manual Entry Fallback at the bottom of dropdown
        var manualDiv = document.createElement('div');
        manualDiv.style.cssText = 'padding: 11px 14px; background: #F8FAFC; border-top: 1.5px solid #E2E8F0; display: flex; align-items: center; justify-content: space-between; gap: 8px;';
        manualDiv.innerHTML = '<span style="font-size: 12px; color: #64748B; font-weight: 600;">Can\'t find your college or university?</span>' +
            '<button type="button" class="btn-manual-add" onclick="setManualInstitution(\'' + q.replace(/'/g, "\\'") + '\')" style="background: #2563EB; color: #FFFFFF; font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 8px; border: none; cursor: pointer;">+ Add manually</button>';
        drop.appendChild(manualDiv);
        
        drop.style.display = 'block';
    }).fail(function() {
        hideInstDrop();
    });
}

function selectOfficialInstitution(inst) {
    document.getElementById('institution_name').value = inst.name;
    document.getElementById('institution_id').value = inst.id;
    document.getElementById('institution_type').value = 'official';
    document.getElementById('institution_verification_status').value = inst.verification_status || 'verified';
    hideInstDrop();
    
    var badge = document.getElementById('inst_status_badge');
    if (badge) {
        badge.style.display = 'flex';
        badge.style.color = '#03855c';
        badge.innerHTML = '<i class="fa fa-check-circle"></i> Officially Recognized Institution (' + (inst.type || 'Recognized') + ' • UGC/AISHE)';
    }
}

function setManualInstitution(customName) {
    var finalName = customName || document.getElementById('institution_name').value.trim() || lastInstQuery;
    if (!finalName) {
        document.getElementById('institution_name').focus();
        return;
    }
    document.getElementById('institution_name').value = finalName;
    document.getElementById('institution_id').value = '';
    document.getElementById('institution_type').value = 'manual';
    document.getElementById('institution_verification_status').value = 'unverified';
    hideInstDrop();
    
    var badge = document.getElementById('inst_status_badge');
    if (badge) {
        badge.style.display = 'flex';
        badge.style.color = '#D97706';
        badge.innerHTML = '<i class="fa fa-info-circle"></i> Custom / Unverified Institution (Will be saved as entered)';
    }
}

function clearInstitutionInput() {
    document.getElementById('institution_name').value = '';
    document.getElementById('institution_id').value = '';
    document.getElementById('institution_type').value = 'official';
    document.getElementById('institution_verification_status').value = 'verified';
    var badge = document.getElementById('inst_status_badge');
    if (badge) badge.style.display = 'none';
    hideInstDrop();
}

function hideInstDrop() {
    var d = document.getElementById('institution_dropdown');
    if (d) d.style.display = 'none';
}

/* ═══════════════════════════════════════════════════════
   STEP 10: SKILLS (Dynamic suggestions)
═══════════════════════════════════════════════════════ */
function loadSkillSuggestions() {
    var course = eduContext.course || '';
    var specs  = getSelectedSpecs();
    var spec   = specs.length ? specs[0] : '';
    $.getJSON('{{ route("api.onboarding.skill_suggestions") }}', { course: course, specialization: spec }, function(res) {
        renderSkillSuggestions(res.skills || []);
    }).fail(function() { renderSkillSuggestions([]); });
}

function renderSkillSuggestions(skills) {
    var cloud = document.getElementById('suggested_skills_cloud');
    cloud.innerHTML = '';
    if (!skills || skills.length === 0) { return; }
    skills.forEach(function(s) {
        var btn = document.createElement('button');
        btn.type = 'button'; btn.className = 'btn-chip';
        btn.textContent = '+ ' + s;
        btn.style.cssText = 'background:#FFFFFF;border:1px solid #CBD5E1;color:#334155;font-size:12.5px;font-weight:600;padding:5px 12px;border-radius:20px;cursor:pointer;transition:all 0.15s ease;';
        btn.onclick = function() { addSkillChip(s); };
        cloud.appendChild(btn);
    });
}

function handleSkillKeyDown(e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        var inp = document.getElementById('skill_search_input');
        if (inp.value.trim()) { addSkillChip(inp.value.trim()); inp.value = ''; }
    }
}

function addSkillChip(skillName) {
    if (!skillName) return;
    var parts = skillName.split(/[,;\n]+/);
    parts.forEach(function(s) {
        s = s.trim();
        if (!s) return;
        var cloud = document.getElementById('selected_skills_cloud');
        if (!cloud) return;
        var existing = cloud.querySelectorAll('[data-skill]');
        for (var i = 0; i < existing.length; i++) {
            if (existing[i].getAttribute('data-skill').toLowerCase() === s.toLowerCase()) return;
        }
        var span = document.createElement('span');
        span.className = 'selected-tag-chip';
        span.setAttribute('data-skill', s);
        span.innerHTML = s + ' <i class="fa fa-times" onclick="removeSkillChip(\'' + s.replace(/'/g, "\\'") + '\', this)"></i>';
        cloud.appendChild(span);
    });
}

function removeSkillChip(skillName, icon) {
    var chip = icon.closest('.selected-tag-chip');
    if (chip) chip.remove();
}

function getSelectedSkills() {
    var skills = [];
    document.querySelectorAll('#selected_skills_cloud [data-skill]').forEach(function(el) {
        var s = el.getAttribute('data-skill');
        if (s && s.trim()) skills.push(s.trim());
    });
    return skills;
}

/* ═══════════════════════════════════════════════════════
   STEP 11: ROLES (Dynamic suggestions)
═══════════════════════════════════════════════════════ */
function loadRoleSuggestions() {
    var course = eduContext.course || '';
    var specs  = getSelectedSpecs();
    var spec   = specs.length ? specs[0] : '';
    $.getJSON('{{ route("api.onboarding.role_suggestions") }}', { course: course, specialization: spec }, function(res) {
        renderRoleSuggestions(res.roles || []);
    }).fail(function() { renderRoleSuggestions([]); });
}

function renderRoleSuggestions(roles) {
    var cloud = document.getElementById('suggested_roles_cloud');
    cloud.innerHTML = '';
    if (!roles || roles.length === 0) { return; }
    roles.forEach(function(r) {
        var btn = document.createElement('button');
        btn.type = 'button'; btn.className = 'btn-chip';
        btn.textContent = '+ ' + r;
        btn.style.cssText = 'background:#FFFFFF;border:1px solid #CBD5E1;color:#334155;font-size:12.5px;font-weight:600;padding:5px 12px;border-radius:20px;cursor:pointer;transition:all 0.15s ease;';
        btn.onclick = function() { addRoleChip(r); };
        cloud.appendChild(btn);
    });
}

function handleRoleKeyDown(e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        var inp = document.getElementById('role_search_input');
        if (inp.value.trim()) { addRoleChip(inp.value.trim()); inp.value = ''; }
    }
}

function addRoleChip(roleName) {
    if (!roleName) return;
    var parts = roleName.split(/[,;\n]+/);
    parts.forEach(function(r) {
        r = r.trim();
        if (!r) return;
        var cloud = document.getElementById('selected_roles_cloud');
        if (!cloud) return;
        var existing = cloud.querySelectorAll('[data-role]');
        for (var i = 0; i < existing.length; i++) {
            if (existing[i].getAttribute('data-role').toLowerCase() === r.toLowerCase()) return;
        }
        var span = document.createElement('span');
        span.className = 'selected-tag-chip';
        span.setAttribute('data-role', r);
        span.innerHTML = r + ' <i class="fa fa-times" onclick="removeRoleChip(\'' + r.replace(/'/g, "\\'") + '\', this)"></i>';
        cloud.appendChild(span);
    });
}

function removeRoleChip(roleName, icon) {
    var chip = icon.closest('.selected-tag-chip');
    if (chip) chip.remove();
}

function getSelectedRoles() {
    var roles = [];
    document.querySelectorAll('#selected_roles_cloud [data-role]').forEach(function(el) {
        var r = el.getAttribute('data-role');
        if (r && r.trim()) roles.push(r.trim());
    });
    return roles;
}

/* ═══════════════════════════════════════════════════════
   AJAX SUBMIT
═══════════════════════════════════════════════════════ */
function showStepError(msg) {
    if (!msg) return;
    // Simple inline toast
    var t = document.createElement('div');
    t.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);' +
        'background:#EF4444;color:#FFF;padding:12px 24px;border-radius:12px;font-size:14px;' +
        'font-weight:700;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,0.18);';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 3000);
}

function submitCurrentStep(stepNum) {
    var payload = { _token: '{{ csrf_token() }}', step: stepNum };

    // Client-side validation & data collection
    if (stepNum === 2) {
        var ptype = document.querySelector('input[name="profile_type"]:checked');
        payload.profile_type = ptype ? ptype.value : 'fresher';

    } else if (stepNum === 3) {
        var cityVal = document.getElementById('city_name').value.trim();
        var distVal = document.getElementById('preferred_job_distance').value;
        if (!cityVal) {
            document.getElementById('city_error').style.display = 'block';
            document.getElementById('city_name').focus();
            return;
        }
        if (!distVal) {
            document.getElementById('distance_error').style.display = 'block';
            return;
        }
        document.getElementById('city_error').style.display = 'none';
        document.getElementById('distance_error').style.display = 'none';
        payload.city_name = cityVal;
        payload.preferred_job_distance = distVal;

    } else if (stepNum === 4) {
        var qual = document.getElementById('highest_qualification').value;
        if (!qual) {
            document.getElementById('qual_error').style.display = 'block';
            return;
        }
        payload.highest_qualification = qual;

    } else if (stepNum === 5) {
        var deg = document.getElementById('course_degree').value;
        if (!deg) {
            document.getElementById('degree_error').style.display = 'block';
            return;
        }
        payload.course_degree = deg;

    } else if (stepNum === 6) {
        var ct = document.getElementById('course_type').value;
        if (!ct) {
            document.getElementById('ctype_error').style.display = 'block';
            return;
        }
        payload.course_type = ct;

    } else if (stepNum === 7) {
        var specs = getSelectedSpecs();
        // Also grab input value if there's something typed
        var specInput = document.getElementById('spec_search_input').value.trim();
        if (specInput) { addSpecChip(specInput); document.getElementById('spec_search_input').value = ''; specs = getSelectedSpecs(); }
        if (!specs.length) {
            document.getElementById('spec_error').style.display = 'block';
            return;
        }
        document.getElementById('spec_error').style.display = 'none';
        payload.specialization = specs;
        // Update eduContext for downstream suggestions
        eduContext.specialization = specs[0];

    } else if (stepNum === 8) {
        var instName = document.getElementById('institution_name').value.trim();
        if (!instName) {
            document.getElementById('inst_error').style.display = 'block';
            document.getElementById('institution_name').focus();
            return;
        }
        document.getElementById('inst_error').style.display = 'none';
        payload.institution_name = instName;
        payload.institution_id = document.getElementById('institution_id').value;
        payload.institution_type = document.getElementById('institution_type').value || 'official';
        payload.institution_verification_status = document.getElementById('institution_verification_status').value || 'verified';

    } else if (stepNum === 9) {
        payload.degree_start_year  = document.getElementById('degree_start_year').value;
        payload.degree_end_year    = document.getElementById('degree_end_year').value;
        payload.degree_percentage  = document.getElementById('degree_percentage').value;

    } else if (stepNum === 10) {
        // Also grab typed skill and split on commas/semicolons
        var skillInput = document.getElementById('skill_search_input').value.trim();
        if (skillInput) {
            addSkillChip(skillInput);
            document.getElementById('skill_search_input').value = '';
        }
        payload.skills = getSelectedSkills();

    } else if (stepNum === 11) {
        var roleInput = document.getElementById('role_search_input').value.trim();
        if (roleInput) {
            addRoleChip(roleInput);
            document.getElementById('role_search_input').value = '';
        }
        payload.preferred_job_roles = getSelectedRoles();
        var cname = document.getElementById('company_name');
        if (cname && cname.value) {
            payload.company_name = cname.value;
            payload.job_title    = document.getElementById('job_title').value;
            payload.is_currently_working = document.getElementById('is_currently_working').checked ? 1 : 0;
        }
    }

    $.ajax({
        url     : '{{ route("onboarding.save.step") }}',
        type    : 'POST',
        data    : payload,
        dataType: 'json',
        traditional: true,   // properly serialize arrays
        success : function(res) {
            if (res.status === 'success') {
                if (res.redirect_url) {
                    window.location.href = res.redirect_url;
                    return;
                }
                showStep(res.next_step);
            } else {
                showStepError(res.message || 'Something went wrong.');
            }
        },
        error   : function(xhr) {
            var msg = '';
            try { msg = JSON.parse(xhr.responseText).message; } catch(e) {}
            if (msg) { showStepError(msg); } else { showStep(stepNum + 1); }
        }
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#city_name') && !e.target.closest('#city_dropdown')) hideCityDrop();
    if (!e.target.closest('#institution_name') && !e.target.closest('#institution_dropdown')) hideInstDrop();
});
</script>
@endpush
