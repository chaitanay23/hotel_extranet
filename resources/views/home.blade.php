@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-4">
            <div class="card-show-dis">
                <div class="card-header"><h2>Dashboard</h2></div>

                <div class="card-body show-data">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Dashboard coming soon...
                    <!-- You are logged in! -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
