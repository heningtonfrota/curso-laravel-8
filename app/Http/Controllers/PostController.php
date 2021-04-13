<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdatePost;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('id', 'DESC')->paginate(1);

        //dd($posts);

        return view('admin.posts.index', [
            'posts' => $posts,
        ]);
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(StoreUpdatePost $request)
    {
        $post = Post::create($request->all());

        return redirect()
            ->route('posts.index')
            ->with('message', 'Post Criado com Sucesso!');
    }
    
    public function show($id)
    {
        if(!$post = Post::find($id)){
            return redirect()->route('posts.index');
        }

        return view('admin.posts.show', [
            'post' => $post
        ]);
    }

    public function edit($id)
    {
        if(!$post = Post::find($id)){
            return redirect()->back();
        }

        return view('admin.posts.edit', [
            'post' => $post
        ]);
    }

    public function update(StoreUpdatePost $request, $id)
    {
        if(!$post = Post::find($id)){
            return redirect()->back();
        }
        $post->update($request->all());

        return redirect()
            ->route('posts.index')
            ->with('message', 'Post ATUALIZADO com Sucesso!');
    }

    public function destroy($id)
    {
        if(!$post = Post::find($id)){
            return redirect()->route('posts.index');
        }

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('message', 'Post Deletado com Sucesso!');
    }

    public function search(Request $request)
    {
        $filters = $request->except('_token');

        $posts = Post::where('title', 'LIKE', "%{$request->search}%")//where('title', '=', $request->search)
                    ->orWhere('content', 'LIKE', "%{$request->search}%")
                    ->paginate(1);
        
        return view('admin.posts.index', [
            'posts'     => $posts,
            'filters'   => $filters
        ]);
    }
}
