@extends('admin.layouts.app')

@section('title', "Editar Post: $post->title")

@section('content')
    <h1>Editar o Post <strong>{{ $post->title }}</strong></h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>    
    @endif

    <form action="{{ route('posts.update', $post->id) }}" method="POST">    
        @method('put')
        @include('admin.posts._partials.form')
    </form>
@endsection
