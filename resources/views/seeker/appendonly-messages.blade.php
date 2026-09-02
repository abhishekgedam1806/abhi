@if(isset($messages) && count($messages) > 0)
    @foreach($messages as $msg)
        @if ($msg->type == 'reply')
            <!-- Received message from Employer -->
            <li class="chat-bubble-received tab{{$company->id}}">
                <div style="width: 34px; height: 34px; border-radius: 10px; overflow: hidden; border: 1px solid #E2E8F0; background: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    @if(!empty($company->logo) && file_exists(public_path('company_logos/' . $company->logo)))
                        <img src="{{ asset('company_logos/' . $company->logo) }}" alt="{{ $company->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-weight: 800; color: #2563EB; font-size: 13px;">{{ strtoupper(substr($company->name ?: 'C', 0, 1)) }}</span>
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
            <!-- Sent message by Candidate -->
            <li class="chat-bubble-sent tab{{$company->id}}">
                <div style="width: 34px; height: 34px; border-radius: 10px; overflow: hidden; border: 1px solid #E2E8F0; background: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    @if(!empty($seeker->image) && file_exists(public_path('user_images/' . $seeker->image)))
                        <img src="{{ asset('user_images/' . $seeker->image) }}" alt="{{ $seeker->first_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-weight: 800; color: #2563EB; font-size: 13px;">{{ strtoupper(substr($seeker->first_name ?: 'U', 0, 1)) }}</span>
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
@endif