<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
      window.app = {!! json_encode([
        'signedIn' => auth()->check(),
        'user' => auth()->user(),
      ]) !!}
    </script>

    <style>
        body {
            background-color: #e2e2e2;
        }

        [v-cloak] {
            display: none;
        }
    </style>

    @yield('head')
</head>
<body>
<div id="app">
    @include('layouts._header')

    <main class="py-4">
        @yield('content')
    </main>

    <flash message="{{ session('flash') }}"></flash>
</div>

@section('scripts')
    <script src="{{ asset('js/app.js') }}" defer></script>
@show
</body>
</html>
