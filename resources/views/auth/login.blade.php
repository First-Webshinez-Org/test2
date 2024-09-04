@extends('layouts.login')
@section('title', __('lang_v1.login'))

@section('content')
    <div class="">
        <p class="text-secondary">Welcome to BizzSelf</p>
        <h1 class="mb-4 login">@lang('lang_v1.login')</h1>
        <form method="POST" action="{{ route('login') }}" id="login-form">
            {{ csrf_field() }}
            <div class="field_div has-feedback {{ $errors->has('username') ? ' has-error' : '' }}">
                @php
                    $username = old('username');
                    $password = null;
                    if(config('app.env') == 'demo'){
                        $username = 'admin';
                        $password = '123456';

                        $demo_types = array(
                            'all_in_one' => 'admin',
                            'super_market' => 'admin',
                            'pharmacy' => 'admin-pharmacy',
                            'electronics' => 'admin-electronics',
                            'services' => 'admin-services',
                            'restaurant' => 'admin-restaurant',
                            'superadmin' => 'superadmin',
                            'woocommerce' => 'woocommerce_user',
                            'essentials' => 'admin-essentials',
                            'manufacturing' => 'manufacturer-demo',
                        );

                        if( !empty($_GET['demo_type']) && array_key_exists($_GET['demo_type'], $demo_types) ){
                            $username = $demo_types[$_GET['demo_type']];
                        }
                    }
                @endphp
                <label for="username" class="form_label">@lang('lang_v1.username')</label>
                <input id="username" type="text" class="input_field" name="username" value="{{ $username }}" required autofocus placeholder="@lang('lang_v1.username')">
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
            <div class="field_div has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="form_label">@lang('lang_v1.password')</label>
                <input id="password" type="password" class="input_field" name="password" value="{{ $password }}" required placeholder="@lang('lang_v1.password')">
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form_check">
                <div class="checkbox icheck custom_checkbox">
                    <input type="checkbox" {{ old('remember') ? 'checked' : '' }} class="form_check_input" id="remember">
                    <label class="form_check_label" for="remember">@lang('lang_v1.remember_me')</label>
                </div>
                <div>
                    <p>
                        <a class="nav_link" href="{{ route('password.request') }}" style="font-weight:lighter;">@lang('lang_v1.forgot_your_password')</a>
                    </p>
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn_custom px-5">@lang('lang_v1.login')</button>
            </div>
        </form>
    </div>
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $('#change_lang').change(function(){
            window.location = "{{ route('login') }}?lang=" + $(this).val();
        });

        $('a.demo-login').click(function (e) {
           e.preventDefault();
           $('#username').val($(this).data('admin'));
           $('#password').val("{{$password}}");
           $('form#login-form').submit();
        });
    })
</script>
@endsection
