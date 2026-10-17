<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mượn thiết bị')</title>

    {{-- Bootstrap 5 qua CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ url('/devices') }}">Mượn thiết bị</a>
        </div>
    </nav>

    <main class="container py-3">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
