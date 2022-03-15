<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="admin" content="">
    <meta name="user_id" content="">      

    <title>Login | {{ config('app.name') }}</title>

    <!-- Scripts and Icon -->
    @if(tenant())
        <script src="{{ asset('../../js/index.js') }}" defer></script>
        <link rel="icon" href="{{ asset(Config::get('constants.tenantInfo.logo')) }}">
    @else
        <script src="{{ asset('js/index.js') }}" defer></script>
        <link rel="icon" href="{{ asset(Config::get('constants.info.logo')) }}">
    @endif

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    @if(tenant())
        <link href="{{ asset('../../css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('../../css/login.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">    
        <link href="{{ asset('css/login.css') }}" rel="stylesheet">    
    @endif
    
</head>

<body>
    <div class="login-box" id="index">
        <div class="container mb-4 col-md-3 col-sm">              
            <div class="logo text-center">
                @if(tenant())
                    <img src="{{ asset(Config::get('constants.tenantInfo.logo')) }}" alt="">
                @else 
                    <img src="{{ asset(Config::get('constants.info.logo')) }}" alt="">
                @endif
            </div>
            <div class="card pb-2 shadow shadow-sm">
                <div class="card-header">
                    @if(tenant())
                        <h5 class="fw-bold py-2">Login to the CRM</h5>
                    @else
                        <h5 class="fw-bold py-2">Login</h5>
                    @endif
                </div>

                <div class="card-body">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" id="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ tenant('id') == 'demo' ? 'demo' : old('username') }}" required autocomplete="username" placeholder="Username">
                            <label for="floatingInput">Username</label>

                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ tenant('id') == 'demo' ? 'demo' : old('password') }}" required autocomplete="current-password" placeholder="Password">
                            <label for="floatingInput">Password</label>

                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group d-flex justify-content-end">
                            @if(!tenant())
                                <a href="/home" class="btn btn-secondary text-white me-2">Back to Homepage</a>
                            @endif

                            <button class="btn btn-primary text-white w-25" type="submit">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
