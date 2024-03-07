<?php

namespace App;

use App\Stock;
use App\VariantGroup;
use App\Variant;

class Cart
{
    public $items =[];
    public $totalQuantity = 0;
    public $totalPrice = 0;

    public function __construct($oldCart){
        if($oldCart){
            $this->items = $oldCart->items;
            $this->totalQuantity = $oldCart->totalQuantity; 
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    public function stockAvailable($product_id,$variant_id,$quantity){
        $itemQuantity = 0;
        for ($x=0; $x<count($this->items);$x++){
            if(isset($this->items[$x])){
                if($this->items[$x]['product_id'] == $product_id
                    && $this->items[$x]['variant_id'] == $variant_id){
                    $itemQuantity = $this->items[$x]['quantity'];
                }
            }
        }
        $itemQuantity = $itemQuantity + $quantity;

        $stock = Stock::where('product_id','=',$product_id)->where('variant_id','=',$variant_id)->first();
        if($itemQuantity>$stock->quantity){
            return false;
        }else{
            return true;
        }
    }

    public function add($item, $variant_id, $quantity){
        
        $variant = Variant::findOrFail($variant_id);
        $variantGroups = Variant::findOrFail($variant->variant_group_id);

        $storedItem = [
            'product_id'=>$item->id,
            'variant_id'=>$variant_id,
            'quantity'=>$quantity,
            'price'=>$item->price,
            'totalPrice'=>$item->price * $quantity,
            'item'=>$item,
            'id'=>0,
            'variant_name'=>$variant->name,
            'variant_group_name'=>$variantGroups->name
        ];

        $itemId = -1;
        for ($x=0; $x<count($this->items);$x++){
            if(isset($this->items[$x])){
                if($this->items[$x]['product_id'] == $item->id && $this->items[$x]['variant_id'] == $variant_id){
                    $itemId = $x;
                    break;
                }
            }
        }
        
        if($itemId==-1){
            array_push($this->items,$storedItem); 
            $this->items[count($this->items)-1]['id'] = count($this->items)-1;
        }else{
            $this->items[$itemId]['quantity'] += $quantity;
            $this->items[$itemId]['totalPrice'] =  $this->items[$itemId]['price'] * $this->items[$itemId]['quantity'];
        }
            
        $this->totalPrice = 0;
        $this->totalQuantity = 0;

        for ($x=0; $x<count($this->items);$x++){
            if(isset($this->items[$x])){
                $this->totalPrice += $this->items[$x]['totalPrice'];
                $this->totalQuantity += $this->items[$x]['quantity'];
            }
        }
        
    }

    public function remove($itemId){

        $this->totalPrice -= $this->items[$itemId]['totalPrice'];
        $this->totalQuantity -= $this->items[$itemId]['quantity'];
        unset($this->items[$itemId]);
        
    }

}