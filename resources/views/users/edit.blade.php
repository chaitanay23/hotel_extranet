@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-12 mt-4">
        <h2 class="heder">User</h2>
    </div>
    <div class="col-sm-4">
        <div class="pull-right ml-2">
            <a class="eye1" href="{{ url()->previous() }}"> <i class="fas fa-long-arrow-alt-left"></i></a>
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


{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id],'enctype' => 'multipart/form-data']) !!}
<div class="form form-padding">
    <div class="row">
        <input type="hidden" value="{{$user->user_role_define}}" id="userType">
        @if(Auth::user()->user_role_define == '1')
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>User Type:</strong>
                {!! Form::select('user_role_define',['1' => 'Biddr','2' => 'Hotel','3'=>'Revenue Manager','4'=>'Channel Manager'],null,['placeholder' => 'Select user type...','class' => 'form-control','required']) !!}
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>User Level:</strong>
                {!! Form::select('user_level',['1' => 'Super User','2' => 'Local User'],null,['placeholder' => 'Select user level...','class' => 'form-control','id' => 'userLevel','required']) !!}
            </div>
        </div>
        @endif
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong style="display: none" id="type_display">Name:</strong>
                <strong style="display: none" id="super_display">Group Name:</strong>
                <strong id="hotel_display">Legal Name:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Username:</strong>
                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control','required')) !!}
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Password:</strong>
                {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
            </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Confirm Password:</strong>
                {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
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
        @can('user_edit')
        <div class="col-xs-12 col-sm-12 col-md-12" id="super_user_field">
            <div class="form-group">
                <strong>Super User (optional):</strong>
                <select class="form-control" id="single_select2" name='super_user_id'>
                    <option value="0">Select super user</option>
                </select>
                <input type="hidden" value={{$user->super_user_id}} id="super_user">
            </div>
        </div>
        @endcan
        @can('user_edit')
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Permissions:</strong>
                {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control','multiple','id'=>'multi_select2','required')) !!}
            </div>
        </div>
        @endcan
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Profile Picture:</strong>
                {!! Form::file('profile_pic', array('placeholder' => 'Profile pic','class' => 'image form-control'))!!}
            </div>
        </div>
        @can('user_delete')
        <div class="col-xs-12 col-sm-12 col-md-12 mou_hide">
            <div class="form-group">
                <strong>MOU Certificate:</strong>
                {!! Form::file('mou', array('placeholder' => 'MOU Certificate','class' => 'image form-control'))!!}
            </div>
        </div>
        @endcan
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">

            <button type="submit" class="btn btn-primary submit-btn-hex">Update</button>

        </div>

    </div>
</div>
{{ Form::hidden('url',URL::previous())  }}
{!! Form::close() !!}
<script>
    jQuery(document).ready(function($) {
        var newValue = [];
        $('.user-inter').addClass('active-sub-menu');
        $('#single_select2').select2({
            allowClear: true,
        });
        $('#multi_select2').select2({
            placeholder: 'Choose roles',
            closeOnSelect: false,
            allowClear: true,
        });
        $('.user').addClass('active-menu');
        var type = $('#userType').val();
        var super_user = $('#super_user').val();
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('edit_user.fetch')}}",
            method: "POST",
            data: {
                _token: _token,
                type: type,
                super_user_id: super_user
            },
            success: function(data) {
                $.each(data, function(key, value) {
                    if (value.id == super_user) {
                        $('#single_select2').append($('<option></option>').attr('value', value.id).text(value.name).attr('selected', 'selected'));
                    } else {
                        $('#single_select2').append($('<option></option>').attr('value', value.id).text(value.name));
                        newValue.push(value.id);
                    }
                });
            }
        });
        // $.ajax({
        //     url:"{{ route('edit_user.fetch')}}",
        //     method:"POST",
        //     data:{type:type,_token:_token,super_id:super_user},
        //     success:function(data)
        //     {
        //         $.each(data, function (key,value) {
        //             jQuery('#single_select2').append($('<option></option>').attr('value', value.id).text(value.name).attr('selected','selected'));
        //         });

        //     }
        // })

        var type = $('#userType').val();
        if (type == '1') {
            $('.mou_hide').css("display", "none");
        }

        $('#userType').change(function() {
            var type = $('#userType').val();
            $('#userLevel').change(function() {
                var level = $('#userLevel').val();
                if (type == '2' && level == '1') {
                    $('#type_display').css("display", "none");
                    $("#hotel_display").css("display", "none");
                    $("#super_display").css("display", "block");
                } else if (type == '2' && level == '2') {
                    $('#type_display').css("display", "none");
                    $("#hotel_display").css("display", "block");
                    $("#super_display").css("display", "none");
                } else {
                    $('#type_display').css("display", "block");
                    $("#hotel_display").css("display", "none");
                    $("#super_display").css("display", "none");
                }
            });
            if (type == '1') {
                $('.mou_hide').css("display", "none");
            }
        });

        var option = [];
        $(document).on('keyup', '.select2-search__field', function(e) {
            var search_super = $(this).val();
            var type = $('#userType').val();
            var _token = $('input[name="_token"]').val();
            if (search_super.length >= 2) {
                $.ajax({
                    url: "{{ route('search_user.fetch')}}",
                    method: "POST",
                    data: {
                        search_super: search_super,
                        type: type,
                        _token: _token
                    },
                    success: function(data) {
                        for (var i = 0; i < data.length; i++) {
                            let result = newValue.includes(data[i].id);
                            if (result == false) {
                                option[i] = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                                newValue.push(data[i].id)
                                $('#single_select2').append(option[i]);
                            }
                        }
                    }
                })
            }
        })
    });
</script>

@endsection