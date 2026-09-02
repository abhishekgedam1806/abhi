@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <h1 style="font-size:20px;font-weight:800;margin-bottom:20px;">Add New Business</h1>
        <div class="portlet light bordered" style="border-radius:12px;max-width:850px;">
            <div class="portlet-body form">
                <form action="{{ route('admin.store.business') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">Business Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">Category *</label>
                                <select name="category_id" class="form-control" required>
                                    @foreach($categories as $cId => $cName)
                                    <option value="{{ $cId }}">{{ $cName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">Phone Number *</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">WhatsApp Number</label>
                                <input type="text" name="whatsapp_number" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">Website</label>
                                <input type="url" name="website" class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <label style="font-weight:600;">Address Line 1 *</label>
                                <input type="text" name="address_line1" class="form-control" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">Area / Locality</label>
                                <input type="text" name="area_locality" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">City *</label>
                                <select name="city_id" class="form-control" required>
                                    @foreach($cities as $cId => $cName)
                                    <option value="{{ $cId }}">{{ $cName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">Latitude</label>
                                <input type="text" name="latitude" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">Longitude</label>
                                <input type="text" name="longitude" class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <label style="font-weight:600;">Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">Verification Status</label>
                                <select name="verification_status" class="form-control">
                                    <option value="verified">Verified</option>
                                    <option value="pending">Pending</option>
                                    <option value="unverified">Unverified</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:600;">Featured Listing</label>
                                <select name="is_featured" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Create Business</button>
                        <a href="{{ route('admin.list.businesses') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
