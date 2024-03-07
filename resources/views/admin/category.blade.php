@extends('layouts.admin')

@section ('content')

<div class="col-12 col-md-12 col-sm-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h5>Categorías</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.category.form') }}" class="btn btn-success mb-4" style="color:white; width:250px;">Agregar categoría</a>
            
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
                @foreach ($categoryList as $category)
                  <tr>
                    <td scope="row">{{ $category[0] }}</td>
                    @if($category[1]==0)
                        <td>{{ $category[2] }}</td>
                    @else
                        <td style="padding-left:50px;">   <i class="fa fa-caret-right"></i>  {{ $category[2] }}</td>
                    @endif

                    <!--<td scope="row">{{ $category[3] }}</td>-->
                    <td>
                        <a href="{{ route('admin.category.edit',['id'=>$category[0]]) }}" class="btn btn-primary w-100 m-1" style="color:white;"><i class="fa fa-edit"></i> Editar</a>
                    </td>
                    <td>
                        <a href="#" data-url="{{ route('admin.category.remove',['id'=>$category[0]]) }}" data-title="Eliminar Categoria"  data-content="Seguro que desea eliminar la categoria? (Si hay productos asignados a esta categoria quedaran sin categoria asignada)" class="btn btn-danger w-100 m-1 confirm" style="color:white;"><i class="fa fa-trash"></i> Eliminar</a>
                    </td>

                  </tr>
                @endforeach
                </tbody>
              </table>
        </div>
    </div>
</div>
    
@endsection