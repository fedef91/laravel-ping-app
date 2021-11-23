<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{env('APP_NAME')}}</title>
   
    <link href="{{ asset('css/app.css') }}" type="text/css" rel="stylesheet"/>
   
</head>
<body>
    <div id="app" class="wrapper min-h-screen">
        <app></app>
    </div>
</body>
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
</html>