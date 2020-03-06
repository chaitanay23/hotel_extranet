@extends('layouts.app')


@section('content')

<div class="row mt-4 mb-2">
  <div class="col-sm-12">
    <h2 class="heder">Hotel</h2>
  </div>
  <div class="col-sm-6 margin-tb">
    <div class="pull-right">
      @can('hotel_create')
      <a class="btn btn-success" href="{{ route('hotel.create') }}"> Create New Hotel</a>
      @endcan
    </div>

  </div>
  <div class="col-sm-6">
    {!! Form::open(['method'=>'GET','url'=>'hotel','class'=>'col-lg-12 float-right','role'=>'search']) !!}
    <div class="input-group custom-search-form">
      <input type="text" class="form-control" name="search" placeholder="Search..." autocomplete="off">
      <div class="input-group-append">
        <button class="btn btn-default-sm btn-color" type="submit">
          <i class="fa fa-search"></i>
        </button>
      </div>
    </div>
    {!! Form::close() !!}

  </div>

</div>


@if ($message = Session::get('success'))
<div class="alert alert-success mt-1">
  <p>{{ $message }}</p>
</div>
@endif

<table class="table table-striped table-dark index-table mt-2">
  <tr>
    <th>No</th>
    <th>Name</th>
    <th>City</th>
    <th>Region</th>
    <th>Area</th>
    <th>Image</th>
    <th width="280px">Action</th>
  </tr>
  @foreach ($hotel as $key => $data)
  <tr>
    <td>{{ ++$i }}</td>
    <td>{{ $data->title }}</td>
    <td>{{ $data->city_name}}</td>
    <td>{{ $data->region_name}}</td>
    <td>{{ $data->area_name}}</td>
    <td>{!! Html::image($data->picture, 'alt', array('width' => 120))!!}
    </td>
    <td>
      <a class="eye" href="{{ route('hotel.show',$data->id) }}"><i class="fas fa-eye"></i></a>
      @can('hotel_edit')
      <a class="eye" href="{{ route('hotel.edit',$data->id) }}"><i class="fas fa-edit"></i></a>
      @endcan
      @can('hotel_delete')
      {!! Form::open(['method' => 'DELETE','route' => ['hotel.destroy', $data->id],'style'=>'display:inline']) !!}

      <button class="btn btn-danger1 eye" value="submit" type="submit"><i class="fas fa-trash-alt"></i></button>

      {!! Form::close() !!}
      @endcan
    </td>
  </tr>
  @endforeach
</table>

<style type="text/css">
  #image {
    width: 200px;
  }
</style>
<script>
  jQuery(document).ready(function($) {
    $('.property').addClass('active-menu');
    $('.hotel').addClass('active-sub-menu');
  });
</script>
{!! $hotel->appends(['search'=>$search])->render() !!}
@endsection