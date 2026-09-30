@extends('admin.layout')

@section('title', 'Edit lottery')

@section('actions')
    <form method="POST" action="{{ route('admin.lotteries.destroy', $lottery) }}" onsubmit="return confirm('Delete “{{ addslashes($lottery->name) }}”? This also removes its draw history and cannot be undone.');">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.lotteries.update', $lottery) }}">
        @method('PUT')
        @include('admin.lotteries._form')
    </form>
@endsection
