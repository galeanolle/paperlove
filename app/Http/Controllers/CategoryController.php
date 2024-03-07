<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categoriesParent=DB::table('categories')->where('id_parent',0)->get();

        $categoryList = array();

        foreach ($categoriesParent as $categoryParent){

            $arr = array($categoryParent->id,$categoryParent->id_parent,$categoryParent->name,$categoryParent->slug);
            array_push($categoryList,$arr);

            $categories=DB::table('categories')->where('id_parent',$categoryParent->id)->get();

            foreach ($categories as $category){
                $arr = array($category->id,$category->id_parent,$category->name,$category->slug);
                array_push($categoryList,$arr);

            }

        }

        return view('admin.category')->with('categoryList',$categoryList);
    }

    public function form()
    {
        $categories = Category::where('id_parent','=',0)->get();
        return view('admin.addcategory',compact('categories'));
    }

    public function create(Request $request)
    {
        
        $this->validate(request(),[
            'id_parent'=>'required',
            'name'=>'required'
        ]);


        $category = new Category();
        $category->name=request('name');
        $category->id_parent=request('id_parent');
        $category->slug = Str::slug(request('name'));
        $category->save();
    
        return redirect()->route('admin.category')->with('success','Successfully added the product!');
    }


    public function editform($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('id_parent','=',0)->get();
        return view('admin.editcategory',compact('category','categories'));
    }


    public function edit(Request $request,$id)
    {
        $this->validate(request(),[
            'id_parent'=>'required',
            'name'=>'required'
        ]);

        $category = Category::findOrFail($id);
        $category->name=request('name');
        $category->id_parent=request('id_parent');
        $category->slug = Str::slug(request('name'));
        $category->save();

        return redirect()->route('admin.category')->with('success','Successfully edited the product!');
        
    }

    public function remove($id)
    {   
        $category = Category::findOrFail($id);

        if($category->id_parent==0){
            $productsWIthCategory = Product::leftjoin('categories','products.category_id','=','categories.id')->where('categories.id_parent','=',$id)->update(['category_id'=>0]);
          //  $productsWIthCategory->update();

        }else{
            $productsWIthCategory = Product::where('category_id','=',$id)->update(['category_id'=>0]);

         //   $productsWIthCategory->category_id = 0;
         //   $productsWIthCategory->update();
        }

        Category::where('id',$id)->delete();
        
        return redirect()->route('admin.product')->with('success','Successfully removed the product!');
    }
}
