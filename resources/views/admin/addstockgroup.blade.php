@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">

    <h5>Asignar modelos de un grupo al producto y agregar stock</h5>
    <hr>

    <form method="POST" action="{{ route('admin.addstockgroup',['id'=>$product->id])}}" enctype="multipart/form-data">
        @csrf
        <div class="row ">

            <div class="col-12">
                <label for="product" class="">{{ __('Product') }}</label>
                <label for="product" class="">{{ $product->name }}</label>
                <hr>
            </div>


            <div class="col-12">
                <label for="group" class="">Grupo de modelos</label>
                <div class="form-group">
                    <select name="group" id="addproductstock" class="form-control">
                        <option selected="true" value="" disabled hidden>Selecciona el grupo de modelos...</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->id.' - '.$group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-12">
                <label for="quantity" class="">Cantidad (igual para todos los modelos del grupo)</label>
                <div class="form-group">
                    <div>
                        <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity')}}" required autocomplete="quantity" autofocus>
                        @error('quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

        </div>
        
        <button type="submit" class="btn btn-success w-100">Asignar modelos de un grupo y agregar stock</button>
        <center>
            <br>
            <a href="{{ route('admin.stock', ['id'=>$product->id]) }}" class="text-center" style="margin-top:15px;">Cancelar</a>
        </center>
    
    </form>

</div>
    
@endsection