<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ Developer::title() }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @if(!is_null($favicon = Developer::favicon()))
    <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    {!! Developer::css() !!}
    {!! Developer::headerJs() !!}
    {!! Developer::js() !!}
    {!! Developer::js_trans() !!}

</head>

<body class="{{config('developer.skin')}} {{ $body_classes }}">

    @if($alert = config('developer.top_alert'))
        <div class="alert">
            {!! $alert !!}
        </div>
    @endif
    <div class="wrapper">

        @include('developer::partials.header')
        @include('developer::partials.sidebar')
        <main id="main" class="p-4">

            <div id="pjax-container">
            <!--start-pjax-container-->
                {!! Developer::style() !!}
                <div id="app">
                    @yield('content')
                </div>
                {!! Developer::html() !!}
                {!! Developer::script() !!}
            <!--end-pjax-container-->
            </div>

        </main>
    </div>

    @if (1==2)
        @include('developer::partials.footer')
    @endif

    <button id="totop" title="Go to top" style="display: none;"><i class="icon-chevron-up"></i></button>

    <script>
        function LA() {}
        LA.token = "{{ csrf_token() }}";
        LA.user = @json($_user_);
    </script>

    </body>
</html>
