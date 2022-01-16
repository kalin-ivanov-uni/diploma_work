<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminPostController extends Controller
{
    public  function index()
    {

        return view('user.posts.index',[
            'posts' => Post::where('user_id',auth()->id())->paginate(10),
        ]);
    }

    public function create()
    {
        return view('user.posts.create');
    }

    public function store()
    {
        $attributes  = $this->validatePost(new Post);

        $attributes['user_id'] = auth()->id();
        $attributes['thumbnail'] = \request()->file('thumbnail')->store('thumbnails');

        if(request('longitude') === null || request('latitude') === null)
        {
            return back()->with('error',['gmap' => 'Please choose a location of the signal']);
        }
        $attributes['longitude'] = \request('longitude');
        $attributes['latitude']  = \request('latitude');

        Post::create($attributes);
        return redirect('/');
    }

    public function edit(Post $post)
    {
        if($post->user_id != auth()->id())
        {
            return redirect('/user/posts')->with('error','This post is not associated to your profile');
        }
        return view('user.posts.edit',['post'=> $post]);
    }

    public function update(Post $post)
    {
        $attributes  = $this->validatePost($post);

        if(isset($attributes['thumbnail']))
        {
            $attributes['thumbnail'] = \request()->file('thumbnail')->store('thumbnails');
        }

        if(request('longitude') === null || request('latitude') === null)
        {
            return back()->with('error',['gmap' => 'Please choose a location of the signal']);
        }

        $attributes['longitude'] = \request('longitude');
        $attributes['latitude']  = \request('latitude');

        $post->update($attributes);

        return back()->with('success','Post Updated');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success','Post Deleted');
    }

    protected  function validatePost(?Post $post = null) : array
    {
        $post  = $post ?? null;
//        Str::slug(\request('title')

       return request()->validate([
            'title' => 'required',
            'thumbnail' => $post->exists ? ['image'] : ['required','image'],
            'slug' => ['required',Rule::unique('posts','slug')->ignore($post)],
            'excerpt'  => 'required',
            'body'  => 'required',
            'category_id' => ['required',Rule::exists('categories','id')]
        ]);
    }


}
