<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest();
        return view('admin.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::latest();
        return view('admin.post.add', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|min:5',
            'description' => 'required|min:5',
            'image' => 'required|file|between:0,2048|mimes:jpeg,jpg,png',
            'category_id' => 'required',
        ]);

        $filetype = $request->file('image')->extension();
        $text = Str::random(16) . '.' . $filetype;
        $data['image'] = Storage::putFileAs('images', $request->file('image'), $text);

        $data['slug'] = strtolower(str_replace(' ', '-', $data['title']));
        $data['user_id'] = Auth::user()->id;

        Post::create($data);

        return redirect('/dashboard/articles')->with('success', 'Artikel Ditambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::latest();
        return view('admin.post.add', compact('categories', 'post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|min:5',
            'description' => 'required|min:5',
            'image' => 'required|file|between:0,2048|mimes:jpeg,jpg,png',
            'category_id' => 'required',
        ]);

        if ($request['image']) {
            Storage::delete($post->image);
            $filetype = $request->file('image')->extension();
            $text = Str::random(16) . '.' . $filetype;
            $data['image'] = Storage::putFileAs('images', $request->file('image'), $text);
        }

        $data['slug'] = strtolower(str_replace(' ', '-', $data['title']));

        $post->update($data);

        return redirect('/dashboard/articles')->with('success', 'Artikel Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::delete($post->image);
        }
        $post->delete();
        return redirect('/dashboard/articles')->with('success', 'Artikel Dihapus');
    }
}
