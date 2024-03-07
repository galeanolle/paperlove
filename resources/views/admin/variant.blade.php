@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h5>Modelos</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.variant.edit',['id'=>1]) }}" class="btn btn-success mb-4" style="color:white; width:250px;">Agregar un modelo</a>
            <a href="{{ route('admin.variant.form') }}" class="btn btn-success mb-4" style="color:white; width:250px;">Agregar un grupo de modelos</a>
            
            <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col" width="40%">Nombre</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($groups as $group)
                  <tr>
                    <td scope="row">{{ $group->id }}</td>
                    <td>{{ $group->name }}</td>
                    
                    <td>
                       
                        <a href="{{ route('admin.variant.edit',['id'=>$group->id]) }}" class="btn btn-primary w-100 m-1" style="color:white;"><i class="fa fa-edit"></i> Editar</a>
                      
                    </td>

                    <td>
                        @if( $group->id != 1)
                            <a href="#" data-url="{{ route('admin.variant.remove',['id'=>$group->id]) }}" data-title="Eliminar grupo de modelos"  data-content="Seguro que desea eliminar el grupo de modelos? (El stock asignado a los productos que tengan los modelos de este grupo seran eliminados)" class="btn btn-danger w-100 m-1 confirm" style="color:white;"><i class="fa fa-trash"></i> Eliminar</a>
                        @endif
                    </td>

                  </tr>
                @endforeach
                </tbody>
              </table>
        </div>
    </div>
</div>
    
@endsection