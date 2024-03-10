<?php

namespace App\Http\Controllers;

use App;
use App\Product;
use App\Stock;
use App\Cart;
use App\Category;
use App\Variant;
use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index($category='',$subcategory='')
    {
        $products = null;
        $categories = null;
        $variantGroups = null;
        $categoryName = null;
        $subcategoryName = null;
        $order = request('order');
        $variants = request('variants');
        if($variants!=''){
            $variants = explode(",",$variants);
        }

        // Todos los productos
        if($category==''&&$subcategory==''){
            $products = Product::leftjoin('stocks','products.id','=','stocks.product_id')->select(
                    'products.id as product_id',
                    'products.name as product_name',
                    'products.price as product_price',
                    'products.image as product_image',
                    'products.slug as product_slug'
                )->selectRaw("IFNULL(SUM(stocks.quantity),0) as total_stock")->groupBy('products.id','products.name','products.price','products.image','products.slug');
            // variantes
            if(is_array($variants))
                if(count($variants)>0)
                    $products = $products->whereIn('stocks.variant_id',$variants);

            if($order!=''){
                if($order=='price-ascending')
                    $products = $products->orderBy('products.price','asc');                    
                if($order=='price-descending')
                    $products = $products->orderBy('products.price','desc');
                if($order=='alpha-ascending')
                    $products = $products->orderBy('products.name','asc');
                if($order=='alpha-descending')
                    $products = $products->orderBy('products.name','desc');
                if($order=='created-descending')
                    $products = $products->orderBy('products.id','desc');
                if($order=='created-ascending')
                    $products = $products->orderBy('products.id','asc');
                if($order=='best-selling')
                    $products = $products->orderBy('stocks.quantity','asc');
            }


            $products = $products->paginate(30)->appends(request()->query());

            $categories = Category::where('id_parent','=',0)->get();
            $variantGroups = Variant::leftjoin('variant_groups','variants.variant_group_id','=','variant_groups.id')
                ->leftjoin('stocks','variants.id','=','stocks.variant_id')
                ->select('variant_groups.name as variant_group_name',
                         'variants.id as variant_id',
                         'variants.name as variant_name'
                )
                ->selectRaw("IFNULL(SUM(stocks.quantity),0) as total_stock")->groupBy('variant_groups.name','variants.id','variants.name')
                ->orderBy('variants.variant_group_id','DESC')
                ->get();

        }

        // Los productos de la categoria padre y mostrar sus subcategorias
        if($category!=''&&$subcategory==''){

            $existCategory = Category::where('slug','=',$category)->where('id_parent','=',0)->get();
            if($existCategory->count()>0){
                // filtrar productos que tengan categorias hijas con la categoria padre actual
                $products = Product::leftjoin('categories','products.category_id','=','categories.id')
                ->leftjoin('stocks','products.id','=','stocks.product_id')
                ->where('categories.id_parent','=', $existCategory->first()->id)
                //->Where('products.category_id','=',$existCategory->first()->id)
                ->select(
                    'products.id as product_id',
                    'products.name as product_name',
                    'products.price as product_price',
                    'products.image as product_image',
                    'products.slug as product_slug'
                )->selectRaw("IFNULL(SUM(stocks.quantity),0) as total_stock")->groupBy('products.id','products.name','products.price','products.image','products.slug');

                // variantes
                if(is_array($variants))
                    if(count($variants)>0)
                        $products = $products->whereIn('stocks.variant_id',$variants);

                if($order!=''){
                    if($order=='price-ascending')
                        $products = $products->orderBy('products.price','asc');                    
                    if($order=='price-descending')
                        $products = $products->orderBy('products.price','desc');
                    if($order=='alpha-ascending')
                        $products = $products->orderBy('products.name','asc');
                    if($order=='alpha-descending')
                        $products = $products->orderBy('products.name','desc');
                    if($order=='created-descending')
                        $products = $products->orderBy('products.id','desc');
                    if($order=='created-ascending')
                        $products = $products->orderBy('products.id','asc');
                    if($order=='best-selling')
                        $products = $products->orderBy('stocks.quantity','asc');
                }

                $products = $products->paginate(30)->appends(request()->query());



                $categories = Category::where('id_parent','=',$existCategory->first()->id)->get();
                $categoryName = $existCategory->first()->name;
                $variantGroups = Variant::leftjoin('variant_groups','variants.variant_group_id','=','variant_groups.id')
                ->leftjoin('stocks','variants.id','=','stocks.variant_id')
                ->leftjoin('products','products.id','=','stocks.product_id')
                ->leftjoin('categories','products.category_id','=','categories.id')
                ->select('variant_groups.name as variant_group_name',
                         'variants.id as variant_id',
                         'variants.name as variant_name'
                )
                ->where('categories.id_parent','=', $existCategory->first()->id)
                ->selectRaw("IFNULL(SUM(stocks.quantity),0) as total_stock")->groupBy('variant_groups.name','variants.id','variants.name')
                ->orderBy('variants.variant_group_id','DESC')
                ->get();
            }else{
                // no hay productos con dicho slug de categoria
            }


        }

        // los productos de la categoria padre y de su subcategoria
        if($category!=''&&$subcategory!=''){

            $existCategory = Category::where('slug','=',$category)->where('id_parent','=',0)->get();
            if($existCategory->count()>0){
                $categoryName = $existCategory->first()->name;
                $existSubCategory = Category::where('slug','=',$subcategory)->where('id_parent','=',$existCategory->first()->id)->get();
                if($existSubCategory->count()>0){

                    $subcategoryName = $existSubCategory->first()->name;
                    // filtrar productos que tengan la categoria hija y la padre seleccionada
                    $products = Product::leftjoin('stocks','products.id','=','stocks.product_id')
                    ->where('products.category_id','=',$existSubCategory->first()->id)
                    ->select(
                    'products.id as product_id',
                    'products.name as product_name',
                    'products.price as product_price',
                    'products.image as product_image',
                    'products.slug as product_slug'
                    )->selectRaw("IFNULL(SUM(stocks.quantity),0) as total_stock")->groupBy('products.id','products.name','products.price','products.image','products.slug');
                    // variantes
                    if(is_array($variants))
                        if(count($variants)>0)
                            $products = $products->whereIn('stocks.variant_id',$variants);


                    if($order!=''){
                        if($order=='price-ascending')
                            $products = $products->orderBy('products.price','asc');                    
                        if($order=='price-descending')
                            $products = $products->orderBy('products.price','desc');
                        if($order=='alpha-ascending')
                            $products = $products->orderBy('products.name','asc');
                        if($order=='alpha-descending')
                            $products = $products->orderBy('products.name','desc');
                        if($order=='created-descending')
                            $products = $products->orderBy('products.id','desc');
                        if($order=='created-ascending')
                            $products = $products->orderBy('products.id','asc');
                        if($order=='best-selling')
                            $products = $products->orderBy('stocks.quantity','asc');
                    }

                    $products = $products->paginate(30)->appends(request()->query());

                    $variantGroups = Variant::leftjoin('variant_groups','variants.variant_group_id','=','variant_groups.id')
                    ->leftjoin('stocks','variants.id','=','stocks.variant_id')
                    ->leftjoin('products','products.id','=','stocks.product_id')
                    ->select('variant_groups.name as variant_group_name',
                             'variants.id as variant_id',
                             'variants.name as variant_name'
                    )
                    ->where('products.category_id','=',$existSubCategory->first()->id)
                    ->selectRaw("IFNULL(SUM(stocks.quantity),0) as total_stock")->groupBy('variant_groups.name','variants.id','variants.name')
                    ->orderBy('variants.variant_group_id','DESC')
                    ->get();

                }else{
                    // no hay productos con dicho slug de categoria y subcategoria erronea
                }
                
            }else{
                // no hay productos con dicho slug de categoria
            }
        }





        return view('products.index',compact(['categories','products','variantGroups']))->with('category',$category)->with('categoryName',$categoryName)->with('subcategory',$subcategory)->with('subcategoryName',$subcategoryName)->with('categoryList',$this->getCategoryList());
    }

    public function filter(Request $request)
    {
        if($request->ajax())
        {
            $products= Product::where('quantity','>',0);
            $query = json_decode($request->get('query'));
            $price = json_decode($request->get('price'));
            
            if(!empty($query))
            {
                $products= $products->where('name','like','%'.$query.'%');        
            }
            if(!empty($price))
            {
                $products= $products->where('price','<=',$price);
            }
            $products=$products->get();
            

            $total_row = $products->count();
            if($total_row>0)
            {
                $output ='';
                foreach($products as $product)
                {
                    $output .='
                    <div class="col-lg-4 col-md-6 col-sm-12 pt-3">
                        <div class="card">
                            <a href="product/'.$product->id.'">
                                <div class="card-body ">
                                    <div class="product-info">
                                    
                                    <div class="info-1"><img src="'.asset('/storage/'.$product->image).'" alt=""></div>
                                    <div class="info-2"><h4>'.$product->name.'</h4></div>
                                    <div class="info-3"><h5>$ '.$product->price.'</h5></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                    </div>
                    ';
                }
            }
            else
            {
                $output='
                <div class="col-lg-4 col-md-6 col-sm-6 pt-3">
                    <h4>No Data Found</h4>
                </div>
                ';
            }
            $data = array(
                'table_data'    =>$output
            );
            echo json_encode($data);
        
        }
    }

    public function show($id,$slug)
    {   
        $variants = Stock::leftjoin('variants','stocks.variant_id','=','variants.id')->where('product_id','=',$id)->get();
        $product = Product::findOrFail($id);

        $subcategory = Category::findOrFail($product->category_id);
        $category = Category::findOrFail($subcategory->id_parent);

        $totalquantity = 0;

        foreach($variants as $variant){
            if($variant->quantity>0)
                $totalquantity +=$variant->quantity;
        }


        return view('products.show', compact ('product','variants','subcategory','category','totalquantity'));
    }

    private function getCategoryList(){
        $categoriesParent=DB::table('categories')->where('id_parent',0)->get();

        $categoryList = array();

        foreach ($categoriesParent as $categoryParent){

            $arr = array($categoryParent->id,$categoryParent->id_parent,$categoryParent->name,$categoryParent->slug);
            array_push($categoryList,$arr);

            $categories=DB::table('categories')->where('id_parent',$categoryParent->id)->get();

            foreach ($categories as $category){
                $arr = array($category->id,$category->id_parent,$category->name,$categoryParent->slug,$category->slug);
                array_push($categoryList,$arr);

            }

        }
        return $categoryList;
    }

    public function form()
    {
        return view('admin.addproduct')->with('categoryList',$this->getCategoryList());
    }

    public function create(Request $request)
    {
        $this->validate(request(),[
            'image'=>'required|image',
            'name'=>'required|string',
            'price'=>'required',
            'category_id'=>'required|integer',
        ]);

        $imagepath = $request->image->store('products','public');
        
        $product = new Product();
        $product->name=request('name');
        $product->price=request('price');
        $product->category_id=request('category_id');
        $product->image=$imagepath;
        $product->slug=Str::slug(request('name'));
        $product->save();
        // DB:: table('products')->insert($product);
        return redirect()->route('admin.product')->with('success','Successfully added the product!');
    }
    
    public function editform($id)
    {
        $product = Product::findOrFail($id);
        $stock = Stock::leftjoin('variants','stocks.variant_id','=','variants.id')->leftjoin('variant_groups','variant_groups.id','=','variants.variant_group_id')->where('stocks.product_id','=',$id)

        ->select('variant_groups.name as group_name', 'variants.name as variant_name','stocks.quantity')->orderBy('variant_groups.id','desc')->get();

        return view('admin.editproduct',compact('product','stock'))->with('categoryList',$this->getCategoryList());;
    }

    public function edit(Request $request,$id)
    {
        $this->validate(request(),[
            'image'=>'',
            'name'=>'required|string',
            'price'=>'required',
            'category_id'=>'required|integer',
        ]);
        if(request('image'))
        {
            $imagepath = $request->image->store('products','public');
            $product = Product::findOrFail($id);
            
            $product->name=request('name');
            $product->price=request('price');
            $product->category_id=request('category_id');
            $product->image=$imagepath;
            $product->slug=Str::slug(request('name'));
            $product->save();
        }
        else
        {
            $product = Product::findOrFail($id);
            $product->name=request('name');
            $product->price=request('price');
            $product->category_id=request('category_id');
            $product->slug=Str::slug(request('name'));
            $product->save();
        }
        return redirect()->route('admin.product')->with('success','Successfully edited the product!');
        
    }
    
    public function remove($id)
    {
        Product::where('id',$id)->delete();
        Stock::where('product_id',$id)->delete();
        return redirect()->route('admin.product')->with('success','Successfully removed the product!');
    }

    public function list()
    {
        $products = Product::orderBy('id', 'desc')
        ->leftjoin('categories','products.category_id','=','categories.id')
        ->leftjoin('categories as c2','categories.id_parent','=','c2.id')
        ->where('products.name','LIKE','%'.request('search').'%')
        ->select(
               'products.id as id',
               'products.image as image',
               'products.name as name',
               'products.price as price',
               'c2.name as parent',
               'categories.name as category',
               'categories.id as category_id',
            )->paginate(15)->appends(request()->query());
        //dd($products);
        return view('admin.product', compact ('products'))->with('search',request('search'));
    }

    public function duplicate($id){

        $product = Product::findOrFail($id);

        $productNew = new Product();
        $productNew->name = '[Duplicado] - '.$product->name;
        $productNew->price = $product->price;
        $productNew->category_id = $product->category_id;
        $productNew->image = $product->image;
        $productNew->slug = $product->slug;
        $productNew->quantity = $product->quantity;
        $productNew->save();

        $stocks = Stock::where('product_id','=',$product->id)->get();

        foreach($stocks as $stock){
            $stocksNew = new Stock();
            $stocksNew->product_id = $productNew->id;
            $stocksNew->variant_id = $stock->variant_id;
            $stocksNew->quantity = $stock->quantity;
            $stocksNew->save();
        }   

        return redirect()->route('admin.product')->with('success','El producto fue duplicado correctamente!');

    }


}
