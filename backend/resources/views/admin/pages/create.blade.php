@extends('admin.layout')

@section('title', 'New page')

@section('content')
    <form method="POST" action="{{ route('admin.pages.store') }}">
        @include('admin.pages._form')
    </form>
@endsection
