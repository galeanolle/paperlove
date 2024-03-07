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
            <h5>Productos</h5>
        </div>
        <div class="card-body">
            <div class="row">
              <div class="col-3">
                <a href="{{ route('admin.addform') }}" class="btn btn-success mb-4" style="color:white; width:150px;">Agregar producto</a>
              </div>
               <div class="col-6">
                  <form id="search-form" action="{{ route('admin.product') }}" method="GET" enctype="multipart/form-data">
                  @csrf
                    <!-- Search form -->
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" name="search" placeholder="Buscar por título" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{ $search }}">
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="event.preventDefault();
                                                    document.getElementById('search-form').submit();"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </form>
               </div>
            </div>
    
   
            <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Imagen</th>
                    <th scope="col" width="20%">Titulo</th>
                    <th scope="col" width="15%">Precio</th>
                    <th scope="col" width="25%">Categoría</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                  <tr>
                    <th scope="row">{{ $product->id }}</th>
                    <td><img style="height:60px;border:1 solid black;" src="{{ asset('/storage/'.$product->image) }}" alt=""></td>
                    <td>{{ $product->name }}</td>
                    <td>$ {{ $product->price }}</td>
                    <td>{{ $product->parent }} <i class="fa fa-caret-right"></i> {{ $product->category }}</td>
                    <td>
                        <a href="{{ route('product.editform',['id'=>$product->id]) }}" class="btn btn-primary w-100 m-1" style="color:white;"><i class="fa fa-edit"></i> Editar</a>
                    </td>
                    <td>
                        <a href="#" data-url="{{ route('product.remove',['id'=>$product->id]) }}" data-title="Eliminar Producto"  data-content="Seguro que desea eliminar el producto?" class="btn btn-danger w-100 m-1 confirm" style="color:white;"><i class="fa fa-trash"></i> Eliminar</a>
                    </td>
                    <td>
                        <a href="{{ route('admin.stock',['id'=>$product->id]) }}" class="btn btn-success w-80 m-1" style="color:white;"><i class="fa fa-check"></i> Stock</a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
        </div>
        <div class="card-footer">
            <h5>{{ $products->links() }}</h5>
        </div>
    </div>
</div>
    
@endsection