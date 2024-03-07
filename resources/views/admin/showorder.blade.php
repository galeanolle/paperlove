@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
            @foreach ($ids as $id)
            <div class="col-12 col-lg-6 col-md-6 col-sm-12 pt-2">
                <h5>Detalle de la órden</h5>
                <a href="{{ route('admin.order') }}" style="color:blue; width:250px;"> [ ir a ordenes ]</a>
                <hr>
                <div class="row">
                    <div class="col-5">
                        <u>Orden</u><br>
                        ID<br>
                        Estado<br>
                        
                        <br>
                        
                        <u>Usuario</u><br>
                        ID<br>
                        Name <br>
                        E-mail <br>
                        Telefono <br>
                        <br>
                        <u>MercadoPago</u><br>
                        ID <br>
                        
        
                    </div>
                    <div class="col-7">
                        <br>
                        : {{ $id->id }} <br>
                     @if($id->status=='created')
                        : <span style="color:black;">Iniciada</span> 
                     @endif
                     @if($id->status=='success')
                        : <span class="text-success">Pagada</span> 
                     @endif
                     @if($id->status=='pending')
                        :<span class="status text-success ml-auto" style="color:yellow;">Pendiente</span> 
                     @endif
                     @if($id->status=='failure')
                        :<span class="status text-success ml-auto" style="color:red;">Cancelada</span> 
                     @endif<br>

                        <br><br>

                        : {{ $id->user_id }} <br>
                        : {{ $id->name }} <br>
                        : {{ $id->email }} <br>
                        : {{ $id->phonenumber }} <br>
                        
                        <br><br>
                        
                        : 1 {{ $id->payment_id }} <br>
                    </div>
                </div>
                
            </div>
            

            <div class="col-12 col-lg-6 col-md-6 col-sm-12 pt-2">
                <h5>Información de envío</h5>
                <br>
                <hr>
                <div class="row">
                    <div class="col-5">
                        Ciudad <br>
                        Domicilio <br>
                        Código Postal <br>
                        
                    </div>
                    <div class="col-7">
                        : {{ $id->city }} <br>
                        : {{ $id->address }} <br>
                        : {{ $id->zipcode }} <br>
                        
                        
                    </div>
                </div>
            </div>
           @endforeach
        </div>
        </div>
        <div class="card-body">
            @foreach($order as $order)
            <div class="col-sm-12 col-md-12 col-lg-12 d-flex order-history mx-auto">
                <div class="row">
                    @foreach ($order->cart->items as $item)
                        <div class="col-12 d-flex justify-content-between ">
                            <div class="order-image">
                                <img src="{{ asset('/storage/'.$item['item']['image']) }}" alt="">
                            </div>

                            <div class="order-detail mr-auto d-flex flex-column justify-content-center">
                                <div class="detail-1">
                                    <h5>{{ $item['item']['name'] }}</h5>
                                </div>
                                <div class="detail-2">
                                    <h6>Modelo: {{ $item['variant_group_name'] }} > {{ $item['variant_name'] }}</h6>
                                </div>
                                <div class="detail-3">
                                    <h6>Cantidad: {{ $item['quantity'] }}</h6>
                                </div>
                                <div class="detail-4">
                                    <h6>Precio: ${{ $item['price'] }}</h6>
                                </div>
                                <div class="detail-4">
                                    <h6>Total: ${{ $item['totalPrice'] }}</h6>
                                </div>
                            </div> 
                        </div>
                    @endforeach
                </div>                      
            </div>
            @endforeach
        </div>
    </div>
</div> 
    
@endsection