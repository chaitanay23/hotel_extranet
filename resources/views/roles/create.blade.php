
@extends('layouts.app')


@section('content')

<div class="row">
    <div class="col-sm-12 mt-4">
        <h2 class="heder">Roles</h2>
    </div>

    <div class="col-sm-4 margin-tb">
      <div class="pull-right ml-2">

            <a class="eye1" href="{{ route('roles.index') }}"> <i class="fas fa-long-arrow-alt-left"></i></a>

        </div>

    </div>

</div>


@if (count($errors) > 0)

    <div class="alert alert-danger">

        <strong>Whoops!</strong> There were some problems with your input.<br><br>

        <ul>

        @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

        </ul>

    </div>

@endif


{!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
<div class="form form-padding">

<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">

        <div class="form-group">

            <strong>Name:</strong>

            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}

        </div>

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">

        <div class="form-group">

            <h3>Permission:</h3>
        </div>
        <div class="row">
            <div class="col-sm-6">

            @foreach($permission as $value)

                <label>
                    {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}

                    <span class="permission-list"> {{ $value->label }}</span>
                </label>

            <br/>

            @endforeach

        </div>

    </div>
    </div>


    <div class="col-xs-12 col-sm-12 col-md-12 text-center">

        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

</div>
</div>

{!! Form::close() !!}
<script>
    jQuery(document).ready(function($){
        $('.user').addClass('active-menu');
        $('.role').addClass('active-sub-menu');
    });
</script>

@endsection
