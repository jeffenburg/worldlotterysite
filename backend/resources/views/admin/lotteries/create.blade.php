@extends('admin.layout')

@section('title', 'New lottery')

@section('content')
    <form method="POST" action="{{ route('admin.lotteries.store') }}">
        @include('admin.lotteries._form')
    </form>
@endsection
