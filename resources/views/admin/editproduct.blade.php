@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">
    <h5>Editar producto</h5>
    <hr>

    <form method="POST" action="{{ route('product.edit',['id'=>$product->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row ">

            <div class="col-12">
                <label for="name" class="">Título</label>
                <div class="form-group">
                    <div>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $product->name}}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label for="price" class="">Precio</label>
                <div class="form-group">
                    <div>
                        <input id="price" type="number" placeholder="0.00" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') ?? $product->price  }}" required autocomplete="price" autofocus min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$">
                        @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

        

              <div class="col-12">
                <label for="category" class="">Categoría</label>
                <div class="form-group">
                    <div>
                        <select name="category_id" id="addproductcategory" class="form-control">


                            @foreach ($categoryList as $category)

                                @if($category[1]==0)
                                    <optgroup label="{{$category[2]}}">
                                @else
                                    @if($product->category_id == $category[0])
                                        <option value="{{$category[0]}}" selected>{{$category[2]}}</option>
                                    @else
                                        <option value="{{$category[0]}}">{{$category[2]}}</option>
                                    @endif
                                @endif

                            @endforeach

                        </select>

                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="">Imagen</label>
                    <input type="file" class="form-control" id="image" name="image">
                    @error('image')

                    <div style="color:red; font-weight:bold; font-size:0.7rem;">{{ $message }}</div>

                    @enderror
                </div>
            </div>
        
             <div class="col-8">
                <div class="form-group">
                    
                    @if($stock->count() > 0)
                    <hr>
                    <label for="image" class="">Información de stock asignado por modelo 

                    </label>
                    <br>
                    <label>
                         <a href="{{ route('admin.stock',['id'=>$product->id]) }}" class="btn btn-success mb-4" style="color:white; width:150px;">Editar stock</a>
                    </label>
                    <table class="table table-striped">

                        <thead>
                          <tr>
                            <th scope="col">Grupo de modelos</th>
                            <th scope="col">Modelo</th>
                            <th scope="stock">Cantidad</th>
                          </tr>
                        </thead>
                        <tbody id="variant-list">
                            <?php  $total_stock = 0; ?>
                            @foreach($stock as $item)
                                <tr>
                                    <td>{{$item->group_name}}</td>
                                    <td>{{$item->variant_name}}</td>
                                    <td>{{$item->quantity}}</td>
                                </tr>
                            <?php $total_stock = $total_stock + $item->quantity; ?>
                            @endforeach
                                
                            <tr>
                                <td></td>
                                <td><b>Total Stock</b></td>
                                <td>{{$total_stock}}</td>
                            </tr>

                        </tbody>
                    </table>
                    @else
                    <hr>
                    <label for="image" class="">El producto no tiene stock asignado a algun modelo o grupo de modelos 

                    </label>

                    <br>
                    <label>
                         <a href="{{ route('admin.stock',['id'=>$product->id]) }}" class="btn btn-success mb-4" style="color:white; width:150px;">Editar stock</a>
                    </label>

                    @endif
                </div>
            </div>


        </div>
        
        <button type="submit" class="btn btn-success w-100">Editar producto</button>


        <center>
            <br>
            <a href="{{ route('admin.product') }}" class="text-center" style="margin-top:15px;">Cancelar</a>
        </center>
    
    </form>

</div>
    
@endsection