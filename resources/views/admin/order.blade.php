@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h5>Órdenes</h5>
        </div>
        <div class="card-body">

            <form id="search-form" action="{{ route('admin.order') }}" method="GET" enctype="multipart/form-data">
              @csrf
                <!-- Search form -->
                <div class="input-group mb-3">
                  <input type="text" class="form-control" name="search" placeholder="Buscar por ID, total, nombre, email o domicilio..." aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{ $search }}">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="event.preventDefault();
                                                document.getElementById('search-form').submit();"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
            <ul class="list-group">
                <a href="#" class="list-group-item latest-order">
                    <div class="row">
                        <div class="col-12 d-flex">
                            <div class="id" style="width:100px">ID</div>
                            <div class="id" style="width:220px">Fecha</div>
                            <div class="name" style="width:200px">Nombre</div>
                            <div class="name" style="width:200px">Subtotal</div>
                            <div class="name" style="width:200px">Envio</div>
                            <div class="name" style="width:200px">Total</div>
                            <div class="status  ml-auto">Estado</div> 
                        </div>
                    </div>
                </a>
                @foreach ($orders as $order)
                <a href="{{ route('admin.showorder',['id'=>$order->order_id]) }}" class="list-group-item latest-order">
                    <div class="row">
                        <div class="col-12 d-flex">
                            <div class="id" style="width:100px">{{ $order->order_id }}</div>
                            <div class="id" style="width:220px">{{ $order->order_created_at }}</div>
                            <div class="name" style="width:200px">{{ $order->name }}</div>
                            <div class="name" style="width:200px">${{ $order->total }}</div>
                            <div class="name" style="width:200px">${{ $order->shippingcost }}</div>
                            <div class="name" style="width:200px">${{ $order->total + $order->shippingcost }}</div>
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
        <div class="card-footer">
            <h6>{{ $orders->links() }}</h6>
        </div>
    </div>
</div>
    
@endsection