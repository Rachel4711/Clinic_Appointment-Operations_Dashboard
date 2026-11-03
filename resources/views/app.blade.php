<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Fictional clinic operations portfolio demonstration.">
    <title>Riverbend Clinic Operations</title>
    @if (file_exists(public_path('build/.vite/manifest.json')))
        @php($manifest = json_decode(file_get_contents(public_path('build/.vite/manifest.json')), true))
        <link rel="stylesheet" href="{{ asset('build/'.$manifest['resources/js/main.jsx']['css'][0]) }}">
        <script type="module" src="{{ asset('build/'.$manifest['resources/js/main.jsx']['file']) }}"></script>
    @else
        <style>body{font-family:system-ui;padding:2rem;color:#173042}</style>
        <p>Frontend assets are missing. Run <code>npm run build</code>, then refresh this page.</p>
    @endif
</head>
<body>
    <div id="root"></div>
</body>
</html>
