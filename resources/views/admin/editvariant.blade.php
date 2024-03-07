@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">
    <h5>Editar el grupo de modelos</h5>
    <hr>

    <form id="form_save" method="POST" action="{{ route('admin.variant.edit',['id'=>$group->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row ">

            <div class="col-12">
                <label for="name" class="">Nombre del grupo</label>
                <div class="form-group">
                    <div>
                        @if( $group->id != 1)
                            <input id="name" type="text" placeholder="Ingrese un nombre..." class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $group->name }}" required autocomplete="name" autofocus>
                        @else
                            {{ $group->name }}
                            <input type="hidden" name="name" value="{{$group->name}}">
                        @endif
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
                    <?php $counterVariant = 0; ?>
                    @foreach($variants as $variant)
                        <tr id="variant_{{ $counterVariant }}">
                            <td>
                                <input type="text" class="form-control input-variant" data-id="{{$variant->id}}" value="{{$variant->name}}">
                            </td>
                            <td>
                                <!--
                                <a href="#" class="btn btn-danger w-60 m-1 removevariant" data-id="{{ $counterVariant }}" data-id-variant="{{$variant->id}}" style="color:white;"><i class="fa fa-trash"></i> Eliminar</a>

                                -->

                                 <a href="#" data-title="Eliminar modelo" data-id="{{ $counterVariant }}" data-id-variant="{{$variant->id}}"  data-content="Seguro que desea eliminar el modelo? (El stock asignado a los productos que tengan este modelo seran eliminados)" class="btn btn-danger w-60 m-1 confirm-edit-variant" style="color:white;"><i class="fa fa-trash"></i> Eliminar</a>

                            </td>
                        </tr>
                        <?php $counterVariant++; ?>
                    @endforeach
                </tbody>
              </table>

                 

            </div>

        </div>


        <input type="hidden" name="variant_info" id="variant_info" />
         <input type="hidden" name="variant_info_deleted" id="variant_info_deleted" />

        <button type="button" id="saveVariantGroup" class="btn btn-success w-100">Editar grupo de modelos</button>
        
        <center>
            <br>
            <a href="{{ route('admin.variant') }}" class="text-center" style="margin-top:15px;">Cancelar</a>
        </center>

    </form>

</div>
    
@endsection


@section ('script')

<script>
    
var counterVariant = {{$counterVariant}};
var arrDelete = [];

function addVariant(){
    counterVariant++;
    var htmlVariant = '<tr id="variant_'+counterVariant+'">';
    htmlVariant += '<td>';
    htmlVariant += '<input type="text" class="form-control input-variant" data-id="0" value="">';
    htmlVariant += '</td>';
    htmlVariant += '<td>';
    htmlVariant += '<a href="#" data-title="Eliminar modelo" data-id="'+counterVariant+'" data-id-variant="0"  data-content="Seguro que desea eliminar el modelo? (El stock asignado a los productos que tengan este modelo seran eliminados)" class="btn btn-danger w-60 m-1 confirm-edit-variant" style="color:white;"><i class="fa fa-trash"></i> Eliminar</a>';
    htmlVariant += '</td>';
    htmlVariant += '</tr>';
    $('#variant-list').append(htmlVariant);
}

$('#addvariant').click(function(){
    addVariant();
    $('.confirm-edit-variant').click(function(){
        idVariant = $(this).attr('data-id-variant');
        idData = $(this).attr('data-id');
        $.confirm({
            title: $(this).attr('data-title'),
            content: $(this).attr('data-content'),
            buttons: {
                confirm: function () {
                    arrDelete.push(idVariant);
                    $('#variant_'+idData).remove();
                },
                cancel: function () {
                }
            }
        });

    });
});


$('#saveVariantGroup').click(function(){
    $('.error-variant').hide();
    
        var inputs =  $('.input-variant');
 
        var arr = [];
        for(var i = 0; i<inputs.length; i++){
            if($(inputs[i]).val()!='')
                arr.push([$(inputs[i]).attr('data-id'),$(inputs[i]).val()]);
        }
        if(arr.length==0){
            $('.error-variant').show();
        }else{
            console.log(arr);
            
            $('#variant_info_deleted').val(JSON.stringify(arrDelete));
            $('#variant_info').val(JSON.stringify(arr));
            $('#form_save').submit();
        }
        
    
   
});

$('.confirm-edit-variant').click(function(){
    idVariant = $(this).attr('data-id-variant');
    idData = $(this).attr('data-id');
    $.confirm({
        title: $(this).attr('data-title'),
        content: $(this).attr('data-content'),
        buttons: {
            confirm: function () {
                arrDelete.push(idVariant);
                $('#variant_'+idData).remove();
            },
            cancel: function () {
            }
        }
    });

});

</script>

@endsection