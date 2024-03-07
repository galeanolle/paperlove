@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">
    <h5>Agregar categoría</h5>
    <hr>

    <form method="POST" action="{{ route('admin.category.create') }}" enctype="multipart/form-data">
        @csrf
        <div class="row ">

    
            <div class="col-12">
                <label for="category" class="">Categoría padre</label>
                <div class="form-group">
                    <div>
                        <select name="id_parent" id="addproductcategory" class="form-control">

                            <option value="0">Es categoría padre</option>

                            @foreach ($categories as $category)
                                    
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                
                            @endforeach

                        </select>
                        @error('parent_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label for="name" class="">Nombre</label>
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


        </div>
        
        <button type="submit" class="btn btn-success w-100">Agregar categoría</button>
        
        <center>
            <br>
            <a href="{{ route('admin.category') }}" class="text-center" style="margin-top:15px;">Cancelar</a>
        </center>

    </form>

</div>
    
@endsection