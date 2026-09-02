@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <h1 style="font-size:20px;font-weight:800;margin-bottom:20px;">Add Business Category</h1>
        <div class="portlet light bordered" style="border-radius:12px;max-width:700px;">
            <div class="portlet-body form">
                <form action="{{ route('admin.store.business_category') }}" method="POST">
                    @csrf
                    <div class="form-body">
                        <div class="form-group">
                            <label style="font-weight:600;">Category Name *</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Restaurants & Food">
                        </div>
                        <div class="form-group">
                            <label style="font-weight:600;">FontAwesome Icon Class</label>
                            <input type="text" name="icon" class="form-control" placeholder="e.g. fa-cutlery, fa-laptop, fa-wrench">
                        </div>
                        <div class="form-group">
                            <label style="font-weight:600;">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" name="is_featured" value="1"> Featured on Homepage</label>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Category</button>
                        <a href="{{ route('admin.list.business_categories') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
