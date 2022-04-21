@component('mail::message')
    <b>Hello!</b>
    <br/><br/>
    @lang('mail.password.paragraph')
    <br/><br/>
    {{ config('app.name') }}
    <br/>
    <hr/>
    @lang('mail.password.footer')
@endcomponent
