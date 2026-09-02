<!-- Active Chat Header -->
<div style="padding: 12px 20px; border-bottom: 1.5px solid #E2E8F0; background: #FFFFFF; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; overflow: hidden; border: 1.5px solid #E2E8F0; background: #FFFFFF; display: flex; align-items: center; justify-content: center;">
            @if(!empty($seeker->image) && file_exists(public_path('user_images/' . $seeker->image)))
                <img src="{{ asset('user_images/' . $seeker->image) }}" alt="{{ $seeker->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <span style="font-weight: 800; color: #2563EB; font-size: 15px;">{{ strtoupper(substr($seeker->name ?: 'U', 0, 1)) }}</span>
            @endif
        </div>
        <div>
            <h2 style="font-size: 14.5px; font-weight: 800; color: #0F172A; margin: 0;">{{ $seeker->name }}</h2>
            <span style="font-size: 11.5px; color: #03855c; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                <i class="fa fa-circle" style="font-size: 7px;"></i> {{__('Active Candidate')}}
            </span>
        </div>
    </div>
    <a href="{{ route('user.profile', $seeker->id) }}" target="_blank" style="background: #EFF6FF; color: #2563EB; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa fa-user"></i> {{__('View Profile')}}
    </a>
</div>

<!-- Chat Message Bubbles Stream -->
<ul class="chat-messages-container message{{$seeker->id}}" style="flex: 1; min-height: 0; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 14px; list-style: none; margin: 0;">
    @if(isset($messages) && count($messages) > 0)
        @foreach ($messages as $msg)
            @if ($msg->type == 'message')
                <!-- Received message from Candidate -->
                <li class="chat-bubble-received tab{{$seeker->id}}">
                    <div style="width: 32px; height: 32px; border-radius: 8px; overflow: hidden; border: 1px solid #E2E8F0; background: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        @if(!empty($seeker->image) && file_exists(public_path('user_images/' . $seeker->image)))
                            <img src="{{ asset('user_images/' . $seeker->image) }}" alt="{{ $seeker->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-weight: 800; color: #2563EB; font-size: 12px;">{{ strtoupper(substr($seeker->name ?: 'U', 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="bubble-content">
                        <div>{{ $msg->message }}</div>
                        <div class="chat-time-label">
                            <i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $msg->updated_at)->diffForHumans() }}
                        </div>
                    </div>
                </li>
            @else
                <!-- Sent message by Company -->
                <li class="chat-bubble-sent tab{{$seeker->id}}">
                    <div style="width: 32px; height: 32px; border-radius: 8px; overflow: hidden; border: 1px solid #E2E8F0; background: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        @if(!empty($company->logo) && file_exists(public_path('company_logos/' . $company->logo)))
                            <img src="{{ asset('company_logos/' . $company->logo) }}" alt="{{ $company->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-weight: 800; color: #2563EB; font-size: 12px;">{{ strtoupper(substr($company->name ?: 'C', 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="bubble-content">
                        <div>{{ $msg->message }}</div>
                        <div class="chat-time-label">
                            <i class="fa fa-check"></i> {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $msg->updated_at)->diffForHumans() }}
                        </div>
                    </div>
                </li>
            @endif
        @endforeach
    @else
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #94A3B8;">
            <i class="fa fa-commenting-o" style="font-size: 36px; margin-bottom: 8px; color: #CBD5E1;"></i>
            <p style="font-size: 13px; font-weight: 600; color: #64748B; margin: 0;">{{__('Say hello to start the conversation!')}}</p>
        </div>
    @endif
</ul>

<!-- Chat Input Bar -->
<div class="chat-input-bar">
    <form class="form-chat-company-send" style="margin: 0; display: flex; align-items: center; gap: 10px;">
        @csrf
        <input type="hidden" name="seeker_id" value="{{$seeker->id}}">
        <div style="flex: 1; position: relative;">
            <input type="text" class="form-control" name="message" id="chat_company_message_input" placeholder="{{__('Type your message here...')}}" autocomplete="off" style="width: 100%; height: 44px; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 8px 14px; font-size: 13.5px; background: #F8FAFC; outline: none; box-shadow: none;">
        </div>
        <button type="submit" style="width: 44px; height: 44px; border-radius: 10px; background: #2563EB; color: #FFFFFF; border: none; display: flex; align-items: center; justify-content: center; font-size: 16px; box-shadow: 0 2px 8px rgba(37,99,235,0.25); cursor: pointer; flex-shrink: 0;">
            <i class="fa fa-paper-plane"></i>
        </button>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function() {
    if ($(".form-chat-company-send").length > 0) {
        $(".form-chat-company-send").validate({
            validateHiddenInputs: true,
            ignore: "",
            rules: {
                message: { required: true, maxlength: 5000 },
            },
            messages: {
                message: { required: "" }
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });
                var msgText = $('#chat_company_message_input').val();
                $.ajax({
                    url: "{{route('company.submit-message')}}",
                    type: "POST",
                    data: $('.form-chat-company-send').serialize(),
                    success: function(res) {
                        $('#chat_company_message_input').val('');
                        if (typeof show_messages === 'function') {
                            show_messages({{$seeker->id}});
                        }
                    }
                });
                return false;
            }
        });
    }
});
</script>
