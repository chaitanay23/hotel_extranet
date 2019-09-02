@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-12 mt-4">
        <h2 class="heder">Bank Details</h2>
    </div>
    <div class="col-sm-4 margin-tb">
        <div class="pull-right ml-2">
            <a class="eye1" href="{{ url()->previous() }}"><i class="fas fa-long-arrow-alt-left"></i></a>
        </div>
    </div>
</div>
<br/>
<div class="container">
    <div class="card-show col-md-8 pt-3 mt-4 pb-3 pl-4">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>Hotel Name:</strong>
                    <span class="show-data"> {{ $hotel->title }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>Account Number:</strong>
                    <span class="show-data"> {{ $bank->account_no }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>Account Holder:</strong>
                    <span class="show-data"> {{ $bank->account_holder }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>Branch Name:</strong>
                    <span class="show-data"> {{ $bank->branch_name }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>IFSC Code:</strong>
                    <span class="show-data"> {{ $bank->ifsc_code }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>Branch Code:</strong>
                    <span class="show-data"> {{ $bank->branch_code }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>Bank Name:</strong>
                    <span class="show-data"> {{ $bank_name->name }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>Bank Code:</strong>
                    <span class="show-data"> {{ $bank->bank_code }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>PAN Number:</strong>
                    <span class="show-data"> {{ $bank->pan_number }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>Name on PAN card:</strong>
                    <span class="show-data"> {{ $bank->name_of_pancard }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>Service Tax Number:</strong>
                    <span class="show-data"> {{ $bank->service_tx_no }}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 mt-4">
                <div class="form-group">
                    <strong>GST Number:</strong>
                    <span class="show-data"> {{ $bank->gst_number }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Payment Option:</strong>
                    <span class="show-data"> @if($bank->payment_id=='1')
                        National Electronic Funds Transfer (NEFT)
                    @else
                        Credit Card
                    @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  jQuery(document).ready(function($){
    $('.commission').addClass('active-menu');
    $('.bank').addClass('active-sub-menu');
  });
</script>
@endsection
