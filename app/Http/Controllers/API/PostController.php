<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts'] = Post::all();
         return response()->json([
                'status'=>true,
                'message'=> 'all post fetched',
                'data' =>$data
              
              ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validePost = Validator::make(
                $request->all(),
                [
                    'title'=>'required',
                    'description'=>'required',
                     'image'=>'required'
                    
                ]
            );


        if($validePost->fails()){
              return response()->json([
                'status'=>false,
                'message'=> 'Validation error',
                'errors'=> $validePost->errors()->all()
              ],401);
        }    

          $img = $request->image;
          $ext = $img->extension();
          $imageName = time(). '.' .$ext;
          $img->move(public_path(). '/uploads', $imageName);


      $post = Post::create([
        'title'=>$request->title,
        'description'=>$request->description,
        'image'=>$imageName,
      ]);

      return response()->json([
                'status'=>true,
                'message'=> 'Post created Successfully',
                'post'=> $post
              ],200);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['post'] = Post::where(['id'=>$id])->get();
        return response()->json([
                'status'=>true,
                'message'=> 'single post fetched',
                'data' =>$data
              
              ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validePost = Validator::make(
                $request->all(),
                [
                    'title'=>'required',
                    'description'=>'required',
                     'image'=>'required'
                    
                ]
            );


        if($validePost->fails()){
              return response()->json([
                'status'=>false,
                'message'=> 'Validation error',
                'errors'=> $validePost->errors()->all()
              ],401);
        }    

          $img = $request->image;
          $ext = $img->getClientOriginalExtension();
          $imageName = time(). '.' .$ext;
          $img->move(public_path(). '/uploads', $imageName);


      $post = Post::where(['id'=>$id])->update([
        'title'=>$request->title,
        'description'=>$request->description,
        'image'=>$imageName,
      ]);

      return response()->json([
                'status'=>true,
                'message'=> 'Post update Successfully',
                'post'=> $post
              ],200); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
