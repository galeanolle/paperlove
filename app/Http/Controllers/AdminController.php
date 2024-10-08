<?php

namespace App\Http\Controllers;
use App\Order;
use App\User;
use App\Profile;
use App\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function index()
    {
        $totalgross = 0;

        $users = User:: get();
        $totaluser = count($users);

        $orders = Order::get();
        $totalorder = count($orders);
     
        
        
        if(Reminder::find(1)==null)
        {
            $reminder=new Reminder();
            $reminder->id = 1;
            $reminder->reminder="Type something";
            $reminder->save();
            $reminder = Reminder::find(1);
        }
        else
        {
            $reminder = Reminder::find(1);
        }
        
        $gross = Order::get();
        $gross->transform(function($order,$key){
            $order->cart = unserialize($order->cart);
            return $order;
        });

        foreach ($gross as $x){
           $totalgross+= $x->cart->totalPrice;
        }


        $latest=Order::leftjoin('users','orders.user_id','=','users.id')
        ->select(
            'orders.id as order_id',
            'orders.created_at as order_created_at',
            'users.name',
            'orders.total',
            'orders.status'
        )
        ->orderBy('orders.created_at','DESC')
        ->take(5)->get();

        
        return view('admin.index',compact('latest','totaluser','totalorder','totalgross','reminder'));
    }

    public function orderComplete($id){
        $order = Order::findOrFail($id);
        $order->status = 'success';
        $order->save();
        return redirect()->route('admin.showorder',['id'=>$id])->with('success','La orden a sido completada!');
    }

    public function order(Request $request)
    {
        $search = $request->get('search');
        $orders=Order::leftjoin('users','orders.user_id','=','users.id')
        ->select(
            'orders.id as order_id',
            'orders.tracking_id',
            'orders.created_at as order_created_at',
            'users.name',
            'orders.total',
            'orders.shippingcost',
            'orders.payment_type',
            'orders.status'
        )
        ->orderBy('orders.created_at','DESC')
        ->where('orders.id','LIKE','%'.$search.'%')
        ->OrWhere('orders.total','LIKE','%'.$search.'%')
        ->OrWhere('orders.address','LIKE','%'.$search.'%')
        ->OrWhere('orders.tracking_id','LIKE','%'.$search.'%')
        ->OrWhere('users.name','LIKE','%'.$search.'%')
        ->OrWhere('users.email','LIKE','%'.$search.'%')
        ->paginate(15)->appends(request()->query());
        
        return view('admin.order',compact('orders','search'));
    }

    public function show_order($id)
    {
        $ids =DB::table('orders')->leftjoin('users','orders.user_id','=','users.id')
        ->select(
            'orders.*',
            'users.name',
            'users.email'
        )
        ->where('orders.id',$id)->get();

        $order =DB::table('orders')->where('id',$id)->get();
        $order->transform(function($order,$key){
            $order->cart = unserialize($order->cart);
            return $order;
        });

        $success = '';
        return view('admin.showorder',compact('order','ids','success'));
    }

    public function edit_order_tracking_id($id, $tracking_id){

        $order =Order::find($id);
        $order->tracking_id = $tracking_id;
        $order->save();
        
        $ids =DB::table('orders')->leftjoin('users','orders.user_id','=','users.id')
        ->select(
            'orders.*',
            'users.name',
            'users.email'
        )
        ->where('orders.id',$id)->get();

        $order =DB::table('orders')->where('id',$id)->get();
        $order->transform(function($order,$key){
            $order->cart = unserialize($order->cart);
            return $order;
        });
        
        return view('admin.showorder',compact('order','ids'))->with('success','Se ha actualizado el Tracking ID del envío');
    }

    public function user()
    {
        $users=DB::table('users')->leftjoin('profiles','users.id','=','profiles.user_id')->get();
        return view('admin.user',compact('users'));
    }

    public function updatereminder()
    {
        $reminder= Reminder::find(1);
        $reminder->reminder = request('reminder');
        $reminder->save();

        return redirect()->route('admin.index')->with('success','Successfully updated the reminder!');
    }


}