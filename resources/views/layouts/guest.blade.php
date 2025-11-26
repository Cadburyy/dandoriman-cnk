<!DOCTYPE html>
@php
    use App\Models\Setting;

    $settings = cache()->remember('app_settings', 60, function () {
        return Setting::pluck('value', 'key')->toArray();
    });

    $brand      = $settings['brand_name'] ?? 'Citra Nugerah Karya';
    $font       = $settings['font'] ?? 'Nunito';
    $faviconUrl = !empty($settings['favicon_path'])
        ? asset('storage/'.$settings['favicon_path'])
        : asset('favicon.ico');

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
    
    $bgColor = $settings['bg_color'] ?? '#f8f9fa';
    $bgTextColor = getTextColor($bgColor);
    $cardBgColor = getCardBgColor($bgColor);
    
    $fontHrefName = str_replace(' ', '+', $font);
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
            --card-surface: {{ $cardBgColor }};
        }

        html, body, #app {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: '{{ $font }}', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif;
            overflow-x: hidden; 
            overflow-y: hidden;
        }
        
        #app {
            display: flex;
            flex-direction: column;
        }

        main {
            flex-grow: 1; 
            overflow-y: auto; 
        }

        .card {
            background-color: var(--card-surface) !important;
        }
    </style>
</head>
<body>
    <div id="app">
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>