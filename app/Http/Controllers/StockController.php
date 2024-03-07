<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Product;
use App\Variant;
use App\VariantGroup;
use Illuminate\Http\Request;
//use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    public function index($id)
    {
        $product = Product::find($id);
        $stock = Stock::leftjoin('variants','stocks.variant_id','=','variants.id')->
            leftjoin('variant_groups','variant_groups.id','=','variants.variant_group_id')->
            where('product_id',$id)->select(
               'stocks.id as stocks_id',
               'stocks.variant_id as variant_id',
               'stocks.quantity as stocks_quantity',
               'variants.name as variant_name',
               'variant_groups.name as variant_group_name'
            );
       
        $stock=$stock->orderBy('stocks_id', 'DESC')->get();

        return view('admin.stock',compact('stock','product'));
    }

    public function addform($id)
    {
        $product = Product::find($id);
        return view('admin.addstock',compact('product'));
    }

    public function addstock($id)
    {
        $this->validate(request(),[
            'name'=>'required|string',
            'quantity'=>'required|integer',
        ]);


        $variants = new Variant();
        $variants->variant_group_id = 1; // Sin agrupar
        $variants->name = request('name');
        $variants->save();

        $stock = new Stock();
        $stock->product_id=$id;
        $stock->variant_id=$variants->id;
        $stock->quantity=request('quantity');
        $stock->save();

        return redirect()->route('admin.stock',['id'=>$id])->with('success','Successfully added the product!');
    }


    public function addformgroup($id)
    {
        $product = Product::find($id);
        $groups = VariantGroup::where('id','>','1')->get(); // todos los grupos menos el generico
        return view('admin.addstockgroup',compact('product','groups'));
    }

    public function addformvariant($id)
    {
        $product = Product::find($id);
        $variants = Variant::where('variant_group_id','=','1')->get(); // todo los modelos del grupo 1 sin asignar
        return view('admin.addstockvariant',compact('product','variants'));
    }

    public function addstockvariant($id)
    {
        $this->validate(request(),[
            'variant_id'=>'required|integer',
            'quantity'=>'required|integer',
        ]);

        if(Stock::where('product_id','=',$id)->where('variant_id','=',request('variant_id'))->count()==0){

            $stock = new Stock();
            $stock->product_id=$id;
            $stock->variant_id=request('variant_id');
            $stock->quantity=request('quantity');
            $stock->save(); 
            
            return redirect()->route('admin.stock',['id'=>$id])->with('success','Successfully added the product!');

        }else{

            return redirect()->back()->withErrors(["variant_exists" =>'El modelo seleccionado ya esta asignado al producto!'])->withInput();

        }

        
    }


    public function addstockgroup($id)
    {
        $this->validate(request(),[
            'group'=>'required|integer',
            'quantity'=>'required|integer',
        ]);

        $variants = Variant::where('variant_group_id','=',request('group'))->get();

        foreach($variants as $variant){
            if(Stock::where('product_id','=',$id)->where('variant_id','=',$variant->id)->count()==0){
                $stock = new Stock();
                $stock->product_id=$id;
                $stock->variant_id=$variant->id;
                $stock->quantity=request('quantity');
                $stock->save(); 
            }
        }  

        return redirect()->route('admin.stock',['id'=>$id])->with('success','Successfully added the product!');
    }


    public function savestock($id){

        $jsonData = request('stockinfo');
        $jsonData = json_decode($jsonData);

        foreach($jsonData as $item){
            $stockItem = Stock::where('id','=',$item[0])->first();
            $stockItem->quantity = $item[1];
            $stockItem->save();
        }
        
        return redirect()->route('admin.stock',['id'=>$id])->with('success','Successfully added the product!');
    }


    public function remove($id,$stock)
    {
        Stock::where('id','=',$stock)->delete();

        return redirect()->route('admin.stock',['id'=>$id])->with('success','Successfully removed the product!');
    }


}