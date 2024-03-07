<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use App\Cart;
use App\Order;
use App\Stock;
use DB;
use Illuminate\Support\Facades\Redirect;

use MercadoPago;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class CheckoutController extends Controller
{
    public function notification(Request $request){

        // obtener el payment_id
        // obtener estado del payment_id , obtener el external (order id) y actualizar el order
        echo '';
        exit();
    
    }

    public function payment(Request $request){
/*
        $payment_id = $request->input('payment_id');
        $status = $request->input('status');
        $external_reference = $request->input('external_reference');

        $order = Order::find($external_reference);
        $order->payment_id = $payment_id;
        $order->status = $status;
        $order->save();
*/
        $status = 'success';
        return view('checkout.payment',compact('status'));

    }

    public function index()
    {
        // Si no hay carro va a la home
        if(!Session::has('cart')){
            return redirect()->route('home.index');
        }
        
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $total = $cart->totalPrice;
        $quantity = $cart->totalQuantity;

        $user = Auth::user();
        return view('checkout.index',compact('user','total','quantity'));
    }

    public function checkout(Request $request)
    {
        $this->validate(request(), [
            'phonenumber' => 'required|string',
            'city' => 'required|string',
            'address' => 'required',
            'zipcode' => 'required',
        ]);
        
        // Si no tiene carrito vuelve a la home 
        if(!Session::has('cart')){
            return redirect()->route('home.index');
        }

        // Quitar el stock de los productos comprados
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        foreach ($cart->items as $item) {
            Stock::where('product_id',$item['product_id'])
                    ->where('variant_id',$item['variant_id'])
                    ->decrement('quantity',$item['quantity']);
        }
        
        // Generar la orden 
        $order = new Order();
        $order->cart = serialize($cart); 
        $order->phonenumber = $request->input('phonenumber');
        $order->city = $request->input('city');
        $order->address = $request->input('address');
        $order->zipcode = $request->input('zipcode');
        $order->total = $cart->totalPrice;
        Auth::user()->orders()->save($order);


        // Si paga con mercado_pago

        // Pago con MercadoPago
        MercadoPago\SDK::setAccessToken(config('services.mercadopago.access_token'));

        // Default value is set to SERVER
        //MercadoPago\SDK::setRuntimeEnviroment(MercadoPago\SDK::LOCAL);

        $preference = new MercadoPago\Preference();

        $preference->back_urls = array(
            'success' => route('checkout.payment'),
            'pending' => route('checkout.payment'),
            'failure' => route('checkout.payment')
        );

        $preference->auto_return = 'approved';


        $preference->notification_url = route('checkout.notification');
        
        // Agregar items
        $itemsMP = array();
        foreach ($cart->items as $item) {
            $itemMP = new MercadoPago\Item();
            $itemMP->id = $item['product_id'];
            $itemMP->title = $item['item']->name;
            $itemMP->quantity = $item['quantity'];
            $itemMP->unit_price = $item['price'];
            array_push($itemsMP,$itemMP);
        }

        $preference->items = $itemsMP;
        $preference->external_reference = $order->id;
        $preference->save(); # Save the preference and send the HTTP Request to create

        // Confirma la url de MercadoPago
        $url = $preference->init_point;

        // Elimina el carro 
        Session::forget('cart');
        
        return Redirect::to($url);
        
        // si la opcion no es mercadopago

        //return redirect()->route('home.index')->with('success','Successfully purchased the products!');
    }
}