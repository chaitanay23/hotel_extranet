@extends('layouts.app')

@section('login-content')

    <img src="{{asset('images/biddr_logo.png')}}">

           
                <h1 class="log-head">{{ __('Welcome') }}</h1>

                
                    <form class="form-log" method="POST" action="{{ route('login') }}">
                        @csrf

                                <input id="email" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                       
                                <input id="password" type="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                    
                                    <!-- <input  class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="chek-form" class="form-check-label show-data" for="remember">
                                        {{ __('Remember Me') }}
                                    </label> -->
                                    

                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                <!-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif --><br>
                                <div class="forget">
                                    <a href="#" id="forget_user">Forget Username?</a><br> 
                                    <a href="#" id="forget_password">Forget Password?</a>
                                </div>
                         
                    </form>
                </div>
                  <ul class="bg-bubbles">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                  </ul>


            </div>
       



<script>
jQuery(document).ready(function($){
    $("#login-button").click(function (event) {
        event.preventDefault();

        $('form').fadeOut(500);
        $('.wrapper').addClass('form-success');
    });
    $('#forget_user').click(function(){
        var email = "extranetadmin@biddr.in";
        var subject = "Forget Username";

        window.location = 'mailto:' + email + '?subject=' + subject;
    });
    $('#forget_password').click(function(){
        var username = $('#email').val();
        var email = "extranetadmin@biddr.in";
        var subject = "Forget Password";
        var emailBody = "Please reset my password. Username is "+username;
        if(username)
        {
            window.location = 'mailto:' + email + '?subject=' + subject + '&body=' +   emailBody;
        }
        else{
            alert("Enter Username Please");
        }
    });
});


</script>

@endsection

