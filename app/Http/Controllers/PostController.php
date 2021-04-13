<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdatePost;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('id', 'DESC')->paginate();

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
        $data = $request->all();

        if ($request->image->isValid()) {
            $nameFile = Str::of($request->title)->slug('-').'.'. $request->image->getClientOriginalExtension();
            $image = $request->image->storeAs('posts', $nameFile);
            $data['image'] = $image;
        }
        
        $post = Post::create($data);

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

        $data = $request->all();

        if ($request->image && $request->image->isValid()) {
            if (Storage::exists($post->image)) {
                Storage::delete($post->image);
            }
            
            $nameFile = Str::of($request->title)->slug('-').'.'. $request->image->getClientOriginalExtension();
            $image = $request->image->storeAs('posts', $nameFile);
            $data['image'] = $image;
        }

        $post->update($data);

        return redirect()
            ->route('posts.index')
            ->with('message', 'Post ATUALIZADO com Sucesso!');
    }

    public function destroy($id)
    {
        if(!$post = Post::find($id)){
            return redirect()->route('posts.index');

            if (Storage::exists($post->image)) {
                Storage::delete($post->image);
            }
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
                    ->paginate();
        
        return view('admin.posts.index', [
            'posts'     => $posts,
            'filters'   => $filters
        ]);
    }
}
