<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL::asset('plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('plugins/sweetalart/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/vote.css') }}" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ URL::asset('plugins/sweetalart/sweetalert.js') }}"></script>
    <script src="{{ URL::asset('js/vote.js') }}"></script>

</head>
<body>

    @yield('content')




<script>

</script>
</body>
</html>