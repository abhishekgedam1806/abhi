@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content" style="background: #F8FAFC; min-height: 100vh;"> 
        <!-- BEGIN PAGE HEADER--> 
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar" style="background: #FFFFFF; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
            <ul class="page-breadcrumb">
                <li> <a href="{{ route('admin.home') }}">Home</a> <i class="fa fa-circle"></i> </li>
                <li> <a href="{{ route('list.companies') }}">Companies</a> <i class="fa fa-circle"></i> </li>
                <li> <span>Edit Company</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR --> 
        <!-- END PAGE HEADER--> 
        @include('flash::message')
        <div class="row">
            <div class="col-md-12">
                {!! Form::model($company, array('method' => 'put', 'route' => array('update.company', $company->id), 'class' => 'form', 'files'=>true)) !!}
                {!! Form::hidden('id', $company->id) !!}
                @include('admin.company.forms.form')
                {!! Form::close() !!}
            </div>
        </div>
        <!-- END CONTENT BODY --> 
    </div>
    @endsection