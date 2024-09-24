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
    
        return redirect()->route('admin.category')->with('success','La categoría se agrego correctamente');
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

        $message = Array('Se editó la categoria correctamente.');

        if(request('price')!=''){
            if(request('id_parent')==0){
                $categories = Category::select('id','name')->where('id_parent','=',$id)->get();
                foreach ($categories as $category) {
                    $totalProducts = Product::where('category_id','=',$category->id)->count();
                    Product::where('category_id','=',$category->id)->update(['price'=>request('price')]);
                    array_push($message, "Se actualizo el precio $".request('price')." a ".$totalProducts." productos  asociado/s a la categoria '".request('name').">".$category->name."'");
                }
            }else{
                $nameCategoryParent = Category::select('name')->where('id','=',request('id_parent'))->first()->name;
                $totalProducts = Product::where('category_id','=',$id)->count();
                Product::where('category_id','=',$id)->update(['price'=>request('price')]);
                array_push($message,"Se actualizo el precio $".request('price')." a ".$totalProducts." productos asociado/s a la categoria '".$nameCategoryParent.">".request('name')."'");
            }
        }

        if(request('percent')!=''){
            if(request('id_parent')==0){
                $categories = Category::select('id','name')->where('id_parent','=',$id)->get();
                foreach ($categories as $category) {
                    $totalProducts = Product::where('category_id','=',$category->id)->count();
                    Product::where('category_id','=',$category->id)->update(['percent'=>request('percent')]);
                    array_push($message, "Se actualizo el porcentaje de descuento ".request('percent')."% a ".$totalProducts." productos  asociado/s a la categoria '".request('name').">".$category->name."'");
                }
            }else{
                $nameCategoryParent = Category::select('name')->where('id','=',request('id_parent'))->first()->name;
                $totalProducts = Product::where('category_id','=',$id)->count();
                Product::where('category_id','=',$id)->update(['percent'=>request('percent')]);
                array_push($message,"Se actualizo el porcentaje de descuento ".request('percent')."% a ".$totalProducts." productos asociado/s a la categoria '".$nameCategoryParent.">".request('name')."'");
            }
        }
        
        

        return redirect()->route('admin.category')->with('success',$message);
        
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
