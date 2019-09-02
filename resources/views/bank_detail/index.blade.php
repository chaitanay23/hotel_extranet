@extends('layouts.app')

@section('content')

<div class="row mt-4 mb-2">
  <div class="col-sm-12">
        <h2 class="heder">Bank Details</h2>
    </div>
  <div class="col-sm-6 margin-tb">
      <div class="pull-right">
      	@can('bank_detail_create')
          <a class="btn btn-success" href="{{ route('bank_detail.create') }}"> Create New Bank Details</a>
      	@endcan
      </div>
  </div>
  <div class="col-sm-6"> 
    {!! Form::open(['method'=>'GET','url'=>'bank_detail','class'=>'col-lg-12 float-right','role'=>'search'])  !!}

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
   <th>Account Number</th>
   <th>Account Holder</th>
   <th>IFSC Code</th>
   <th width="280px">Action</th>
 </tr>
 @foreach ($bank as $key => $data)
 <tr>
	<td>{{ ++$i }}</td>
	<td>{{ $data->hotel_name}}</td>
	<td>{{ $data->account_no}}</td>
	<td>{{ $data->account_holder}}</td>
	<td>{{ $data->ifsc_code}}</td>
	<td>
		<a class="eye" href="{{ route('bank_detail.show',$data->id) }}"><i class="fas fa-eye"></i></a>
		@can('bank_detail_edit')
		<a class="eye" href="{{ route('bank_detail.edit',$data->id) }}"><i class="fas fa-edit"></i></a>
		@endcan
		@can('bank_detail_delete')
		{!! Form::open(['method' => 'DELETE','route' => ['bank_detail.destroy', $data->id],'style'=>'display:inline']) !!}
		    <button class="btn btn-danger1 eye" value="submit" type="submit"><i class="fas fa-trash-alt"></i></button>
		{!! Form::close() !!}        
		@endcan
	</td>
</tr>
@endforeach
</table>
{!! $bank->appends(['search'=>$search])->render() !!}
<script>
  jQuery(document).ready(function($){
    $('.commission').addClass('active-menu');
    $('.bank').addClass('active-sub-menu');
  });
</script>
@endsection
