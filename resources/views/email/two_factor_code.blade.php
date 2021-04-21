@component('mail::message')
<b>Hello!</b>
<br /><br />
@lang('mail.two_factor_code.paragraph')
<br /><br />
@if($code)
@lang('mail.two_factor_code.code') <span class="alert alert-primary"><b>{{$code}}</b></span>
@endif
<br /><br />
{{ config('app.name') }}
<br />
<hr />
@lang('mail.two_factor_code.footer')
@endcomponent
