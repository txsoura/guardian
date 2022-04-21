@component('mail::message')
    <b>Hello!</b>
    <br/><br/>
    @lang('mail.login.paragraph')
    <br/><br/>
    @if($data)
        @lang('mail.login.date') <span class="alert alert-primary"><b>{{$data['date']}}</b></span>
        <br/>
        @lang('mail.login.ip') <span class="alert alert-primary"><b>{{$data['ip']}}</b></span>
    @endif
    <br/><br/>
    {{ config('app.name') }}
    <br/>
    <hr/>
    @lang('mail.login.footer')
@endcomponent
