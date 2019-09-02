@extends('layouts.app')


@section('content')

<div class="row mt-4 mb-2">
  <div class="col-sm-12">
        <h2 class="heder">Hotel Type</h2>
    </div>

    <div class="col-sm-12 margin-tb">
     <div class="pull-right">
          @can('hotel_type_create')
            <a class="btn btn-success" href="{{ route('hotel_type.create') }}"> Create New Hotel Type</a>
          @endcan
        </div>

    </div>

</div>


@if ($message = Session::get('success'))

<div class="alert alert-success">

  <p>{{ $message }}</p>

</div>

@endif


<table class="table table-striped table-dark index-table mt-4">

 <tr>

   <th>No</th>

   <th>Name</th>

   <th width="280px">Action</th>

 </tr>

 @foreach ($data as $key => $type)

  <tr>
    
    <td>{{ ++$i }}</td>

    <td>{{ $type->name }}</td>

    <td>

       
      @can('hotel_type_edit')
      <a class="eye" href="{{ route('hotel_type.edit',$type->id) }}"><i class="fas fa-edit"></i></a>
      @endcan 

      @can('hotel_type_delete')
   
      {!! Form::open(['method' => 'DELETE','route' => ['hotel_type.destroy', $type->id],'style'=>'display:inline']) !!}

          <button class="btn btn-danger1 eye" value="submit" type="submit"><i class="fas fa-trash-alt"></i></button>

      {!! Form::close() !!}
      @endcan

    </td>

  </tr>

 @endforeach

</table>
<script>
  jQuery(document).ready(function($){
    $('.property').addClass('active-menu');
    $('.hotel_type').addClass('active-sub-menu');
  });
</script>

{!! $data->render() !!}
@endsection
