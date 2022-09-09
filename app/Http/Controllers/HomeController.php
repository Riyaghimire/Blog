<?php

namespace App\Http\Controllers;


use App\Http\Resources\Blog\BlogCollection;
use App\Models\Blog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $blogs=Blog::latest()->paginate(5);
      
        return response()->json([
             "blogs"=>new BlogCollection($blogs),
            ]);
    }

        
        }
       

    

   