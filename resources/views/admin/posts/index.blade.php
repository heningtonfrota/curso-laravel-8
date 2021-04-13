@extends('admin.layouts.app')

@section('title', 'Listagem dos Posts')

@section('content')
    <a href="{{ route('posts.create') }}">Novo Post</a>
    <hr>
    @if (session('message'))
        <div>
            {{ session('message') }}
        </div>
    @endif

    <form action="{{ route('posts.search') }}" method="post">
        @csrf
        <input type="text" name="search" placeholder="Pesquisar:">
        <button type="submit">Filtrar</button>
    </form>
    <h1>Posts</h1>

    @foreach ($posts as $post)
        <h2>
            {{ $post->title }} 
            [
                <a href="{{ route('posts.show', $post->id) }}">Ver</a> |
                <a href="{{ route('posts.edit', $post->id) }}">Edit</a>
            ]
        </h2>      
    @endforeach

    <hr>
    @if (isset($filters))
        {{ $posts->appends($filters)->links() }}
    @else
        {{ $posts->links() }}
    @endif
@endsection