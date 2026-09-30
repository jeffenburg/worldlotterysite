@extends('admin.layout')

@section('title', 'New post')

@section('content')
    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
        @include('admin.posts._form')
    </form>
@endsection
