@extends('layouts.login')

@section('title', __('lang_v1.reset_password'))

@section('content')

<div class="">

    @if(session('status') && is_string(session('status')))
        <div class="alert alert-info" role="alert">{{ session('status') }}</div>
    @endif

    <form  method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}
         <div class="field_div has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
            <input id="email" type="email" class="input_field" name="email" value="{{ old('email') }}" required autofocus placeholder="@lang('lang_v1.email_address')">
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <br>
        <div class="mb-3">
            <button type="submit" class="btn_custom px-5"> @lang('lang_v1.send_password_reset_link')</button>
        </div>
    </form>
</div>
@endsection
