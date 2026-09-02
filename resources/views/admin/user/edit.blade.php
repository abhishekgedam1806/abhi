@extends('admin.layouts.admin_layout')
@section('content')
<style>
/* Modern User Edit Tabs & Header */
.adm-user-header {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 18px 24px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.adm-user-left {
    display: flex;
    align-items: center;
    gap: 16px;
}
.adm-user-avatar-top {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: #EFF6FF;
    color: #2563EB;
    border: 2px solid #DBEAFE;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 800;
    overflow: hidden;
    flex-shrink: 0;
}
.adm-user-avatar-top img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.adm-user-title {
    font-size: 20px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.adm-user-sub {
    font-size: 13px;
    color: #64748B;
    margin: 0;
}

/* Modern Tab Navigation */
.adm-modern-nav-tabs {
    border-bottom: 1.5px solid #E2E8F0 !important;
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-bottom: 24px;
    background: #FFFFFF;
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid #E2E8F0;
}
.adm-modern-nav-tabs > li {
    margin-bottom: 0;
}
.adm-modern-nav-tabs > li > a {
    border: none !important;
    border-radius: 8px !important;
    padding: 9px 16px !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    color: #64748B !important;
    background: transparent !important;
    display: flex;
    align-items: center;
    gap: 7px;
    transition: all 0.15s ease;
}
.adm-modern-nav-tabs > li > a:hover {
    color: #0F172A !important;
    background: #F1F5F9 !important;
}
.adm-modern-nav-tabs > li.active > a,
.adm-modern-nav-tabs > li.active > a:focus,
.adm-modern-nav-tabs > li.active > a:hover {
    color: #FFFFFF !important;
    background: #2563EB !important;
    box-shadow: 0 2px 8px rgba(37,99,235,0.25);
}
</style>

<div class="page-content-wrapper"> 
    <div class="page-content" style="background: #F8FAFC; min-height: 100vh;"> 
        <!-- Breadcrumb Bar -->
        <div class="page-bar" style="background: #FFFFFF; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <ul class="page-breadcrumb">
                <li> <a href="{{ route('admin.home') }}" style="color:#64748B;">Home</a> <i class="fa fa-circle" style="font-size:6px; color:#CBD5E1;"></i> </li>
                <li> <a href="{{ route('list.users') }}" style="color:#64748B;">Users</a> <i class="fa fa-circle" style="font-size:6px; color:#CBD5E1;"></i> </li>
                <li> <span style="font-weight:700; color:#0F172A;">Edit Candidate Profile</span> </li>
            </ul>
        </div>

        @include('flash::message')

        {{-- Top Profile Header Banner --}}
        <div class="adm-user-header">
            <div class="adm-user-left">
                <div class="adm-user-avatar-top">
                    @if(!empty($user->image))
                        <img src="{{ asset('user_images/'.$user->image) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}
                    @endif
                </div>
                <div>
                    <h1 class="adm-user-title">
                        {{ $user->getName() ?: 'Candidate Profile' }}
                        @if($user->is_active)
                            <span class="label label-sm label-success" style="font-size: 11px; padding: 3px 8px; border-radius: 10px;">Active</span>
                        @else
                            <span class="label label-sm label-danger" style="font-size: 11px; padding: 3px 8px; border-radius: 10px;">In-Active</span>
                        @endif
                        @if($user->verified)
                            <span class="label label-sm label-info" style="font-size: 11px; padding: 3px 8px; border-radius: 10px;"><i class="fa fa-shield"></i> Verified</span>
                        @endif
                        @if($user->is_immediate_available)
                            <span class="label label-sm label-warning" style="font-size: 11px; padding: 3px 8px; border-radius: 10px;"><i class="fa fa-bolt"></i> Immediate Available</span>
                        @endif
                    </h1>
                    <p class="adm-user-sub">
                        <i class="fa fa-envelope-o"></i> {{ $user->email }}
                        @if(!empty($user->phone))
                            • <i class="fa fa-phone"></i> {{ $user->phone }}
                        @endif
                        @if(!empty($user->getLocation()))
                            • <i class="fa fa-map-marker"></i> {{ $user->getLocation() }}
                        @endif
                    </p>
                </div>
            </div>
            <div>
                <a href="{{ route('list.users') }}" class="btn btn-default" style="border-radius: 8px; font-weight: 600; padding: 8px 16px; border: 1.5px solid #CBD5E1; color: #475569;">
                    <i class="fa fa-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                {{-- Modern Nav Tabs --}}
                <ul class="nav nav-tabs adm-modern-nav-tabs">              
                    <li class="active"> <a href="#Details" data-toggle="tab" aria-expanded="false"><i class="fa fa-user"></i> Personal Details</a> </li>
                    <li><a href="#Summary" data-toggle="tab" aria-expanded="false"><i class="fa fa-file-text-o"></i> Profile Summary</a></li>
                    <li><a href="#CV" data-toggle="tab" aria-expanded="false"><i class="fa fa-file-pdf-o"></i> Resumes (CV)</a></li>
                    <li><a href="#Projects" data-toggle="tab" aria-expanded="false"><i class="fa fa-rocket"></i> Projects</a></li>
                    <li><a href="#Experience" data-toggle="tab" aria-expanded="false"><i class="fa fa-briefcase"></i> Experience</a></li>
                    <li><a href="#Education" data-toggle="tab" aria-expanded="false"><i class="fa fa-graduation-cap"></i> Education</a></li>
                    <li><a href="#Skills" data-toggle="tab" aria-expanded="false"><i class="fa fa-bolt"></i> Skills</a></li>
                    <li><a href="#Languages" data-toggle="tab" aria-expanded="false"><i class="fa fa-language"></i> Languages</a></li>
                </ul>

                <div class="tab-content">              
                    <div class="tab-pane fade active in" id="Details"> @include('admin.user.forms.form') </div>
                    <div class="tab-pane fade" id="Summary"> @include('admin.user.forms.summary') </div>
                    <div class="tab-pane fade" id="CV"> @include('admin.user.forms.cv.cvs') </div>
                    <div class="tab-pane fade" id="Projects"> @include('admin.user.forms.project.projects') </div>
                    <div class="tab-pane fade" id="Experience"> @include('admin.user.forms.experience.experience') </div>
                    <div class="tab-pane fade" id="Education"> @include('admin.user.forms.education.education') </div>
                    <div class="tab-pane fade" id="Skills"> @include('admin.user.forms.skill.skills') </div>
                    <div class="tab-pane fade" id="Languages"> @include('admin.user.forms.language.languages') </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection