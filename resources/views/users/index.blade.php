@extends('layouts.app')


@section('content')

<div class="row mt-4 mb-2">
  <div class="col-sm-12">
        <h2 class="heder">User</h2>
    </div>
    <div class="col-sm-6">
    @can('user_create')
            <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
          @endcan
     </div>
      <div class="col-sm-6"> 
    {!! Form::open(['method'=>'GET','url'=>'users','class'=>'col-lg-12 float-right','role'=>'search'])  !!}
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


<table class="table table-striped table-dark index-table">

 <tr>

   <th>No</th>

   <th>Name</th>

   <th>Username</th>

   <th>Roles</th>

   <th width="280px">Action</th>

 </tr>

 @foreach ($data as $key => $user)

  <tr>
    
    <td>{{ ++$i }}</td>

    <td>{{ $user->name }}</td>

    <td>{{ $user->email }}</td>

    <td>

      @if(!empty($user->getRoleNames()))

        @foreach($user->getRoleNames() as $v)

           <label class="badge badge-success">{{ $v }}</label>

        @endforeach

      @endif

    </td>

    <td>
        
       <a class="eye" href="{{ route('users.show',$user->id) }}"><i class="fas fa-eye"></i></a>
       @can('user_edit')
          <a class="eye" href="{{ route('users.edit',$user->id) }}"><i class="fas fa-edit"></i></a>
       @endcan

       @can('user_delete')
          {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
          
          <button class="btn btn-danger1 eye" value="submit" type="submit"><i class="fas fa-trash-alt"></i></button>
              

          {!! Form::close() !!}
        @endcan

    </td>

  </tr>

 @endforeach

</table>

{!! $data->appends(['search'=>$search])->render() !!}
<script>
  jQuery(document).ready(function($){
    $('.user').addClass('active-menu');
    $('.user-inter').addClass('active-sub-menu');
  });
</script>

@endsection
