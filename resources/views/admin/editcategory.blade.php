@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">
    <h5>Editar categoría</h5>
    <hr>

    <form method="POST" action="{{ route('admin.category.edit',['id'=>$category->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row ">

    
            <div class="col-12">
                <label for="category" class="">Categoría padre</label>
                <div class="form-group">
                    <div>
                        @if($category->id_parent==0)
                            Es categoría padre
                            <input name="id_parent" type="hidden" value="0" />
                        @else

                        <select name="id_parent" id="addproductcategory" class="form-control">

                            @if($category->id_parent==0)
                                <option value="0" selected>Es categoría padre</option>
                            @else
                                <option value="0">Es categoría padre</option>
                            @endif

                            @foreach ($categories as $itemCategory)
                                @if($category->id_parent==$itemCategory->id)
                                    <option value="{{$itemCategory->id}}" selected>{{$itemCategory->name}}</option>
                                @else
                                    <option value="{{$itemCategory->id}}">{{$itemCategory->name}}</option>
                                @endif
                            @endforeach

                        </select>
                        @endif
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
                        <input id="name" type="text" placeholder="Ingrese un nombre..." class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $category->name }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>


        </div>
        
        <button type="submit" class="btn btn-success w-100">Editar categoría</button>
        
        <center>
            <br>
            <a href="{{ route('admin.category') }}" class="text-center" style="margin-top:15px;">Cancelar</a>
        </center>

    </form>

</div>
    
@endsection