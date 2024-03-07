@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">
    <h5>Agregar grupo de modelos</h5>
    <hr>

    <form id="form_save" method="POST" action="{{ route('admin.variant.create') }}" enctype="multipart/form-data">
        @csrf
        <div class="row ">

            <div class="col-12">
                <label for="name" class="">Nombre del grupo</label>
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

        <div class="row ">
            <div class="col-8">
                <span class="invalid-feedback error-variant" style="display:none;" role="alert">
                        <strong>Se debe ingresar al menos un modelo para el grupo</strong>
                            </span>
                            
                <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Modelo</th>
                    <th scope="col">
                      
                        <button type="button" class="btn btn-success w-60" id="addvariant">
                            Agregar otro modelo
                        </button>
                           
                    </th>
                  </tr>
                </thead>
                <tbody id="variant-list">
                        
                

                </tbody>
              </table>

                 

            </div>

        </div>


        <input type="hidden" name="variant_info" id="variant_info" />
       
        

        <button type="button" id="saveVariantGroup" class="btn btn-success w-100">Agregar grupo de modelos</button>
        
        <center>
            <br>
            <a href="{{ route('admin.variant') }}" class="text-center" style="margin-top:15px;">Cancelar</a>
        </center>

    </form>

</div>
    
@endsection


@section ('script')

<script>
    
var counterVariant = 0;

function addVariant(){
    counterVariant++;
    var htmlVariant = '<tr id="variant_'+counterVariant+'">';
    htmlVariant += '<td>';
    htmlVariant += '<input type="text" class="form-control input-variant" value="">';
    htmlVariant += '</td>';
    htmlVariant += '<td>';
    htmlVariant += '<a href="#" class="btn btn-danger w-60 m-1 removevariant" data-id="'+counterVariant+'" style="color:white;"><i class="fa fa-trash"></i> Eliminar</a>';
    htmlVariant += '</td>';
    htmlVariant += '</tr>';
    $('#variant-list').append(htmlVariant);
}

addVariant();

$('#addvariant').click(function(){
    addVariant();
    $('.removevariant').click(function(){
        $('#variant_'+$(this).attr('data-id')).remove();
    });
});

$('#saveVariantGroup').click(function(){
    $('.error-variant').hide();
    
    var inputs =  $('.input-variant');
 
         var arr = [];
        for(var i = 0; i<inputs.length; i++){
            if($(inputs[i]).val()!='')
                arr.push($(inputs[i]).val());
        }
        if(arr.length==0){
            $('.error-variant').show();
        }else{
            console.log(arr);
            $('#variant_info').val(JSON.stringify(arr));
            $('#form_save').submit();
        }
        
    
   
});

$('.removevariant').click(function(){
    $('#variant_'+$(this).attr('data-id')).remove();
});


</script>

@endsection