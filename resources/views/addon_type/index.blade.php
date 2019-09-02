@extends('layouts.app')

@section('content')
<div class="row mt-4 mb-4">
  <div class="col-sm-12">
        <h2 class="heder">Addon Type</h2>
    </div>
    <div class="col-sm-12 margin-tb">
        <div class="pull-right">
          
            <a class="btn btn-success" href="{{ route('addon_type.create') }}"> Create New Addon</a>

        </div>
    </div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif
<table class="table table-striped table-dark index-table mt-2">
 <tr>
   <th>No</th>
   <th>Title</th>
   <th>Image</th>
   <th>Amount</th>
   <th width="280px">Action</th>
 </tr>
 @foreach ($addon_type as $key => $data)
  <tr>   
    <td>{{ ++$i }}</td>
    <td>{{ $data->title}}</td>
    <td>{!! Html::image($data->list_img, 'alt', array('width' => 120))!!}</td>
    <td>{{ $data->amount}}</td>
    <td>

      <a class="eye" href="{{ route('addon_type.edit',$data->id) }}"><i class="fas fa-edit"></i></a>


      {!! Form::open(['method' => 'DELETE','route' => ['addon_type.destroy', $data->id],'style'=>'display:inline']) !!}
          <button class="btn btn-danger1 eye" value="submit" type="submit"><i class="fas fa-trash-alt"></i></button>
      {!! Form::close() !!}        

    </td>
  </tr>
  @endforeach
</table>

<style type="text/css">
  #image{
    width:200px;
  }
</style>
{!! $addon_type->render() !!}
<script>
  jQuery(document).ready(function($){
    $('.add-on').addClass('active-menu');
    $('.add-type').addClass('active-sub-menu');
  });
</script>
@endsection
