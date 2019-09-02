@extends('layouts.app')


@section('content')

<div class="row">
<div class="col-sm-12 mt-4">
        <h2 class="heder">User</h2>
    </div>
    <div class="col-sm-4">

        <div class="pull-right ml-2">

            <a class="eye1" href="{{ url()->previous() }}"> <i class="fas fa-long-arrow-alt-left"></i>
</a>

        </div>

    </div>

</div>


<div class="container">
    <div class="card-show col-md-4 pt-3 mt-4 pb-3 pl-4">
        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
            <input type="hidden" id ="user_profile" value="{{$user->profile_pic}}">
            <i class="fas fa-user-circle" id="no_profile"></i>
            <div class="form-group profile-pic" id="profile-pic">
                {!! Html::image($user->profile_pic, 'alt',array('class' => 'img-thumbnail user-image'))!!}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 mt-4">

            <div class="form-group">

                <strong>Hotel User:</strong>

                <span class="show-data"> {{ $user->name }}</span>

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Username:</strong>

                <span class="show-data">{{ $user->email }}</span>

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Email:</strong>

                <span class="show-data">{{ $user->primary_email }}</span>

            </div>

        </div>
        <input type="hidden" id ="mobile_data" value="{{$user->mobile}}">
        <div class="col-xs-12 col-sm-12 col-md-12" id="mobile_detail">

            <div class="form-group">

                <strong>Mobile:</strong>

                <span class="show-data">{{ $user->mobile }}</span>

            </div>

        </div>

        @if(!empty($super->name))
        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Super User:</strong>
                    
                    <span class="show-data">{{$super->name}}</span>
                
            </div>

        </div>
        @endif
        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Permissions:</strong>

                @if(!empty($user->getRoleNames()))

                    @foreach($user->getRoleNames() as $v)

                        <label class="badge badge-success">{{ $v }}</label>

                    @endforeach

                @endif

            </div>

        </div>
        <input type="hidden" id ="mou_pdf" value="{{$mou}}">
        <div class="col-xs-12 col-sm-12 col-md-12" id="mou_link">
            <div class="form-group">
                <strong>MOU:</strong>
                <a class="show-data" href="{{$mou}}" target="_blank">Mou</a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12" id="property_mou">
            <div class="form-group">
                <strong>Property MOU:</strong>
                @foreach($chain_property as $chain)
                    <input type="hidden" id ="chain_mou_pdf" value="{{$chain->mou}}">
                    <a href="{{$chain->mou}}" class="show-data" target="_blank">Property Mou</a>,
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        $('.user').addClass('active-menu');
        $('.user-inter').addClass('active-sub-menu');
        var mou_pdf = $('#mou_pdf').val();
        var chain_mou_pdf = $('#chain_mou_pdf').val();
        var mobile_data = $('#mobile_data').val();
        var profile_pic = $('#user_profile').val();
        $('#mou_link').css('display','none');
        $('#no_profile').css('display','none');
        $('#mobile_detail').css('display','none');
        if(mou_pdf)
        {
            $('#mou_link').css('display','block');
        }
        $('#property_mou').css('display','none');
        if(chain_mou_pdf)
        {
            $('#property_mou').css('display','block');
        }
        if(mobile_data)
        {
            $('#mobile_detail').css('display','block');
        }
        if(!profile_pic)
        {
            $('#no_profile').css('display','block');
            $('#profile-pic').css('display','none');
        }

    });
</script>
@endsection
