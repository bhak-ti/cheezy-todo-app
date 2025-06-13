<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cheezy Todo App</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}" />
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">🧀 Cheezy Todo App</h1>
        @yield('content')
    </div>

    <script src="{{ asset('js/flatpickr.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
