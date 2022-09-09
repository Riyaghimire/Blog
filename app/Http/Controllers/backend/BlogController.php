<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\BlogCollection;
use App\Http\Resources\Blog\BlogResource;
use App\Models\Blog;
use App\Models\Btag;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BlogController extends BaseController
{
    protected $folder = "Blogs";
    protected $folder_path;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->folder_path = 'image'.DIRECTORY_SEPARATOR.$this->folder;
    }
    public function index()
    {
        return new BlogCollection(Blog::paginate(8));
    }

    /**
      * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|unique:blogs,title',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description'=>'required',
            'btag_id' => 'required',
           
        ]);

        $input = $request->all();

        if ($request->hasFile('image')) {
            // $this->folder_path = $this->folder.DIRECTORY_SEPARATOR.$request->title;
            $file_name = $this->processImage($request->file('image'));
            $input['image'] = $file_name;
        }

        $blog = Blog::create($input);
        return response()->json([
            'success' => 'Blog saved!',
            'blog' => new BlogResource($blog)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new BlogResource(Blog::FindOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $request->validate([
            'title' => 'required|max:255|unique:blogs,title,'.$id,
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description'=>'required',
            'btag_id' => 'required',
            
        ]);
        $blog = Blog::findOrFail($id);
        $input = $request->all();
        
        if ($request->hasFile(['image'])) {
            // $this->folder_path = $this->folder.DIRECTORY_SEPARATOR.$request->title;
            $file_name = $this->processImage($request->file('image'),$blog->image);
            $input['image'] = $file_name;
            }
        

        $blog->title =  $request->get('title');
        $blog->image = $input['image'];
        $blog->description = $request->get('description');
        $blog->excerpt= $request->get('excerpt');
        

        $blog->save();
        return response()->json([
            'success' => 'Blog update!',
            "blog" => new BlogResource($blog)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $this->deleteImage($blog->image);
        
        $blog->delete();
        return response()->json(['danger' => 'Removed.']);
    }
}

