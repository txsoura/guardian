@component('mail::message')
    <b>Hello!</b>
    <br/><br/>
    @lang('mail.cellphone.paragraph')
    <br/><br/>
    {{ config('app.name') }}
    <br/>
    <hr/>
    @lang('mail.cellphone.footer')
@endcomponent
