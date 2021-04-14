<!DOCTYPE html>
<html class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset( 'favicon.ico' ) }}" />

    <link rel="preconnect" href="https://fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;600;700;800;900&display=swap" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- Inertia.js polyfills --}}
    <script src="https://polyfill.io/v3/polyfill.min.js?features=smoothscroll,NodeList.prototype.forEach,Promise,Object.values,Object.assign" defer></script>

    {{-- startsWith polyfill --}}
    <script src="https://polyfill.io/v3/polyfill.min.js?features=String.prototype.startsWith" defer></script>

    <script src="{{ mix('/js/app.js') }}" defer></script>
    @routes
</head>

<body class="font-sans leading-none text-gray-700 antialiased">

    @inertia

</body>

</html>