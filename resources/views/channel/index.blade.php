
@extends('layouts.app')
@section('content')

<div class="row mt-4 mb-2">
    <div class="col-sm-12">
        <h2 class="heder">Channel Manager</h2>
    </div>
    <div class="col-sm-6 margin-tb">
        <div class="pull-right">
            <div class="pull-right">
                @can('channel_manager_create')
                <a class="btn btn-success" href="{{ route('channel.create') }}" > Create New Channel Details</a>
                @endcan
            </div>
        </div>
    </div>
    <div class="col-sm-6"> 
      {!! Form::open(['method'=>'GET','url'=>'channel','class'=>'col-lg-12 float-right','role'=>'search'])  !!}
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
        <th>Channel Partner</th>
        <th>Property ID</th>
        <th >Key</th>
        @if(Auth::user()->user_role_define == '1')
        <th >Status</th>
        @endif
        <th width="300em">Action</th>
    </tr>
    
    @foreach($channel as $key =>$data) 
    
    <tr>
        <td>{{++ $i}}</td>
        <td>{{$data->hotel_name}}</td>
        <td>{{$data->partner_name}}</td>
        <td>{{$data->hotel_id}}</td>
        <td id="channelID">{{$data->hotelkey}}</td>
         <!-- <td><input type="checkbox" id="switch" checked data-toggle="toggle" data-size="normal">  -->
        @if(Auth::user()->user_role_define == '1')
        <td class="toggle_button">
            <input type="checkbox" value="{{$data->status}}" data-toggle="toggle" data-offstyle="danger" data-onstyle="success" data-addon="" data-channel_id="{{$data->id}}">          
        </td>
        @endif
        <td>
            <a class="eye" href="{{route('channel.show',$data->id)}}"><i class="fas fa-eye"></i></a>
            @can('channel_manager_edit')
            <a  class="eye" href="{{route('channel.edit',$data->id)}}"><i class="fas fa-edit"></i></a>
            @endcan
            @can('channel_manager_delete')
            {!! Form::open(['method' => 'DELETE','route' => ['channel.destroy', $data->id],'style'=>'display:inline']) !!}
            <button class="btn btn-danger1 eye" value="submit" type="submit"><i class="fas fa-trash-alt"></i></button>
            {!! Form::close() !!}   
            @endcan
        </td>
    </tr>

    @endforeach 
</table>
{!! $channel->appends(['search'=>$search])->render() !!}
<script>
   jQuery(document).ready(function($){
        var _token = $('input[name="_token"]').val();
        $('.channel').addClass('active-menu');
        $('input:checkbox').ready(function(){
            $('input:checkbox').each(function(){
                var value = $(this).val();
                if(value=='1')
                {
                    $(this).bootstrapToggle('on');
                }
            });
        });
        $(".toggle_button").click(function(){
            $(".toggle_button").change(function(){
                var check = $(this).children().children().val();
                var id = $(this).children().children().data('channel_id');
                if(check=='1'){
                    $.ajax({
                        url:"{{route('channel_status_off.update')}}",
                        method:'POST',
                        data:{_token:_token,id:id},
                        success:function(data){
                            
                        }
                    });

                }
                else{
                    $.ajax({
                        url:"{{route('channel_status_on.update')}}",
                        method:'POST',
                        data:{_token:_token,id:id},
                        success:function(data){
                            
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
