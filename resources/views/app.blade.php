<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WooW</title>

    <!-- CSRF Token pour les requêtes JS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS & JS via Vite -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body>
    <div id="app"></div>

    @php
      $injectPayload = request()->is('dashboard') || request()->is('character/create');
    @endphp

    @if ($injectPayload)
    <script>
        window.APP_PAYLOAD = {
            user: @json(auth()->user()),
            character: @json(auth()->user()?->character),
            map: @json(optional(auth()->user()?->character?->map)->cells ?? []),
            flash: {
                success: @json(session('success')),
                error: @json(session('error')),
            }
        };
    </script>
    @endif
</body>
</html>
