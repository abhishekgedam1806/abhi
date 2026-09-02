@if (!empty($siteSetting->linkedin_address))
<a href="{{ $siteSetting->linkedin_address }}" target="_blank" rel="noopener noreferrer" title="LinkedIn" aria-label="LinkedIn"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
@endif

@if (!empty($siteSetting->instagram_address))
<a href="{{ $siteSetting->instagram_address }}" target="_blank" rel="noopener noreferrer" title="Instagram" aria-label="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
@endif

@if (!empty($siteSetting->facebook_address))
<a href="{{ $siteSetting->facebook_address }}" target="_blank" rel="noopener noreferrer" title="Facebook" aria-label="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
@endif

@if (!empty($siteSetting->twitter_address))
<a href="{{ $siteSetting->twitter_address }}" target="_blank" rel="noopener noreferrer" title="Twitter / X" aria-label="Twitter / X"><i class="fa fa-twitter" aria-hidden="true"></i></a>
@endif

@if (!empty($siteSetting->youtube_address))
<a href="{{ $siteSetting->youtube_address }}" target="_blank" rel="noopener noreferrer" title="YouTube" aria-label="YouTube"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
@endif

@php
    $rawPhone = preg_replace('/[^0-9]/', '', $siteSetting->site_phone_primary ?? '7038424139');
    $waPhone = (strlen($rawPhone) == 10) ? '91' . $rawPhone : $rawPhone;
@endphp
<a href="https://api.whatsapp.com/send?phone={{ $waPhone }}&text=Hi%20Jobs%20Portal" target="_blank" rel="noopener noreferrer" title="WhatsApp Support" aria-label="WhatsApp Support"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>