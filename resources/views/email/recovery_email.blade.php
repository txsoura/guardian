@component('mail::message')
    <b>Hello!</b>
    <br/><br/>
    @if($email)
        @lang('mail.recovery_email.email')<span class="alert alert-primary"><b>{{$email}}</b></span>
    @endif
    <br/><br/>
    @lang('mail.recovery_email.paragraph')
    <br/><br/>
    @component('mail::button', ['url' => $url])
        @lang('mail.recovery_email.action')
    @endcomponent
    <br/><br/>
    @lang('mail.recovery_email.expires',['count' => config('auth.recovery_email_timeout')])
    <br/><br/>
    {{ config('app.name') }}
    <br/>
    <hr/>
    @lang('mail.recovery_email.footer')
@endcomponent
