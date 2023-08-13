@extends('developer::index', ['header' => strip_tags($header)])

@section('content')

    @foreach ($css_files as $css_file)
        <link rel="stylesheet" href="{{ $css_file }}">
    @endforeach
    @isset($css)
        <style type="text/css">{{ $css }}</style>
    @endisset

    <section class="content-header clearfix">
        <h1>
            {!! $header ?: trans('developer.title') !!}
            <small>{!! $description ?: trans('developer.description') !!}</small>
        </h1>

        @include('developer::partials.breadcrumb')

    </section>

    <section class="content">

        @include('developer::partials.alerts')
        @include('developer::partials.exception')
        @include('developer::partials.toastr')

        @if($_view_)
            @include($_view_['view'], $_view_['data'])
        @else
            {!! $_content_ !!}
        @endif

    </section>
@endsection
