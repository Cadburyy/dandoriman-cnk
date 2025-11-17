<!doctype html>
@php
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;


$settings = cache()->remember('app_settings', 60, function () {
    return Setting::pluck('value', 'key')->toArray();
});


$brand      = $settings['brand_name'] ?? 'Citra Nugerah Karya';
$font       = $settings['font'] ?? 'Nunito';
$logoUrl    = !empty($settings['logo_path'])
    ? asset('storage/'.$settings['logo_path'])
    : asset('images/cnk.png');

$faviconUrl = !empty($settings['favicon_path'])
    ? asset('storage/'.$settings['favicon_path'])
    : asset('favicon.ico');

$fontHrefName = str_replace(' ', '+', $font);


function getTextColor($hexColor) {
    $hex = str_replace('#', '', $hexColor);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }

    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
    return $luminance > 0.5 ? '#111827' : '#f8f9fa';
}


function hexToRgba($hex, $alpha) {
    $hex = str_replace('#', '', $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }

    return "rgba($r, $g, $b, $alpha)";
}


function getCardBgColor($bgColor) {
    $hex = str_replace('#', '', $bgColor);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }

    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
    return $luminance > 0.5 ? '#f3f4f6' : '#ffffff';
}


function getDropdownBgColor($bgColor) {
    $hex = str_replace('#', '', $bgColor);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }

    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
    return $luminance > 0.5 ? '#e9ecef' : '#212529';
}


$bgColor           = $settings['bg_color'] ?? '#f8f9fa';
$navBgColor        = $settings['nav_bg_color'] ?? '#ffffff';
$bgTextColor       = getTextColor($bgColor);
$navTextColor      = getTextColor($navBgColor);
$cardBgColor       = getCardBgColor($bgColor);
$cardTextColor     = getTextColor($cardBgColor);
$dropdownBgColor   = getDropdownBgColor($bgColor);
$dropdownTextColor = getTextColor($dropdownBgColor);
@endphp

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $brand }}</title>

    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family={{ $fontHrefName }}:400,600,700" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        :root {
            --bg: {{ $bgColor }};
            --text: {{ $bgTextColor }};
            --surface: {{ $navBgColor }};
            --nav-text: {{ $navTextColor }};
            --card-surface: {{ $cardBgColor }};
            --card-text: {{ $cardTextColor }};
            --dropdown-surface: {{ $dropdownBgColor }};
            --dropdown-text: {{ $dropdownTextColor }};
            --muted: #6b7280;
            --border: #e5e7eb;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: '{{ $font }}', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif;
        }

        .navbar-brand,
        .navbar-brand span,
        .navbar .nav-link,
        .dropdown-item {
            color: var(--nav-text) !important;
        }

        .navbar.navbar-light.bg-white,
        .navbar-collapse {
            background-color: var(--surface) !important;
            color: var(--text) !important;
        }

        .dropdown-menu {
            background-color: var(--dropdown-surface) !important;
            color: var(--dropdown-text) !important;
        }

        .dropdown-item {
            color: var(--dropdown-text) !important;
        }

        .card {
            background-color: var(--card-surface) !important;
            color: var(--card-text) !important;
        }

        .card-header {
            background-color: var(--card-surface) !important;
            border-bottom: 1px solid var(--border) !important;
        }

        .fixed-blur-navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            backdrop-filter: blur(5px);
            background-color: {{ hexToRgba($navBgColor, 0.8) }};
        }

        #loading-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 0;
            background-color: #3498db;
            transition: width 0.3s ease-in-out, opacity 0.5s ease-in-out;
            z-index: 1031;
        }

        .navbar-nav .nav-link {
            position: relative;
            padding-bottom: 12px;
        }

        .navbar-nav .nav-link::before,
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            height: 2px;
            width: 0;
            transition: width 0.3s ease-in-out;
        }

        .navbar-nav .nav-link::before {
            background-color: #ef4444;
            bottom: 7px;
        }

        .navbar-nav .nav-link::after {
            background-color: #3b82f6;
        }

        .navbar-nav .nav-link:hover::before {
            width: 62.5%;
        }

        .navbar-nav .nav-link:hover::after {
            width: 37.5%;
        }

        @media (max-width: 767px) {
            .navbar-collapse {
                background-color: {{ hexToRgba($navBgColor, 0.95) }};
                padding: 1rem;
                border-radius: 0.75rem;
                margin-top: 0.5rem;
                animation: slideDown 0.3s ease-in-out;
            }

            .navbar-nav .nav-item {
                border-bottom: 1px solid var(--border);
            }

            .navbar-nav .nav-item:last-child {
                border-bottom: none;
            }

            .navbar-nav .nav-link {
                padding: 0.75rem 1rem;
                font-weight: 500;
                transition: background-color 0.2s ease-in-out;
            }

            .navbar-nav .nav-link:hover {
                background-color: #d1d5db;
                border-radius: 0.5rem;
                transform: translateX(5px);
            }

            .dropdown-menu {
                background-color: var(--dropdown-surface) !important;
                border: none !important;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-blur-navbar">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ $logoUrl }}" alt="Logo" style="height: 30px;">
                </a>
                <span class="ms-2 align-items-center">{{ $brand }}</span>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"></ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            @php
                                $user = Auth::user();
                                $isAdmin = $user->hasRole('Admin');
                                $isRequestor = $user->hasRole('Requestor');
                                $isTeknisi = $user->hasRole('Teknisi');
                                $isView = $user->hasRole('Views');
                                $isTeknisiAdmin = $user->hasRole('AdminTeknisi');
                            @endphp

                            @if($isView || $isAdmin || $isRequestor || $isTeknisi || $isTeknisiAdmin)
                                <li>
                                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                                </li>
                            @endif

                            @if($isAdmin || $isRequestor || $isTeknisi || $isTeknisiAdmin)
                                <li>
                                    <a class="nav-link" href="{{ route('dandories.index') }}">Dandori</a>
                                </li>
                            @endif

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ $user->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if($isAdmin || $isTeknisiAdmin)
                                        <a class="dropdown-item" href="{{ route('settings.index') }}">Settings</a>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
            <div id="loading-bar"></div>
        </nav>

        <main class="py-4 mt-5">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div>
                            <div class="card-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @if(Auth::check() && Auth::user()->hasRole('Teknisi'))
        <script>
            (function() {
                let logoutTimer;
                const LIMIT = 60 * 1000;

                function resetTimer() {
                    clearTimeout(logoutTimer);
                    logoutTimer = setTimeout(logoutUser, LIMIT);
                }

                function logoutUser() {
                    fetch("{{ route('logout') }}", {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                    }).then(() => window.location.href = "{{ route('login') }}");
                }

                ['mousemove','keypress','click','scroll'].forEach(e => document.addEventListener(e, resetTimer));
                resetTimer();
            })();
        </script>
    @endif

    <script>
        const loadingBar=document.getElementById('loading-bar');
        document.addEventListener('DOMContentLoaded',function(){
            loadingBar.style.width='90%';
        });
        window.addEventListener('load',function(){
            loadingBar.style.width='100%';
            loadingBar.style.opacity='0';
        });
    </script>
</body>
</html>