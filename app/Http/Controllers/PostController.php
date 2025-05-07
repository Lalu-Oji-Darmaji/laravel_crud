<?php
namespace App\Http\Controllers;

use App\Models\Post;

use Illuminate\View\View;

use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    
    public function index() : View
    {
        $post = Post::get();
        return view('/posts/index', compact('post'));
    }

    public function create(): View
    {
        return view('posts.create');
    }

   
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:3',
            'content'       => 'required|min:5'
        ]);

        $image = $request->file('image');
        $image->storeAs('posts', $image->hashName());

        Post::create([
            'image'         => $image->hashName(),
            'title'         => $request->title,
            'content'       => $request->content
        ]);

        return redirect()->route('list.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id): View
    {
        $post = Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }

    public function edit(string $id): View
    { 
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'image'         => 'image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:3',
            'content'       => 'required|min:5'
        ]);

        $post = Post::findOrFail($id);

        if ($request->hasFile('image')) {

            Storage::delete('posts/'.$post->image);

            $image = $request->file('image');
            $image->storeAs('posts', $image->hashName());

            $post->update([
                'image'         => $image->hashName(),
                'title'         => $request->title,
                'content'       => $request->content
            ]);

        } else {

            $post->update([
                'title'         => $request->title,
                'content'       => $request->content
            ]);
        }

        return redirect()->route('list.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy($id): RedirectResponse
    {
        $post = Post::findOrFail($id);
        Storage::delete('posts/'. $post->image);
        $post->delete();
        return redirect()->route('list.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}