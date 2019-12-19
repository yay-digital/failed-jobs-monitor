<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Failed Jobs</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('/css/app.css', 'vendor/failed-jobs-monitor') }}">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('failed-jobs-monitor.index') }}">Failed Jobs Monitor</a>
</nav>
<div class="container">
    @yield('content')
</div>

<!-- Scripts -->
<script src="{{ mix('/js/app.js', 'vendor/failed-jobs-monitor') }}"></script>
</body>
</html>
