@extends('layouts.app')
@section('content')
<div class="row mt-4 mb-2">
  <div class="col-sm-12">
        <h2 class="heder">Room</h2>
    </div>
  <div class="col-sm-6 margin-tb">
      <div class="pull-right">
        @can('room_create')
          <a class="btn btn-success" href="{{ route('room.create') }}"> Create New Room</a>
        @endcan
      </div>
  </div>
  <div class="col-sm-6"> 
    {!! Form::open(['method'=>'GET','url'=>'room','class'=>'col-lg-12 float-right','role'=>'search'])  !!}
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
   <th>Room Name</th>
   @can('room_create')
   <th>Add Type</th>
   @endcan
   <th>Image</th>
   @if(Auth::user()->user_role_define == '1')
   <th>Status</th>
   @endif
   <th width="280px">Action</th>
 </tr>
 @foreach ($room as $key => $data)
  <tr>   
    <td>{{ ++$i }}</td>
    <td>{{ $data->hotel_name }}</td>
    <td>{{ $data->room_name }}</td>
    @can('room_create')
    <td><a class="eye" href="{{ route('room.create_type',['id' => $data->id])}}"><i class="fas fa-plus"></i></a></td>
    @endcan
    <td>{!! Html::image($data->room_picture, 'alt', array('width' => 120))!!}
	  </td>
  @if(Auth::user()->user_role_define == '1')
    <td>
      <input type="checkbox" data-offstyle="danger" id="status_room" data-onstyle="success" data-toggle="toggle" name="status" data-room="{{$data->id}}" value="{{$data->status}}">
    </td>
  @endif
    <td>
      <a class="eye" href="{{ route('room.show',$data->id) }}"><i class="fas fa-eye"></i></a>
      @can('room_edit')
      <a class="eye" href="{{ route('room.edit',$data->id) }}"><i class="fas fa-edit"></i></a>
      @endcan
      @can('room_delete')
      {!! Form::open(['method' => 'DELETE','route' => ['room.destroy', $data->id],'style'=>'display:inline']) !!}
          <button class="btn btn-danger1 eye" value="submit" type="submit"><i class="fas fa-trash-alt"></i></button>
      {!! Form::close() !!}
      @endcan     
    </td>
  </tr>
 @endforeach
</table>

<style type="text/css">
	#image{
		width:200px;
	}
</style>

<script>
	jQuery(document).ready(function($){
    $('.property').addClass('active-menu');
    $('.room').addClass('active-sub-menu');
		var _token = $('input[name="_token"]').val();
        $('input:checkbox').ready(function(){
          $('input:checkbox').each(function(){
            var value = $(this).val();
            if(value=='0')
            {
              $(this).bootstrapToggle('off');
            }
            else
            {
              $(this).bootstrapToggle('on');
            }
          });
        });
        $('.toggle-group').click(function(){
          $('input:checkbox').change(function(){
            var room = $(this).data("room");
            var value = $(this).val();
            if(value=='0')
            {
              $.ajax({
                url:"{{route('room_status_on.change')}}",
                method:"POST",
                data:{_token:_token,room_id:room},
                success:function(data)
                {
                  console.log(data);
                  $(this).val('1');
                }
              });
            }
            else{
              $.ajax({
                url:"{{route('room_status_off.change')}}",
                method:"POST",
                data:{_token:_token,room_id:room},
                success:function(data)
                {
                  console.log(data);
                  $(this).val('0');
                }
              });
            }
          });
        });
	});
</script>
{!! $room->appends(['search'=>$search])->render() !!}

@endsection
