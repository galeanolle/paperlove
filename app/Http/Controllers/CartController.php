<?php

namespace App\Http\Controllers;
use App;
use App\Product;
use App\Cart;
use Session;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        if($request->ajax())
        {   

            $result = true;
            $message = '';
            $product_id = $request->get('product_id');
            $variant_id = $request->get('variant_id');
            $quantity = $request->get('quantity');

            $product = Product::find($product_id);
        
            $oldCart = Session::has('cart') ? Session::get('cart') : null;
            $cart = new Cart($oldCart);

            if($cart->stockAvailable($product_id,$variant_id,$quantity)){
                $cart->add($product,$variant_id,$quantity);
                $request->session()->put('cart',$cart);
            }else{
                $result = false;
            }

            return response()->json(['result' => $result, 'cart' => $cart]);
        }

    
    }

    public function addItem(Request $request)
    {
        if($request->ajax())
        {   

            $result = true;
            $message = '';
            $itemId = $request->get('id');
            $oldCart = Session::has('cart') ? Session::get('cart') : null;
            $cart = new Cart($oldCart);

            if($oldCart){

                $product_id = $cart->items[$itemId]['product_id'];
                $variant_id = $cart->items[$itemId]['variant_id'];
                $quantity = $request->get('quantity');

                $product = Product::find($product_id);

                if($cart->stockAvailable($product_id,$variant_id,$quantity)){
                    $cart->add($product,$variant_id,$quantity);
                    $request->session()->put('cart',$cart);
                }else{
                    $result = false;
                }

            }else{
                $result = false;
            }
            

            return response()->json(['result' => $result, 'cart' => $cart]);
        }

    
    }


    public function remove(Request $request)
    {
        
        if($request->ajax())
        { 
            $result = true;
            $id = $request->get('id');

            $oldCart = Session::has('cart') ? Session::get('cart') : null;
            $cart = new Cart($oldCart);
            $cart->remove($id);
        
            Session::put('cart',$cart);
            if($cart->totalQuantity<=0){
                Session::forget('cart');
                $result = false;
            }
            return response()->json(['result' => $result, 'cart' => $cart]);
        }
    }

    public function destroy()
    {
        Session::forget('cart');
    }

    public function viewCart()
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        return response()->json(['cart' => $cart]);
    }

    public function index(Request $request)
    {
        if($request->ajax())
        { 
            $result = false;
            $oldCart = Session::has('cart') ? Session::get('cart') : null;
            if($oldCart){
                $result = true;
            }
            $cart = new Cart($oldCart);
            return response()->json(['result' => $result, 'cart' => $cart]);
        }
    }
}