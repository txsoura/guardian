@component('mail::message')
<b>Hello!</b>
<br /><br />
@lang('mail.password.paragraph')
<br /><br />
@if($password)
@lang('mail.password.password') <span class="alert alert-primary"><b>{{$password}}</b></span>
@endif
<br /><br />
{{ config('app.name') }}
<br />
<hr />
@lang('mail.password.footer')
@endcomponent
