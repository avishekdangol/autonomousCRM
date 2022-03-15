<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="admin" content="{{ Auth::user()->admin }}">
    <meta name="user_id" content="{{ Auth::user()->id }}">      
    @if(tenant())
        <meta name="tenant_id" content="{{ tenant('id') }}">      
    @endif

    <title>
        @if(tenant())
            {{ tenant('name') }} | {{ config('app.name') }}
        @else
            Dashboard | {{ Config('app.name') }}
        @endif
    </title>

    <!-- Scripts and Icon -->
    @if(tenant())
        <script src="{{ asset('../../js/app.js') }}" defer></script>
        <link rel="icon" href="{{ asset(Config::get('constants.tenantInfo.logo')) }}">
    @else
        <script src="{{ asset('js/app.js') }}" defer></script>
        <link rel="icon" type="image" href="{{ asset(Config::get('constants.info.logo')) }}">
    @endif

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    @if(tenant())
        <link href="{{ asset('../../css/app.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">    
    @endif
    
</head>
<body onload="load()">
    <div class="pre-loader">
        <div>
            @if(tenant())
                <img class="loader-img" src="{{ asset(Config::get('constants.tenantInfo.gif_logo')) }}" alt="">
            @else 
                <img class="loader-img" src="{{ asset(Config::get('constants.info.gif_logo')) }}" alt="">
            @endif
        </div>
    </div>

    <div id="app">
        <!-- Navbar -->

        <nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm">
            <div class="px-2 w-100 row justify-content-between align-items-center">
                <div class="col-10">
                    @if(tenant())
                        <h5 class="fw-bold"><i class="fa-solid fa-user-tie"></i> Hello, {{ Auth::user()->name }}</h5>
                    @else
                        <a href="{{ url('/') }}" class="navbar-brand">
                            <img src="{{ Config::get('constants.info.full_logo') }}" alt="Autonomous Logo">
                        </a>
                    @endif
                </div>
    
                <div class="col-2">

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    {{-- <li class="dropdown me-2">
                        <a href="#" class="nav-link dropdown-toggle not-toggle" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                        </a>

                        <div class="dropdown-menu not-dropdown" aria-labelledby="notificationDropdown">
                            <a href="#" class="dropdown-item">You have pending new leads to deal with today.</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">You have pending new leads to deal with today.</a>
                        </div>
                    </li> --}}

                    <div class="collapse navbar-collapse float-right" id="navbar">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">

                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
        
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a href="#" class="dropdown-item {{ Auth::user()->name == 'Demo' ? 'disabled' : '' }}">
                                        <change-password />
                                    </a>
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                    </a>
                                    
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

    <script>
        const loader = document.querySelector('.pre-loader');
        
        function load() {
            loader.style.display = 'none';
        }
    </script>
</body>
</html>
