@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Company Messages')])

@push('styles')
<style type="text/css">
    .messageWrap {
        background: #F8FAFC;
        padding: 36px 0 60px;
        min-height: 85vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .chat-main-card {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 640px;
    }
    .chat-layout {
        display: flex;
        flex: 1;
        min-height: 0;
        overflow: hidden;
    }
    .chat-sidebar {
        width: 300px;
        border-right: 1.5px solid #E2E8F0;
        background: #FFFFFF;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        min-height: 0;
    }
    .chat-sidebar-header {
        padding: 14px 18px;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #FFFFFF;
        flex-shrink: 0;
    }
    .chat-list-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 8px;
        list-style: none;
        margin: 0;
        min-height: 0;
    }
    .chat-conversation-item {
        border-radius: 12px;
        margin-bottom: 4px;
        transition: all 0.15s ease;
    }
    .chat-conversation-item.active {
        background: #EFF6FF !important;
        border-left: 3.5px solid #2563EB;
    }
    .chat-conversation-item:hover:not(.active) {
        background: #F8FAFC;
    }
    .chat-conversation-link {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        text-decoration: none;
        gap: 12px;
        color: #0F172A;
    }
    .chat-avatar-box {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        overflow: hidden;
        border: 1.5px solid #E2E8F0;
        background: #F8FAFC;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .chat-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .chat-main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #F8FAFC;
        overflow: hidden;
        min-height: 0;
        position: relative;
    }
    .chat-messages-container {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        list-style: none;
        margin: 0;
    }
    .chat-bubble-received {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        max-width: 80%;
        align-self: flex-start;
    }
    .chat-bubble-received .bubble-content {
        background: #FFFFFF;
        color: #1E293B;
        border: 1.5px solid #E2E8F0;
        border-radius: 16px 16px 16px 4px;
        padding: 10px 16px;
        font-size: 13.5px;
        line-height: 1.5;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .chat-bubble-sent {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        max-width: 80%;
        align-self: flex-end;
        flex-direction: row-reverse;
    }
    .chat-bubble-sent .bubble-content {
        background: #2563EB;
        color: #FFFFFF;
        border-radius: 16px 16px 4px 16px;
        padding: 10px 16px;
        font-size: 13.5px;
        line-height: 1.5;
        box-shadow: 0 3px 12px rgba(37,99,235,0.2);
    }
    .chat-time-label {
        font-size: 11px;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .chat-bubble-received .chat-time-label {
        color: #94A3B8;
    }
    .chat-bubble-sent .chat-time-label {
        color: #DBEAFE;
        justify-content: flex-end;
    }
    .chat-input-bar {
        background: #FFFFFF;
        border-top: 1.5px solid #E2E8F0;
        padding: 14px 20px;
        flex-shrink: 0;
    }
    @media (max-width: 768px) {
        .chat-main-card {
            height: 620px;
        }
        .chat-sidebar {
            width: 100%;
            border-right: none;
            border-bottom: 1.5px solid #E2E8F0;
            max-height: 180px;
        }
        .chat-layout {
            flex-direction: column;
        }
    }
</style>
@endpush

<div class="listpgWraper messageWrap">
    <div class="container">
        <div class="row">
            @include('includes.company_dashboard_menu')
            <div class="col-md-9 col-sm-8">
                <div class="chat-main-card">
                    <div class="chat-layout">
                        <!-- Left Sidebar: Candidates List -->
                        <div class="chat-sidebar">
                            <div class="chat-sidebar-header">
                                <h3 style="font-size: 15px; font-weight: 800; color: #0F172A; margin: 0;">
                                    <i class="fa fa-comments text-primary mr-1"></i> {{__('Candidate Chats')}}
                                </h3>
                                <span style="font-size: 12px; font-weight: 700; color: #64748B; background: #F1F5F9; padding: 2px 8px; border-radius: 20px;">
                                    {{ isset($seekers) ? count($seekers) : 0 }}
                                </span>
                            </div>

                            <ul class="chat-list-scroll">
                                @if(isset($seekers) && count($seekers) > 0)
                                    @php $firstSeekerId = null; @endphp
                                    @foreach($seekers as $index => $seeker)
                                        @if($index === 0) @php $firstSeekerId = $seeker->id; @endphp @endif
                                        <li class="chat-conversation-item {{ $index === 0 ? 'active' : '' }}" id="adactive{{ $seeker->id }}">
                                            <a href="javascript:;" class="chat-conversation-link" data-gift="{{ $seeker->id }}" id="seeker_id_{{ $seeker->id }}" onclick="show_messages({{ $seeker->id }})">
                                                <div class="chat-avatar-box">
                                                    @if(!empty($seeker->image) && file_exists(public_path('user_images/' . $seeker->image)))
                                                        <img src="{{ asset('user_images/' . $seeker->image) }}" alt="{{ $seeker->name }}">
                                                    @else
                                                        <span style="font-weight: 800; color: #2563EB; font-size: 15px;">{{ strtoupper(substr($seeker->name ?: 'U', 0, 1)) }}</span>
                                                    @endif
                                                </div>
                                                <div style="flex: 1; min-width: 0;">
                                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px;">
                                                        <span style="font-weight: 700; font-size: 13.5px; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                            {{ $seeker->name }}
                                                        </span>
                                                        @php $unreadCount = $seeker->countMessages(Auth::guard('company')->user()->id); @endphp
                                                        @if($unreadCount > 0)
                                                            <span style="background: #2563EB; color: #FFFFFF; font-size: 11px; font-weight: 800; padding: 1px 7px; border-radius: 12px;">
                                                                {{ $unreadCount }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span style="font-size: 12px; color: #64748B; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">
                                                        {{ $seeker->email }}
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <div style="padding: 30px 16px; text-align: center; color: #94A3B8;">
                                        <i class="fa fa-envelope-open-o" style="font-size: 28px; margin-bottom: 8px;"></i>
                                        <p style="font-size: 13px; margin: 0;">{{__('No messages yet')}}</p>
                                    </div>
                                @endif
                            </ul>
                        </div>

                        <!-- Right Main Pane: Active Chat Messages -->
                        <div class="chat-main-content">
                            <div id="append_messages" style="display: flex; flex-direction: column; flex: 1; min-height: 0; height: 100%;">
                                <!-- Loaded dynamically via AJAX -->
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #94A3B8;">
                                    <i class="fa fa-comments-o" style="font-size: 40px; margin-bottom: 12px; color: #CBD5E1;"></i>
                                    <p style="font-size: 14px; font-weight: 600; color: #64748B; margin: 0;">{{__('Select a candidate to start chatting')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
var currentActiveSeekerId = null;

function show_messages(id) {
    currentActiveSeekerId = id;
    $.ajax({
        type: "GET",
        url: "{{route('company-change-message-status')}}",
        data: { 'sender_id': id },
    });

    $.ajax({
        type: 'get',
        url: "{{route('append-message')}}",
        data: {
            '_token': $('input[name=_token]').val(),
            'seeker_id': id,
        },
        success: function(res) {
            $('#append_messages').html(res);
            var msgContainer = document.querySelector('.chat-messages-container');
            if (msgContainer) {
                msgContainer.scrollTop = msgContainer.scrollHeight;
            }
            $('.chat-conversation-item').removeClass('active');
            $("#adactive" + id).addClass('active');
        }
    });
}

$(document).ready(function() {
    @if(isset($firstSeekerId) && $firstSeekerId)
        show_messages({{ $firstSeekerId }});
    @endif

    // Real-time polling every 6 seconds for new messages
    setInterval(function() {
        if (currentActiveSeekerId) {
            $.ajax({
                type: 'get',
                url: "{{route('append-message')}}",
                data: {
                    '_token': $('input[name=_token]').val(),
                    'seeker_id': currentActiveSeekerId,
                },
                success: function(res) {
                    var msgContainer = document.querySelector('.chat-messages-container');
                    var isNearBottom = msgContainer ? (msgContainer.scrollHeight - msgContainer.scrollTop <= msgContainer.clientHeight + 80) : false;
                    $('#append_messages').html(res);
                    if (isNearBottom) {
                        var updatedContainer = document.querySelector('.chat-messages-container');
                        if (updatedContainer) updatedContainer.scrollTop = updatedContainer.scrollHeight;
                    }
                }
            });
        }
    }, 6000);
});
</script>
@endpush
