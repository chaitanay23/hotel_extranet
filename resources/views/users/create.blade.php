@extends('layouts.app')

@section('content')

<div class="row mt-4 mb-2">
      <div class="col-sm-12">
        <h2 class="heder">User</h2>
    </div>
    <div class="col-sm-4 margin-tb">
        <div class="pull-right ml-2">
            <a class="eye1" href="{{ url()->previous() }}"><i class="fas fa-long-arrow-alt-left"></i></a>
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

{!! Form::open(array('route' => 'users.store','method'=>'POST','enctype' => 'multipart/form-data')) !!}
<div class="form form-padding">
<div class="row mt-4">
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>User Type:</strong>
            {!! Form::select('user_role_define',['1' => 'MagicSpree','2' => 'Hotel','3'=>'Revenue Manager','4'=>'Channel Manager'],null,['placeholder' => 'Select user type...','class' => 'form-control','id' => 'userType','required']) !!}
        </div>
    </div>
    
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>User Level:</strong>
            {!! Form::select('user_level',['1' => 'Super User','2' => 'Local User'],null,['placeholder' => 'Select user level...','class' => 'form-control','id' => 'userLevel','required']) !!}
        </div>
    </div>
            
    <div class="col-xs-12 col-sm-12 col-md-12" >
        <div class="form-group">
            <strong id="type_display">Name:</strong>
            <strong style="display: none" id="super_display">Group Name:</strong>
            <strong style="display: none" id="hotel_display">Legal Name:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Username:</strong>
            {!! Form::text('email', null, array('placeholder' => 'Username','class' => 'form-control','required')) !!}
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6">

        <div class="form-group">
            <strong>Password:</strong>
            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control','required')) !!}
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>Confirm Password:</strong>
            {!! Form::password('confirm-password', array('placeholder' => 'Confirm password','class' => 'form-control','required')) !!}
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>Primary Email:</strong>
            {!! Form::text('primary_email', null, array('placeholder' => 'Primary Email','class' => 'form-control','required')) !!}
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>Mobile Number:</strong>
            {!! Form::number('mobile', null, array('placeholder' => 'Mobile number','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12" id="super_user_field">
        <div class="form-group">
          <strong>Super User (optional):</strong>
          <div id="super_search">
            <select class="form-control" id="single_select2" name = 'super_user_id'>
              <option value="0">Select super user</option> 
            </select>
          </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Permissions:</strong>
            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple','id' => 'multi_select2','required')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group"> 
          <strong>Profile Picture:</strong>
          {!! Form::file('profile_pic', array('placeholder' => 'Profile pic','class' => 'image form-control'))!!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 mou_hide">
        <div class="form-group"> 
          <strong>MOU Certificate:</strong>
          {!! Form::file('mou', array('placeholder' => 'MOU Certificate','class' => 'image form-control'))!!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
        @can('hotel_create')
        <div class="text-right proceed_hide" style="display: none">
            <button type="submit" class="btn btn-success" name="submit" value="proceed">Proceed to next</button>
        </div>
        @endcan
    </div>
</div>

</div>

{!! Form::close() !!}

<script>
    jQuery(document).ready(function($) {
        $('#single_select2').select2({
            allowClear:true,
        });
        $('#multi_select2').select2({
            placeholder:'Choose roles',
            closeOnSelect: false,
            allowClear:true,
        });
        $('.user').addClass('active-menu');
        $('.user-inter').addClass('active-sub-menu');
        $('#userType').change(function(){
            var type = $('#userType').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('user.fetch')}}",
                method:"POST",
                data:{type:type,_token:_token},
                success:function(data)
                {
                    jQuery('#single_select2').empty().append('<option selected="selected" value="">Select super user</option>');
                    $.each(data, function (key,value) {
                        jQuery('#single_select2').append($('<option></option>').attr('value', value.id).text(value.name));
                    });

                }
            })
        })

        $('#userType').change(function(){
            var type = $('#userType').val();
            $('#userLevel').change(function(){
                var type = $('#userType').val();
                var level = $('#userLevel').val();
                if(type=='2' && level =='1')
                {
                    $('#type_display').css("display","none");
                    $("#hotel_display").css("display","none");
                    $("#super_display").css("display","block");
                    $('#super_user_field').css("display","none");
                    $('.proceed_hide').css("display","none");
                }
                else if(type=='2' && level =='2')
                {
                    $('#type_display').css("display","none");
                    $("#hotel_display").css("display","block");
                    $("#super_display").css("display","none");
                    $('.proceed_hide').css("display","block");
                    $('#super_user_field').css("display","block");
                }
                else
                {
                    $('.proceed_hide').css("display","none");
                    $('#type_display').css("display","block");
                    $("#hotel_display").css("display","none");
                    $("#super_display").css("display","none");
                }
            });
            if(type=='1'){
                $('.mou_hide').css("display","none");
            }
            else{
                $('.mou_hide').css("display","block");

            }
        });

        $(document).on('keyup','.select2-search__field',function(e){
            var search_super = $(this).val();
            var type = $('#userType').val();
            var _token = $('input[name="_token"]').val();
            var newValue = [];
            if(search_super.length>=2)
            {
                $.ajax({
                    url:"{{ route('search_user.fetch')}}",
                    method:"POST",
                    data:{search_super:search_super,type:type,_token:_token},
                    
                    success:function(data)
                    {
                        // jQuery('#single_select2').empty().append('<option selected="selected" value="">Select super user</option>');
                        $.each(data, function (key,value) {
                            var newOption = new Option(value.name, value.id, false, false);
                            newValue.push(newOption);
                            // jQuery('#single_select2').append($('<option></option>').attr('value', value.id).text(value.name));
                        });
                        $('#single_select2').append(newValue);
                    }
                })
            }
        })

    });
</script>
@endsection
