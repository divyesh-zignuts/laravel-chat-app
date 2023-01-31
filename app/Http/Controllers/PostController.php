<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PostCreated;
use App\Models\Post;

class PostController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('posts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // dd($request->all());
        $this->validate($request, [
            'title' => 'required|unique:posts,title',
            'message' => 'required',
        ]);

        $post = Post::create(['title' => $request->title,'message' => $request->message]);

        $data = ['title' => $request->title,'message' => $request->message];

        event(new PostCreated($data));
        return redirect()->route('posts.index')
                        ->with('success','Post created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        return view('posts.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        return view('posts.edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'message' => 'required',
        ]);

        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->message = $request->input('message');
        $post->save();

        return redirect()->route('posts.index')
                        ->with('success','Post updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Post::where('id',$id)->delete();
        return redirect()->route('posts.index')
                        ->with('success','Post deleted successfully');
    }
}
