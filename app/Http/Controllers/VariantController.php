<?php

namespace App\Http\Controllers;

use App\Variant;
use App\VariantGroup;
use App\Stock;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function index()
    {
        $groups = VariantGroup::all();
        return view('admin.variant',compact('groups'));
    }

    public function form()
    {
        return view('admin.addvariant');
    }

    public function create(Request $request)
    {
        $this->validate(request(),[
            'name'=>'required',
            'variant_info'=>'required'
        ]);


        $jsonData = request('variant_info');
        $jsonData = json_decode($jsonData);

        $group = new VariantGroup();
        $group->name=request('name');
        $group->save();

        foreach($jsonData as $item){
            $variant = new Variant();
            $variant->variant_group_id = $group->id;
            $variant->name = $item;
            $variant->save();
        }
        
        return redirect()->route('admin.variant')->with('success','Successfully added the product!');
    }


    public function editform($id)
    {
        $group = VariantGroup::findOrFail($id);
        $variants = Variant::where('variant_group_id','=',$id)->get();
        return view('admin.editvariant',compact('group','variants'));
    }


    public function edit(Request $request,$id)
    {
        $this->validate(request(),[
            'name'=>'required',
            'variant_info'=>'required'
        ]);


        $jsonData = request('variant_info');
        $jsonData = json_decode($jsonData);

        $jsonDataDeleted = request('variant_info_deleted');
        $jsonDataDeleted = json_decode($jsonDataDeleted);

        $group = VariantGroup::findOrFail($id);
        $group->name=request('name');
        $group->save();

        $variantOfGroup = Variant::where('variant_group_id','=',$id)->first();
        $productsWithVariant = Stock::where('variant_id','=',$variantOfGroup->id)->select('product_id')->groupBy('product_id')->get();

        $newVariants = array();

        foreach($jsonData as $item){

            if(Variant::where('id','=',$item[0])->count()==0){
                $variant = new Variant();
                $variant->variant_group_id = $id;
                $variant->name = $item[1];
                $variant->save();
                array_push($newVariants,$variant->id);
            }else{
                $variant = Variant::where('id','=',$item[0])->first();
                $variant->variant_group_id = $id;
                $variant->name = $item[1];
                $variant->save();
            }

        }
        

        foreach($productsWithVariant as $item){
            foreach($newVariants as $new){
                $stock = new Stock();
                $stock->product_id = $item->product_id;
                $stock->variant_id = $new;
                $stock->quantity = 0;
                $stock->save(); 
            }
        }
       
        foreach($jsonDataDeleted as $item){
            Variant::where('id','=',$item)->delete();
            Stock::where('variant_id','=',$item)->delete();
        }
        

        return redirect()->route('admin.variant')->with('success','Successfully added the product!');
        
    }

    public function remove($id)
    {   
        $variants = Variant::where('variant_group_id','=',$id)->get();
        foreach($variants as $variant){
            Variant::where('id','=',$variant->id)->delete();
            Stock::where('variant_id','=',$variant->id)->delete();
        }
        VariantGroup::findOrFail($id)->delete();
        return redirect()->route('admin.variant')->with('success','Successfully removed the product!');
    }
}
