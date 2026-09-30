@extends('admin.layout')

@section('title', 'Edit post')

@section('actions')
    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete “{{ addslashes($post->title) }}”? This cannot be undone.');">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.posts._form')
    </form>
@endsection
