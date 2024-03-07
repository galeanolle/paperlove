@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">
    @if(Session::has('success'))
    <div class="row">
      <div class="col-12">
        <div id="charge-message" class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      </div>
    </div>
    @endif
    <div class="row">
        <div class="col-4 totaluser">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-user"></i>
                    Usuarios
                </div>
                <div class="card-body">
                    <h5>{{ $totaluser }}</h5>
                </div>
            </div>
        </div>
        <div class="col-4 totalorder">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-dropbox"></i>
                    Órdenes 
                </div>
                <div class="card-body">
                    <h5>{{ $totalorder }} </h5>
                </div>
            </div>
        </div>
        <div class="col-4 totalgross">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-money"></i>
                    Dinero
                </div>
                <div class="card-body">
                    
                    <h5>$ {{ $totalgross }}</h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-sm-12 col-lg-8 latestorder mt-4">
            <div class="card">
                <div class="card-header">
                    Ùltimas órdenes
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($latest as $order)
                        <a href="{{ route('admin.showorder',['id'=>$order->order_id]) }}" class="list-group-item latest-order">
                            <div class="row">
                                <div class="col-12 d-flex">
                                    <div class="id" style="width:100px">ID: {{ $order->order_id }}</div>
                                    <div class="id" style="width:230px">Fecha: {{ $order->order_created_at }}</div>
                                    <div class="name">Total: ${{ $order->total }}</div>
                                     @if($order->status=='created')
                                        <div class="status text-success ml-auto" style="color:black;">Iniciada</div> 
                                     @endif
                                     @if($order->status=='success')
                                        <div class="status text-success ml-auto">Pagada</div> 
                                     @endif
                                     @if($order->status=='pending')
                                        <div class="status text-success ml-auto" style="color:yellow;">Pendiente</div> 
                                     @endif
                                     @if($order->status=='failure')
                                        <div class="status text-success ml-auto" style="color:red;">Cancelada</div> 
                                     @endif

                                </div>
                            </div>
                        </a>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection