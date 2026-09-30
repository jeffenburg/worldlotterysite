@extends('admin.layout')

@section('title', 'Edit page')

@section('actions')
    <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Delete “{{ addslashes($page->title) }}”? This cannot be undone.');">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.pages.update', $page) }}">
        @method('PUT')
        @include('admin.pages._form')
    </form>
@endsection
