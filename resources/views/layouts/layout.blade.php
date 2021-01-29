<!doctype html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--  JavaScript -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>{{ config('app.name', 'Badamum') }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">



</head>
<body>
    <div id="app" class='d-flex flex-column justify-content-between' style='height:130vh' >

        @include('layouts.nav')

        <main class="mb-auto py-4" style='margin-top: 65px'>
            @yield('content')
        </main>

        @include('layouts.footer')

    </div>

</body>
</html>