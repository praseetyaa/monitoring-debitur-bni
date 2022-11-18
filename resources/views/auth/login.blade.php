<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metas -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="sistem informasi monitoring ATM BNI">
    <meta name="author" content="Spandiv Digital">
    <meta name="keywords" content="sistem informasi monitoring ATM BNI">
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="3 days">
    <meta name="language" content="id">
    <meta name="distribution" content="global">
    <meta name="rating" content="general">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo/BNI_icon.png') }}">

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://ajifatur.github.io/assets/spandiv.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <title>Log in | {{ config('app.name') }}</title>
    <style>
        body, body main {
            min-height: 100vh;
        }
        body{background-image: url({{ asset('assets/images/backgrounds/bg-login.svg') }});background-size: cover;background-repeat: no-repeat;background-position: center;background-attachment: fixed;}
        .login-box {
            width: 75%;
            margin: auto;
        }
        .sign-l{padding-top:9em; padding-bottom:9em;}
        .sign-r{padding-top:3em; padding-bottom:3em;}
        .bg-glass{backdrop-filter:blur(5px); border:2px solid #fff}
    </style>
    {!! ReCaptcha::htmlScriptTagJsApi() !!}
</head>
<body>
    <main class="d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center shadow rounded-4 bg-glass p-lg-3">
                <div class="col-12 col-lg-5 px-lg-5 sign-l d-none d-lg-block bg-primary rounded-3">
                    <img src="{{ asset('assets/images/illustrations/sign-in.png') }}" alt="sign-in" class="img-fluid">
                    <div class="greetings text-white text-center mt-3">
                        <p class="mb-0 fw-bold">Selamat Datang Di Aplikasi Monitoring Debitur</p>
                        <p class="small">Anda dapat melakukan monitoring jalanya debitur dan melakukan report</p>
                    </div>
                </div>
                <div class="col-12 col-lg-7 px-lg-5 sign-r">
                    <form class="login-box text-start" method="post" action="{{ route('auth.login') }}">
                        @csrf
                        <img src="{{ asset('assets/images/logo/BNI_logo.png') }}" width="100" alt="bni logo">
                        <p class="small">Monitoring Debitur</p>
                        <h1 class="h3">Masuk</h1>
                        <p class="mb-4">Silahkan masuk menggunakan username dan password</p>
                        @if($errors->has('message'))
                        <div class="alert alert-danger" role="alert">{{ $errors->first('message') }}</div>
                        @endif
                        @if($errors->has('g-recaptcha-response'))
                        <div class="alert alert-danger" role="alert">Anda terindikasi robot!</div>
                        @endif
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control rounded-3 {{ $errors->has('username') ? 'border-danger' : '' }}" value="{{ old('username') }}" placeholder="{{ config('faturhelper.auth.allow_login_by_email') === true ? 'Email atau Username' : 'Username' }}" autofocus>
                            @if($errors->has('username'))
                            <div class="small text-danger text-start">{{ $errors->first('username') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'border-danger' : '' }}" placeholder="Password" style="border-radius:2rem 0 0 2rem">
                                <button type="button" class="btn {{ $errors->has('password') ? 'btn-outline-danger' : 'btn-outline-secondary' }} btn-toggle-password btn-primary" style="border-radius:0 2rem 2rem 0"><i class="bi-eye"></i></button>
                            </div>
                            @if($errors->has('password'))
                            <div class="small text-danger text-start">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="mb-3 d-flex justify-content-center">{!! htmlFormSnippet() !!}</div>
                        <button class="w-100 btn btn-primary rounded-3" type="submit">Log in</button>
                        @if(config('faturhelper.auth.socialite') == true)
                        <div class="btn-group mt-3">
                            <a href="{{ route('auth.login.provider', ['provider' => 'google']) }}" class="btn btn-outline-primary">Google</a>
                            <a href="{{ route('auth.login.provider', ['provider' => 'facebook']) }}" class="btn btn-outline-primary">Facebook</a>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://ajifatur.github.io/assets/spandiv.min.js"></script>
</body>
</html>