<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login theme">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space"></div>
            <div class="logo"></div>
            <div class="break-line"></div>
            <div class="form">
                <div class="Message">{{ trans('login.hello') }},
                    <br /> {{ trans('login.please_login') }}
                </div>
                <div class="info-wrap">
                    <input type="email" placeholder="{{ trans('login.email') }}" name="email"
                        value="{{ old('email') }}" />
                    <div class="validation-summary-errors">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>
                    <input type="password" placeholder="{{ trans('login.password') }}" name="password" />
                    <div class="validation-summary-errors">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="Options">
                    <div>
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} />
                        <label for="remember">{{ trans('login.remember') }}</label>
                    </div>
                    <div>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ trans('login.forget_password') }}
                            </a>
                        @endif
                    </div>
                </div>
                <input type="submit" class="btn-submit" value="{{ trans('login.login') }}" />
            </div>
            <div class="space"></div>
        </form>
    </div>
</body>

</html>
