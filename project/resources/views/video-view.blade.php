@extends('adminlte::page')

@section('title', 'View')

@section('content_header')
    <h1>{{ $video->title }}</h1>
@stop

@section('content')

<video controls="true" autoplay="true">
    <source src="{{ $url }}" type="video/mp4">
</video>

<h3>{{ $video->title }}</h3>
<p>{{ $video->created_at }}</p>

<a role="button" href="/video-edit/{{$video->id}}" class="btn btn-info">Edit</a>
<a role="button" href="/video-delete/{{$video->id}}" class="btn btn-danger">Delete</a>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop