@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <h1 style="font-size:20px;font-weight:800;margin-bottom:20px;">Edit Business Category: {{ $category->name }}</h1>
        <div class="portlet light bordered" style="border-radius:12px;max-width:700px;">
            <div class="portlet-body form">
                <form action="{{ route('admin.update.business_category', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-body">
                        <div class="form-group">
                            <label style="font-weight:600;">Category Name *</label>
                            <input type="text" name="name" class="form-control" required value="{{ $category->name }}">
                        </div>
                        <div class="form-group">
                            <label style="font-weight:600;">FontAwesome Icon Class</label>
                            <input type="text" name="icon" class="form-control" value="{{ $category->icon }}">
                        </div>
                        <div class="form-group">
                            <label style="font-weight:600;">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" name="is_featured" value="1" {{ $category->is_featured ? 'checked' : '' }}> Featured on Homepage</label>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Category</button>
                        <a href="{{ route('admin.list.business_categories') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
