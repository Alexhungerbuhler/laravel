<!DOCTYPE html>
<html lang="fr">
<head>
  …
  <script>
    window.APP_PAYLOAD = {
      // Le personnage (ou null)
      character: @json(optional(Auth::user()->character)),
      // La map (ou [[]])
      map:       @json(optional(Auth::user()->character)->map->cells ?? []),
      // Flash messages éventuelles
      flash: {
        success: @json(session('success')),
        error:   @json(session('error')),
      }
    };
  </script>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
  <div id="app"></div>
</body>
</html>
