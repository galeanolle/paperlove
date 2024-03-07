@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">
    <h5>Agregar producto</h5>
    <hr>

    <form method="POST" action="{{ route('product.create') }}" enctype="multipart/form-data">
        @csrf
        <div class="row ">

            <div class="col-12">
                <label for="name" class="">Título</label>
                <div class="form-group">
                    <div>
                        <input id="name" type="text" placeholder="Ingrese un nombre..." class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
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
                        <input id="price" type="number" placeholder="0.00" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price')  }}" required autocomplete="price" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$">
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
                                    <option value="{{$category[0]}}">{{$category[2]}}</option>
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
            


        </div>
        
        <button type="submit" class="btn btn-success w-100">Agregar producto</button>
        
        <center>
            <br>
            <a href="{{ route('admin.product') }}" class="text-center" style="margin-top:15px;">Cancelar</a>
        </center>

    </form>

</div>
    
@endsection