<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFormRequest;

class CategoryController extends Controller
{
    public function index(){
        return view('admin.category.index');
    }

    // Category Create Function
    public function create(){
        return view('admin.category.create');
    }
    public function store(CategoryFormRequest $request){
        //I get all the data in here after validation
        $validatedData = $request->validated();

        //dd($validatedData);

        $category = new Category();
        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['slug']);
        $category->description = $validatedData['description'];

        if($request->hasFile('image')){
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $fileName = time().'.'.$ext;

            //dd($fileName);
            $file->move('upload/category/',$fileName);

            $category->image = $fileName;
        }
        $category->image = $validatedData['image'];

        $category->meta_title = $validatedData['meta_title'];
        $category->meta_keyword = $validatedData['meta_keyword'];
        $category->meta_description = $validatedData['meta_description'];

        // Status we take it directly
        $category->status = $request->status == true ? '1':'0';

        // Saving Category
        DB::enableQueryLog();
        $category->save();
        DB::getQueryLog();

        return redirect('admin/category')->with('message','Category Added Successfully');



    }
}