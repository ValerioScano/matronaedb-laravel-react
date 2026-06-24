<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MatronaeDB') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])

</head>

<body>
    <div id="app" class="d-flex flex-column min-vh-100">

        <header class="sticky-top bg-white">
            <nav class="navbar navbar-expand-md navbar-light shadow-sm">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                        <img src="{{ asset('img/logo.png') }}" alt="logo MatronaeDB" width="30" height="24"
                            class="d-inline-block align-text-top">
                        <span>MatronaeDB</span>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/filings') }}">{{ __('Browse database') }}</a>
                            </li>
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                                        <a class="dropdown-item"
                                            href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </header>


        <main class="container py-4 flex-grow-1">
            @yield('content')
        </main>

        <footer class="border-top sticky-md-bottom bg-white">
            <div class="container">
                <div class="row py-3 mb-3 justify-content-around g-3">
                    <div class="col-md-4 text-center text-md-start">
                        <ul style="list-style-type: none" class="p-0 m-0">
                            <li><a class="fw-semibold fs-5 gap-2 d-flex align-items-center justify-content-center justify-content-md-start"
                                    href="{{ url('/') }}" style="text-decoration: none; color: inherit">
                                    <img src="{{ asset('img/logo.png') }}" alt="logo MatronaeDB" width="30"
                                        height="24" class="d-inline-block align-text-top">
                                    <span>MatronaeDB</span>
                                </a>
                            </li>

                            <li>
                                <a class="link" href="{{ url('/filings') }}">{{ __('Browse database') }}</a>
                            </li>
                            <li>
                                <button type="button" class="btn btn-link p-0 align-baseline text-decoration-underline"
                                    data-bs-toggle="modal" data-bs-target="#cookiepolicy">
                                    Cookie policy
                                </button>
                            </li>
                            @guest

                                <li>
                                    <a class="link" href="{{ route('login') }}">{{ __('Login') }}</a>

                                </li>
                                @if (Route::has('register'))
                                    <li>
                                        <a class="link" href="{{ route('register') }}">{{ __('Register') }}</a>

                                    </li>
                                @endif
                            @else
                                <li>
                                    <a class="link" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>

                                </li>
                                <li>
                                    <a class="link" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </li>

                                <fom id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                    </form>
                                @endguest

                        </ul>
                    </div>

                    <div class="col-md-4 text-center text-md-start">
                        <span class="fw-semibold fs-5">About us</span>
                        <ul style="list-style-type: none" class="p-0 m-0">
                            <li>Alice Cicarelli <a href="mailto:alice.cicarelli@uniroma1.it"><i
                                        class="bi bi-envelope-at-fill"></i></a>
                                <a
                                    href="https://phd.uniroma1.it/web/dottorato-sapienza-filologia-e-storia-del-mondo-antico-cicarelli-alice_nP1901686.aspx"><i
                                        class="bi bi-mortarboard-fill"></i></a>
                                <a href="https://www.linkedin.com/in/alice-cicarelli-6946a9364/"><i
                                        class="bi bi-linkedin"></i></a>
                            </li>
                            
                            <li>Valerio Scano <a href="mailto:valerioscano00@gmail.com"><i
                                        class="bi bi-envelope-at-fill"></i></a> <a
                                    href="https://www.linkedin.com/in/valerio-scano-aa3b252a6/"><i
                                        class="bi bi-linkedin"></i></a> <a href="https://github.com/ValerioScano"><i
                                        class="bi bi-github"></i></a></li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-center text-md-start">
                        <span class="fw-semibold fs-5">Help the project</span>
                        <ul style="list-style-type: none" class="p-0 m-0">
                            <li>
                                <a href="https://paypal.me/vscano00" class="btn btn-outline-dark mt-3">
                                    Buy us a coffee! <i class="bi bi-cup-hot-fill"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </footer>
    </div>

    <div class="modal fade" id="cookiepolicy" tabindex="-1" aria-labelledby="cookiepolicyLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="cookiepolicyLabel">Cookie policy</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        This website uses only technical cookies that are strictly necessary for
                        it to function. We do not use profiling cookies or third-party cookies,
                        so no consent is required.
                    </p>
                    The cookies we set are:


                    <ul>
                        <li>
                            laravel_session — keeps your session active while you browse. (Session)
                        </li>
                        <li>
                            XSRF-TOKEN — protects forms against CSRF attacks (security). (Session)
                        </li>
                        <li> remember_web_* — keeps you logged in if you choose "Remember me".
                            (Persistent)
                        </li>
                    </ul>

                    These cookies do not track you and are not shared with anyone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">All clear!</button>
                </div>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>

</html>
