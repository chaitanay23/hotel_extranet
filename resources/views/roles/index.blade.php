@extends('layouts.app')


@section('content')

<div class="row mt-4 mb-4">
    <div class="col-sm-12">
        <h2 class="heder">Roles</h2>
    </div>
    <div class="col-sm-4 margin-tb">

        <div class="pull-right">

        @can('role_create')
            <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role</a>
        @endcan

        </div>

    </div>

</div>


@if ($message = Session::get('success'))

    <div class="alert alert-success">

        <p>{{ $message }}</p>

    </div>

@endif


<table class="table table-striped table-dark index-table">

  <tr>

     <th>No</th>

     <th>Name</th>

     <th width="280px">Action</th>

  </tr>

    @foreach ($roles as $key => $role)

    <tr>

        <td>{{ ++$i }}</td>

        <td>{{ $role->name }}</td>

        <td>

            <a class="eye" href="{{ route('roles.show',$role->id) }}"><i class="fas fa-eye"></i></a>

            @can('role_edit')

                <a class="eye" href="{{ route('roles.edit',$role->id) }}"><i class="fas fa-edit"></i></a>

            @endcan

            @can('role_delete')

                {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}

                    <button class="btn btn-danger1 eye" value="submit" type="submit"><i class="fas fa-trash-alt"></i></button>

                {!! Form::close() !!}

            @endcan

        </td>

    </tr>

    @endforeach

</table>


{!! $roles->render() !!}

<script>
    jQuery(document).ready(function($){
        $('.user').addClass('active-menu');
        $('.role').addClass('active-sub-menu');
    });
</script>
@endsection
