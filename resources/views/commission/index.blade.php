@extends('layouts.app')

@section('content')

<div class="row mt-4 mb-2">
<div class="col-sm-12">
        <h2 class="heder">MagicSpree Commissions</h2>
    </div>
  <div class="col-sm-6 margin-tb">
      <div class="pull-right">
          @can('commission_create')
          <a class="btn btn-success" href="{{ route('commission.create') }}"> Create New Commission</a>
          @endcan
      </div>
  </div>
    <div class="col-sm-6"> 
    {!! Form::open(['method'=>'GET','url'=>'commission','class'=>'col-lg-12 float-right','role'=>'search'])  !!}
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
   <th>Hotel Name</th>
   <th>Commission (%)</th>
   <th>Pass-Back-Commission (%)</th>
   <th>MagicSpree Fee</th>
   <th width="280px">Action</th>
 </tr>
@foreach ($commission as $key => $data)
<tr>
    <td>{{ ++$i }}</td>
    <td>{{ $data->hotel_name}}</td>
    <td>{{ $data->commission}}</td>
    <td>{{ $data->pbc}}</td>
    <td>{{ $data->magicspree_fee}}</td>
    <td>
        <a class="eye" href="{{ route('commission.show',$data->id) }}"><i class="fas fa-eye"></i></a>
        @can('commission_edit')
        <a class="eye" href="{{ route('commission.edit',$data->id) }}"><i class="fas fa-edit"></i></a>
        @endcan
        @can('commission_delete')
        {!! Form::open(['method' => 'DELETE','route' => ['commission.destroy', $data->id],'style'=>'display:inline']) !!}
           <button class="btn btn-danger1 eye" value="submit" type="submit"><i class="fas fa-trash-alt"></i></button>
        {!! Form::close() !!}        
        @endcan
    </td>
</tr>
@endforeach
</table>
{!! $commission->appends(['search'=>$search])->render() !!}
<script>
    jQuery(document).ready(function($){
        $('.commission').addClass('active-menu');
        $('.ms_com').addClass('active-sub-menu');
    });
</script>
@endsection
