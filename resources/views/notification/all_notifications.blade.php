@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title' => __('Notification Center')])
<!-- Inner Page Title end -->

<div class="listpgWraper" style="background: #F8FAFC; padding: 40px 0 70px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                
                {{-- Top Card Header --}}
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 24px; margin-bottom: 20px; box-shadow: 0 2px 12px rgba(15,23,42,0.04); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <div>
                        <h2 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fa fa-bell" style="color: #2563EB;"></i> {{ __('All Notifications') }}
                        </h2>
                        <p style="font-size: 13.5px; color: #64748B; margin: 4px 0 0 0;">
                            {{ __('Stay updated with real-time job applications, messages, and alerts.') }}
                        </p>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px;">
                        <a href="{{ route('notification.all', ['filter' => 'all']) }}" class="btn btn-sm {{ $filter !== 'unread' ? 'btn-primary' : 'btn-outline-secondary' }}" style="font-weight: 700; border-radius: 8px; font-size: 12.5px; padding: 6px 14px;">
                            {{ __('All') }}
                        </a>
                        <a href="{{ route('notification.all', ['filter' => 'unread']) }}" class="btn btn-sm {{ $filter === 'unread' ? 'btn-primary' : 'btn-outline-secondary' }}" style="font-weight: 700; border-radius: 8px; font-size: 12.5px; padding: 6px 14px;">
                            {{ __('Unread') }} ({{ $unreadCount }})
                        </a>
                        @if($unreadCount > 0)
                        <button type="button" onclick="markAllNotificationsRead()" class="btn btn-sm btn-outline-primary" style="font-weight: 700; border-radius: 8px; font-size: 12.5px; padding: 6px 14px;">
                            <i class="fa fa-check-circle"></i> {{ __('Mark all read') }}
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Notification List --}}
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(15,23,42,0.04);">
                    @if(isset($notifications) && $notifications->count() > 0)
                        <div class="notification-stream-list">
                            @foreach($notifications as $notif)
                                <a href="{{ route('notification.read', $notif->id) }}" class="notif-item-row {{ !$notif->is_read ? 'unread-bg' : '' }}" style="display: flex; align-items: flex-start; gap: 16px; padding: 18px 22px; border-bottom: 1px solid #F1F5F9; text-decoration: none; color: inherit; transition: all 0.15s ease; position: relative;">
                                    
                                    {{-- Unread Indicator Bar --}}
                                    @if(!$notif->is_read)
                                        <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: #2563EB;"></div>
                                    @endif

                                    {{-- Icon Circle --}}
                                    <div style="width: 44px; height: 44px; border-radius: 12px; background: {{ $notif->color ? $notif->color . '18' : '#EFF6FF' }}; color: {{ $notif->color ?: '#2563EB' }}; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                        <i class="fa {{ $notif->icon ?: 'fa-bell' }}"></i>
                                    </div>

                                    {{-- Text Content --}}
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 3px;">
                                            <h3 style="font-size: 14.5px; font-weight: {{ !$notif->is_read ? '800' : '600' }}; color: #0F172A; margin: 0;">
                                                {{ $notif->title }}
                                            </h3>
                                            <span style="font-size: 12px; color: #94A3B8; white-space: nowrap;">
                                                <i class="fa fa-clock-o"></i> {{ $notif->created_at ? $notif->created_at->diffForHumans() : '' }}
                                            </span>
                                        </div>
                                        <p style="font-size: 13.5px; color: #475569; margin: 0; line-height: 1.45;">
                                            {{ $notif->message }}
                                        </p>
                                    </div>

                                    {{-- Arrow Action --}}
                                    <div style="color: #94A3B8; font-size: 14px; align-self: center;">
                                        <i class="fa fa-angle-right"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div style="padding: 16px 22px; background: #F8FAFC; border-top: 1px solid #E2E8F0; display: flex; justify-content: center;">
                            {!! $notifications->appends(['filter' => $filter])->render() !!}
                        </div>
                    @else
                        <div style="padding: 60px 24px; text-align: center; color: #94A3B8;">
                            <div style="width: 64px; height: 64px; border-radius: 50%; background: #F1F5F9; display: inline-flex; align-items: center; justify-content: center; font-size: 28px; color: #CBD5E1; margin-bottom: 14px;">
                                <i class="fa fa-bell-slash-o"></i>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 700; color: #475569; margin-bottom: 4px;">
                                {{ __('No notifications found') }}
                            </h3>
                            <p style="font-size: 13.5px; color: #64748B; margin: 0;">
                                {{ __('You are all caught up! New notifications will appear here in real-time.') }}
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.notif-item-row:hover {
    background: #F8FAFC !important;
}
.notif-item-row.unread-bg {
    background: #F0F7FF;
}
</style>

@push('scripts')
<script>
function markAllNotificationsRead() {
    $.ajax({
        url: "{{ route('notification.mark-all-read') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(res) {
            window.location.reload();
        }
    });
}
</script>
@endpush
@endsection
