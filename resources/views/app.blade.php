@extends('adminlte::page')

@section('title', 'Asset Management System')

@section('content_header')
    <h1>@yield('page_title')</h1>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="{{ asset('js/app.js') }}"></script>
@stop
