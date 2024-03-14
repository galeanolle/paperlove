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

use Illuminate\Support\Facades\Http;

use MercadoPago;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class CheckoutController extends Controller
{
    public function notification(Request $request){

        /*
        if($request->('topic')=='payment'){
            if($request->('id')){
                $payment_id = $request->('id');
                $url = 'https://api.mercadopago.com/v1/payments/'.$payment_id;
                $data = Http::get($url);
            }
        }


        topic=payment&id=123456789

        */

        // obtener el payment_id
        // obtener estado del payment_id , obtener el external (order id) y actualizar el order
        echo '';
        exit();
    
    }

    public function payment(Request $request){


        $status = $request->input('status');
        $payment_id = $request->input('payment_id');
    
        $external_reference = $request->input('external_reference');

        $order = Order::find($external_reference);
        $order->payment_id = $payment_id;
        $order->status = $status;
        $order->save();
        
        

        return view('checkout.payment',compact('status','external_reference'));

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
        $order->payment_type = $request->get('paymenttype');

        if($request->get('tipo_entrega')==2){
            $order->shippingcost = $request->get('envio');
        }

        Auth::user()->orders()->save($order);


        // Si paga con mercado_pago
        $url = 'https://lovepaper.com.ar';
        if($request->get('paymenttype')==1){
            // Pago con MercadoPago
            MercadoPago\SDK::setAccessToken('APP_USR-5313401638247144-021215-08fdf3249b42e0a553e4b72d951bdc8f-521851047');

            // Default value is set to SERVER
            //MercadoPago\SDK::setRuntimeEnviroment(MercadoPago\SDK::LOCAL);

            $preference = new MercadoPago\Preference();

            $preference->back_urls = array(
                'success' => 'https://lovepaper.com.ar/checkout/payment',
                'pending' => 'https://lovepaper.com.ar/checkout/payment',
                'failure' => 'https://lovepaper.com.ar/checkout/payment'
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

            if($request->get('tipo_entrega')==2){
                $itemMP = new MercadoPago\Item();
                $itemMP->id = 0;
                $itemMP->title = 'Costo de envio';
                $itemMP->quantity = 1;
                $itemMP->unit_price = $request->get('envio');
                array_push($itemsMP,$itemMP);
            }

            $preference->items = $itemsMP;
            $preference->external_reference = $order->id;
            $preference->save(); # Save the preference and send the HTTP Request to create

            // Confirma la url de MercadoPago
            $url = $preference->init_point;
        }

        // Elimina el carro 
        Session::forget('cart');

        if($request->get('paymenttype')==1)
            return Redirect::to($url);
        else{
            $status = 'pending-cash';
            $external_reference = $order->id;
            return view('checkout.payment',compact('status','external_reference'));
        }
    }

    function envio(Request $request){

        $result = false;
        if($request->ajax())
        {   
            $cuit = '20-39760789-6';
            $codigoPostalOrigen ='1722';
            $codigoPostalDestino = $request->get('zipcode');
            $cantidadPaquetes = $request->get('quantity');
            $valorDeclarado = $request->get('total_price');
            $peso = '0.150';
            $volumen = '0.0003';
            $operativa = '412889'; // puerta a puerta

            $url='https://webservice.oca.com.ar/ePak_tracking/Oep_TrackEPak.asmx/Tarifar_Envio_Corporativo';
            $url.='?PesoTotal='.$peso;
            $url.='&VolumenTotal='.$volumen;
            $url.='&CodigoPostalOrigen='.$codigoPostalOrigen;
            $url.='&CodigoPostalDestino='.$codigoPostalDestino;
            $url.='&CantidadPaquetes='.$cantidadPaquetes;
            $url.='&ValorDeclarado='.$valorDeclarado;
            $url.='&CUIT='.$cuit;
            $url.='&Operativa='.$operativa;

            $response = Http::get($url);

            $match;
            preg_match('/<Precio>(.*?)<\/Precio>/s', $response, $match);
            $price = 0;
            if(count($match)>0){
                $price = (float)$match[1];
                $result = true;
            }else{
                $result = false;
            }
            
            return response()->json(['result' => $result, 'price' => $price ]);
            
            //return response()->json(['result' => true, 'price' => 110 ]);
        }

    }
}
