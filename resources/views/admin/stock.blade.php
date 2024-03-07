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

    <div class="card">
        <div class="card-header">

            <h5>Stock de modelos asignados al producto</h5>

            <h8>{{ $product->name }} 
                <br>
            <a href="{{ route('product.editform',['id'=>$product->id]) }}" style="color:blue; width:250px;"> [ ir al producto ]</a>

            <a href="{{ route('admin.product') }}" style="color:blue; width:250px;"> [ ir al lista de productos ]</a>
        </h8>

        </div>
        <div class="card-body">
            <a href="{{ route('admin.addstockformvariant',['id'=>$product->id]) }}" class="btn btn-success mb-2" style="color:white; width:250px;"><i class="fa fa-check"></i> Asignar un modelo</a>
            <a href="{{ route('admin.addstockformgroup',['id'=>$product->id]) }}" class="btn btn-success mb-2" style="color:white; width:250px;"><i class="fa fa-check"></i> Asignar Grupo de modelos</a>
           

            <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Grupo</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">
                        <form method="POST" id="form_save" action="{{ route('admin.savestock',['id'=>$product->id])}}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="stockinfo" id="stock_info">
                            @if($stock->count()>0)
                                <button type="button" class="btn btn-success w-100" id="savestock">
                            Guardar Stock</button>
                            @else
                                <button type="button" class="btn btn-success w-100" id="savestock" style="display:none">
                            Guardar Stock</button>
                            @endif
                        </form>
                    </th>
                  </tr>
                </thead>
                <tbody id="stock-list">
                    
                    <?php $total_stock = 0; ?>
                    @if($stock->count()>0)

                    @foreach($stock as $itemStock)
                        <tr id="item_stock_{{$itemStock->stocks_id}}">

                        <th scope="row">
                            {{$itemStock->variant_group_name}}
                        </th>

                        <th>
                            {{$itemStock->variant_name}}
                        </th>
                        <td>
                            <input id="quantity" type="number" data-id="{{$itemStock->stocks_id}}" class="form-control quantity" value="{{$itemStock->stocks_quantity}}">
                            
                        </td>
                        <td>

                              <a href="#" data-url="{{ route('admin.removestock',['id'=>$product->id,'stock'=>$itemStock->stocks_id]) }}" data-title="Eliminar Stock del producto"  data-content="Seguro que desea eliminar el stock del producto?" class="btn btn-danger w-100 m-1 confirm" style="color:white;"><i class="fa fa-trash"></i> Eliminar</a>
                        </td>

                        </tr>
                        <?php 
                            $total_stock = $total_stock + $itemStock->stocks_quantity;
                        ?>
                    @endforeach

                        <tr>
                            <td></td>
                            <td><b>Total Stock</b></td>
                            <td>{{$total_stock}}</td>
                            <td></td>
                        </tr>

                    @else

                        <tr>
                            <td colspan="4">No hay modelos asignados para definir stock</td>
                        </tr>

                    @endif

                </tbody>
              </table>
        </div>
    </div>
</div>
    
@endsection