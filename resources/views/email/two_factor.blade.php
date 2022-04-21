@component('mail::message')
    <b>Hello!</b>
    <br/><br/>
    @lang('mail.two_factor.paragraph')
    <br/><br/>
    @if($provider)
        @lang('mail.two_factor.status')
        <span class="alert alert-primary"><b>@lang('mail.two_factor.active')</b></span>
        <br/>
        @lang('mail.two_factor.verification') <span class="alert alert-primary"><b>{{$provider}}</b></span>
    @else
        @lang('mail.two_factor.status')
        <span class="alert alert-primary"><b>@lang('mail.two_factor.deactivate')</b></span>
    @endif
    <br/><br/>
    {{ config('app.name') }}
    <br/>
    <hr/>
    @lang('mail.two_factor.footer')
@endcomponent
