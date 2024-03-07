@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">

    <h5>Asignar unn modelo particular y agregar stock</h5>
    <hr>

    <form method="POST" action="{{ route('admin.addstockvariant',['id'=>$product->id])}}" enctype="multipart/form-data">
        @csrf
        <div class="row ">

            <div class="col-12">
                <label for="product" class="">{{ __('Product') }}</label>
                <label for="product" class="">{{ $product->name }}</label>
                <hr>
            </div>


            <div class="col-12">
                <label for="group" class="">Modelos</label>
                <div class="form-group">
                    <select name="variant_id" id="addproductstock" class="form-control">
                        <option selected="true" value="" disabled hidden>Selecciona un modelo...</option>
                        @foreach ($variants as $variant)
                            @if(old('variant_id')==$variant->id)
                                <option value="{{ $variant->id }}" selected>{{ $variant->id.' - '.$variant->name }}</option>
                            @else
                                <option value="{{ $variant->id }}">{{ $variant->id.' - '.$variant->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                @foreach($errors->all() as $error)
                        <strong style="color:red;">{{ $error }}</strong>
                        <br>
                @endforeach
            </div>


            <div class="col-12">
                <label for="quantity" class="">Cantidad</label>
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
        
        <button type="submit" class="btn btn-success w-100">Asignar el modelo y agregar stock</button>
        <center>
            <br>
            <a href="{{ route('admin.stock', ['id'=>$product->id]) }}" class="text-center" style="margin-top:15px;">Cancelar</a>
        </center>
    
    </form>

</div>
    
@endsection