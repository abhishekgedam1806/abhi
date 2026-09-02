@extends('admin.layouts.admin_layout')

@section('content')
<style>
.blog-mgmt-wrap {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
    padding: 10px 0 30px 0;
}
.blog-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #94A3B8;
    margin-bottom: 20px;
    font-weight: 500;
}
.blog-breadcrumb a { color: #64748B; text-decoration: none; }
.blog-breadcrumb a:hover { color: #2563EB; }
.blog-breadcrumb i { font-size: 9px; color: #CBD5E1; }

.blog-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 12px;
}
.blog-page-title {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.blog-page-title .title-icon {
    width: 38px;
    height: 38px;
    background: #2563EB;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 17px;
}

.card-box {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 20px 22px;
    margin-bottom: 22px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.table-blog {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.table-blog th {
    background: #F8FAFC;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 1px solid #E2E8F0;
}
.table-blog td {
    padding: 14px 16px;
    border-bottom: 1px solid #F1F5F9;
    font-size: 13.5px;
    vertical-align: middle;
}
.table-blog tr:hover td {
    background: #F8FAFC;
}

/* Status Toggle Button */
.status-toggle-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    cursor: pointer;
    padding: 4px 10px;
    border-radius: 20px;
    outline: none !important;
    transition: all 0.2s ease;
    user-select: none;
}
.status-toggle-btn:hover {
    background: #F1F5F9;
    border-color: #CBD5E1;
}
.toggle-track {
    position: relative;
    display: inline-block;
    width: 32px;
    height: 18px;
    background-color: #CBD5E1;
    border-radius: 20px;
    transition: background-color 0.25s ease;
}
.status-toggle-btn.active .toggle-track {
    background-color: #03855c;
}
.toggle-thumb {
    position: absolute;
    top: 2px;
    left: 2px;
    width: 14px;
    height: 14px;
    background-color: #FFFFFF;
    border-radius: 50%;
    transition: transform 0.25s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.25);
}
.status-toggle-btn.active .toggle-thumb {
    transform: translateX(14px);
}
.toggle-text {
    font-size: 11.5px;
    font-weight: 700;
    color: #64748B;
    min-width: 54px;
    text-align: left;
}
.status-toggle-btn.active .toggle-text {
    color: #03855c;
}

.btn-primary-custom {
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 16px;
    border-radius: 8px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
    transition: background 0.15s ease;
}
.btn-primary-custom:hover {
    background: #1D4ED8;
}

.btn-action-icon {
    width: 32px;
    height: 32px;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #E2E8F0;
    background: #FFFFFF;
    color: #475569;
    font-size: 13px;
    text-decoration: none !important;
    transition: all 0.15s ease;
    cursor: pointer;
}
.btn-action-icon:hover {
    border-color: #CBD5E1;
    background: #F8FAFC;
    color: #0F172A;
}
.btn-action-icon.text-danger:hover {
    background: #FEE2E2;
    border-color: #FECACA;
    color: #DC2626 !important;
}

.badge-cat {
    display: inline-block;
    background: #F1F5F9;
    color: #334155;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
    margin: 2px 2px 2px 0;
}
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="blog-mgmt-wrap">

            <!-- Breadcrumb -->
            <div class="blog-breadcrumb">
                <a href="{{ route('admin.home') }}"><i class="fa fa-home"></i> Dashboard</a>
                <i class="fa fa-chevron-right"></i>
                <a href="{{ route('blog') }}">Manage Blogs</a>
                <i class="fa fa-chevron-right"></i>
                <span style="color: #0F172A; font-weight: 700;">All Blogs</span>
            </div>

            @include('flash::message')
            @if(session()->has('message.content'))
            <div class="alert alert-success fade in" style="border-radius: 8px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {!! session('message.content') !!}
            </div>
            @endif

            <!-- Header Row -->
            <div class="blog-header-row">
                <h1 class="blog-page-title">
                    <span class="title-icon"><i class="fa fa-newspaper-o"></i></span>
                    All Blog Posts & Articles ({{ $blogs->total() }})
                </h1>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ url('/admin/blog_category') }}" class="btn btn-default" style="border-radius: 8px; font-weight: 600; font-size: 13px;">
                        <i class="fa fa-tags"></i> Blog Categories
                    </a>
                    <a href="{{ route('add-new-blog') }}" class="btn-primary-custom">
                        <i class="fa fa-plus"></i> Add New Blog Post
                    </a>
                </div>
            </div>

            <!-- Filters & Search Bar -->
            <div class="card-box" style="padding: 16px 20px;">
                <form action="{{ route('blog') }}" method="GET">
                    <div class="row" style="align-items: center;">
                        <div class="col-md-4 col-sm-6 form-group mb-2">
                            <div class="input-group">
                                <span class="input-group-addon" style="background: #F8FAFC; border-color: #E2E8F0;"><i class="fa fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Search blog title, slug, keyword..." value="{{ request('search') }}" style="border-color: #E2E8F0; border-radius: 0 8px 8px 0; font-size: 13px;">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 form-group mb-2">
                            <select name="category_id" class="form-control" style="border-color: #E2E8F0; border-radius: 8px; font-size: 13px;">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->heading }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6 form-group mb-2">
                            <select name="status" class="form-control" style="border-color: #E2E8F0; border-radius: 8px; font-size: 13px;">
                                <option value="">All Statuses</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Only</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6 form-group mb-2">
                            <select name="lang" class="form-control" style="border-color: #E2E8F0; border-radius: 8px; font-size: 13px;">
                                <option value="">All Languages</option>
                                @foreach($languages as $code => $langName)
                                <option value="{{ $code }}" {{ request('lang') === $code ? 'selected' : '' }}>{{ $langName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 col-sm-12 form-group mb-2" style="text-align: right;">
                            <button type="submit" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; width: 100%; font-weight: 700;">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Blogs Data Table -->
            <div class="card-box" style="padding: 0; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table-blog">
                        <thead>
                            <tr>
                                <th style="width: 70px;">Image</th>
                                <th>Blog Details & Slug</th>
                                <th>Categories</th>
                                <th>Lang</th>
                                <th style="text-align: center; width: 120px;">Status</th>
                                <th style="width: 130px;">Last Updated</th>
                                <th style="text-align: right; width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blogs as $blog)
                            <tr id="blog-row-{{ $blog->id }}">
                                <td>
                                    <div style="width: 54px; height: 38px; border-radius: 6px; overflow: hidden; background: #F1F5F9; border: 1px solid #E2E8F0;">
                                        <img src="{{ $blog->getImageUrl() }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #0F172A; font-size: 14px; margin-bottom: 3px;">
                                        {{ $blog->heading }}
                                    </div>
                                    <div style="font-size: 12px; color: #64748B; display: flex; align-items: center; gap: 6px;">
                                        <code>/blog/{{ $blog->slug }}</code>
                                        @if($blog->is_published)
                                        <a href="{{ url('/blog/' . $blog->slug) }}" target="_blank" title="View Public Post" style="color: #2563EB;"><i class="fa fa-external-link"></i></a>
                                        @endif
                                    </div>
                                    @if(!empty($blog->focus_keyword))
                                    <div style="font-size: 11px; color: #03855c; margin-top: 2px;">
                                        <i class="fa fa-key"></i> Focus: <strong>{{ $blog->focus_keyword }}</strong>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @php $blogCats = $blog->getCategories(); @endphp
                                    @forelse($blogCats as $c)
                                    <span class="badge-cat">{{ $c->heading }}</span>
                                    @empty
                                    <span style="color: #94A3B8; font-size: 12px;">Uncategorized</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span style="font-size: 12px; font-weight: 700; background: #EFF6FF; color: #2563EB; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">{{ $blog->lang ?: 'en' }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="status-toggle-btn {{ $blog->is_published ? 'active' : '' }}" onclick="toggleBlogStatus(this, {{ $blog->id }})" title="Click to toggle Published / Draft">
                                        <span class="toggle-track">
                                            <span class="toggle-thumb"></span>
                                        </span>
                                        <span class="toggle-text">{{ $blog->is_published ? 'Published' : 'Draft' }}</span>
                                    </button>
                                </td>
                                <td style="font-size: 12.5px; color: #64748B;">
                                    {{ !empty($blog->updated_at) ? \Carbon\Carbon::parse($blog->updated_at)->diffForHumans() : '—' }}
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ url('/blog/' . $blog->slug) }}" target="_blank" class="btn-action-icon" title="Preview Public Page">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('edit-blog', $blog->id) }}" class="btn-action-icon" title="Edit Blog Post">
                                        <i class="fa fa-pencil text-primary"></i>
                                    </a>
                                    <button type="button" class="btn-action-icon text-danger" onclick="deleteBlog({{ $blog->id }}, '{{ addslashes($blog->heading) }}')" title="Delete Blog Post">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: #94A3B8; padding: 40px 20px;">
                                    <i class="fa fa-newspaper-o" style="font-size: 36px; margin-bottom: 10px; display: block;"></i>
                                    No blog posts found matching the filter criteria.
                                    <div style="margin-top: 12px;">
                                        <a href="{{ route('add-new-blog') }}" class="btn-primary-custom" style="display: inline-flex;">+ Create New Blog Post</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($blogs->hasPages())
                <div style="padding: 16px 20px; border-top: 1px solid #F1F5F9; display: flex; justify-content: flex-end;">
                    {!! $blogs->appends(request()->query())->links() !!}
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Modal: Delete Confirmation -->
<div class="modal fade" id="modalDeleteBlog" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 1px solid #F1F5F9; padding: 16px 20px;">
                <h5 class="modal-title" style="font-weight: 800; color: #DC2626; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-exclamation-triangle"></i> Confirm Delete Blog Post
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px; font-size: 14px; color: #334155;">
                Are you sure you want to permanently delete: <br>
                <strong id="delete_blog_title" style="color: #0F172A; display: block; margin-top: 6px;"></strong>
                <p style="font-size: 12px; color: #64748B; margin-top: 10px;">This action cannot be undone and will remove the featured image as well.</p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #F1F5F9; padding: 12px 20px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                <button type="button" class="btn btn-danger" id="btnConfirmDelete" style="border-radius: 8px; background: #DC2626; border-color: #DC2626; font-weight: 700;">Delete Permanently</button>
            </div>
        </div>
    </div>
</div>

<script>
var deleteBlogId = null;

function deleteBlog(id, title) {
    deleteBlogId = id;
    $('#delete_blog_title').text('"' + title + '"');
    $('#modalDeleteBlog').modal('show');
}

$('#btnConfirmDelete').on('click', function() {
    if (!deleteBlogId) return;
    var $btn = $(this);
    $btn.prop('disabled', true).text('Deleting...');

    $.ajax({
        url: '{{ url("admin/blog") }}/' + deleteBlogId,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            $('#modalDeleteBlog').modal('hide');
            $('#blog-row-' + deleteBlogId).fadeOut(300, function() { $(this).remove(); });
            deleteBlogId = null;
            $btn.prop('disabled', false).text('Delete Permanently');
        },
        error: function(err) {
            $btn.prop('disabled', false).text('Delete Permanently');
            alert('Failed to delete blog post. Please refresh.');
        }
    });
});

function toggleBlogStatus(btn, id) {
    var $btn = $(btn);
    $btn.prop('disabled', true).css('opacity', '0.6');

    $.ajax({
        url: '{{ url("admin/blog/toggle-status") }}/' + id,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            $btn.prop('disabled', false).css('opacity', '1');
            if (res.is_published) {
                $btn.addClass('active');
                $btn.find('.toggle-text').text('Published');
            } else {
                $btn.removeClass('active');
                $btn.find('.toggle-text').text('Draft');
            }
        },
        error: function(err) {
            $btn.prop('disabled', false).css('opacity', '1');
            alert('Could not update status. Please try again.');
        }
    });
}
</script>
@endsection