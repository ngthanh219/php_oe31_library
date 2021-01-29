<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ trans('client.forgot_password') }}</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login theme">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="space"></div>
            <div class="logo"></div>
            <div class="break-line"></div>
            <div class="form">
                <div class="message">
                    <div class="card-header">{{ trans('client.forgot_password') }}</div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <span class="mess-success">{{ session('status') }}</span>
                        </div>
                    @endif
                </div>
                <div class="info-wrap">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email"
                        autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <div class="error">{{ $message }}</div>
                        </span>
                    @enderror
                </div>
                <div class="options">
                </div>
                <input type="submit" class="btn-submit" value="{{ trans('client.forgot_password') }}" />
            </div>
            <div class="space"></div>
        </form>
    </div>
</body>

</html>
