@extends('layouts.app')


@section('content')

<div class="row">
    <div class="col-sm-12 mt-4">
        <h2 class="heder">Roles</h2>
    </div>


    <div class="ccol-sm-4 margin-tb">

        <div class="pull-left ml-4">

            <a class="eye1" href="{{ route('roles.index') }}"> <i class="fas fa-long-arrow-alt-left"></i></a>

        </div>

    </div>

</div>


<div class="row">
    <div class="card-show col-md-4 pt-3 mt-4 pb-3 pl-4">
        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Name:</strong>

                <span class="show-data">{{ $role->name }}</span>

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Permissions:</strong>

                @if(!empty($rolePermissions))

                    @foreach($rolePermissions as $v)

                        <label class="label label-success show-data">{{ $v->label }},</label>

                    @endforeach

                @endif

            </div>
        </div>
    </div>

</div>
<script>
    jQuery(document).ready(function($){
        $('.user').addClass('active-menu');
        $('.role').addClass('active-sub-menu');
    });
</script>
@endsection 
