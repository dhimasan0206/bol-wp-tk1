@extends('adminlte::page')

@section('title', 'Update')

@section('content_header')
    <h1>Video Edit Form</h1>
@stop

@section('content')
    <div class="container mt-5">
        <div class="panel panel-primary">
            <div class="panel-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <video controls="true" autoplay="true">
                    <source src="{{ $url }}" type="video/mp4">
                </video>

                <form action="{{ route('update.video', $video->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-md-12">
                        <div class="col-md-6 form-group">
                            <label>Title:</label>
                            <input type="text" name="title" class="form-control" value="{{ $video->title }}"/>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Select new video to replace current video:</label>
                            <input type="file" name="video" class="form-control"/>
                        </div>
                        <div class="col-md-6 form-group">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop