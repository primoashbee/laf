<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>OLAF</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<style type="text/css">
    div.notification{
        display: none;
    }
    div.notification.active{
        display: block;
    }
    a[disabled="disabled"] {
        pointer-events: none;
        background: #6c757d;
        border-color: #6c757d;
    }
    .mt-50{
        margin-top: 50px;
    }
    a.import:visited {
     pointer-events: none;
     cursor: default; 
    }
    .animated {
            background-image: url(/css/images/logo.png);
            background-repeat: no-repeat;
            background-position: left top;
            -webkit-animation-duration: 3;animation-duration: 3s;
            -webkit-animation-fill-mode: both;animation-fill-mode: both;
         }
         
         @-webkit-keyframes fadeOut {
            0% {opacity: 1;}
            100% {
                opacity: 0;
            }
         }
         
         @keyframes fadeOut {
            0% {opacity: 1;}
            100% {opacity: 0;
         }
         
         .fadeOut {
            -webkit-animation-name: fadeOut;
            animation-name: fadeOut;
         }
    }
</style>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                  OLAF
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            
                           
                        @else


                            @if (Auth::check())
                                @if(auth()->user()->is_admin == true)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Create Account</a>
                                </li>
                                 @endif 
                             @endif
                             <li class="nav-item">
                                <a class="nav-link" href="{{ route('forms.printed') }}">Printed Forms</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('forms.unprinted') }}">Unprinted Forms</a>
                            </li>
                            <li class="nav-item dropdown">
                                 
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('scripts')
</body>
</html>
