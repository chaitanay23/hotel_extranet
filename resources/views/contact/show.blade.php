@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-12 mt-4 ml-2">
        <h2 class="heder">Contact</h2>
    </div>
    <div class="col-sm-4 margin-tb">
        <div class="pull-right ml-3">

            <a class="eye1" href="{{ url()->previous() }}"><i class="fas fa-long-arrow-alt-left"></i></a>

        </div>
        
    </div>
</div>
<br/>
<div class="container">
    <div class="form form-padding">
    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
        <div class="form-group">
            <strong>Hotel Name:</strong>
            <span class="show-data">{{ $hotel->title }}</span>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card-show col-md-5 pt-3 mt-4 pb-3 pl-4">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <h4 class="mt-3">Reception:-</h4>
                <div class="form-group">
                    <strong>Reception Phone Number:</strong>
                    <span class="show-data">{{ $contact->phone_no }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Reception Email:</strong>
                    <span class="show-data">{{ $contact->email }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Reception Mobile:</strong>
                    <span class="show-data">{{ $contact->mobile }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Website:</strong>
                    <span class="show-data">{{ $contact->website }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Price Negotiation Subscription:</strong>
                    <span class="show-data">
                        @if($contact->nego=='1')
                            Checked
                        @else
                            Unchecked
                        @endif
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Booking Voucher Subscription:</strong>
                    <span class="show-data">
                        @if($contact->voucher=='1')
                            Checked
                        @else
                            Unchecked
                        @endif
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Other SMS:</strong>
                    <span class="show-data">
                        @if($contact->oms=='1')
                            Checked
                        @else
                            Unchecked
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <div class="card-show col-md-5 pt-3 mt-4 pb-3 pl-4 ml-4">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <h4 class="mt-3">Primary:-</h4>
                <div class="form-group">
                    <strong>Primary Email:</strong>
                    <span class="show-data">{{ $contact->pemail }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Primary Mobile Number:</strong>
                    <span class="show-data">{{ $contact->pmobile }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Primary Designation:</strong>
                    <span class="show-data">{{ $contact->pdesignation }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Price Negotiation Subscription:</strong>
                    <span class="show-data">
                        @if($contact->pnego=='1')
                            Checked
                        @else
                            Unchecked
                        @endif
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Booking Voucher Subscription:</strong>
                    <span class="show-data">
                        @if($contact->pvoucher=='1')
                            Checked
                        @else
                            Unchecked
                        @endif
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>SMS Subscription:</strong>
                    <span class="show-data">
                        @if($contact->psms=='1')
                            Checked
                        @else
                            Unchecked
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <div class="card-show col-md-5 pt-3 mt-4 pb-3 pl-4">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <h4 class="mt-3">Secondary:-</h4>
                <div class="form-group">
                    <strong>Seconday Email:</strong>
                    <span class="show-data">{{ $contact->semail }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Seconday Mobile:</strong>
                    <span class="show-data">{{ $contact->smobile }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Secondary Designation: </strong>
                    <span class="show-data">{{ $contact->sdesignation }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Price Negotiation Subscription:</strong>
                    <span class="show-data">
                        @if($contact->snego=='1')
                            Checked
                        @else
                            Unchecked
                        @endif
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Booking Voucher Subscription:</strong>
                    <span class="show-data">
                        @if($contact->svoucher=='1')
                            Checked
                        @else
                            Unchecked
                        @endif
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>SMS Subscription:</strong>
                    <span class="show-data">
                        @if($contact->ssms=='1')
                            Checked
                        @else
                            Unchecked
                        @endif
                    </span>
                </div>
            </div>
        </div>
         <div class="card-show col-md-5 pt-3 mt-4 pb-3 pl-4 ml-4">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <h4 class="mt-3">Accounts:-</h4>
                <div class="form-group">
                    <strong>Account Email:</strong>
                    <span class="show-data">{{ $contact->aemail }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Account Mobile Number:</strong>
                    <span class="show-data">{{ $contact->amobile }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                <div class="form-group">
                    <strong>Account Phone Number:</strong>
                    <span class="show-data">{{ $contact->aphone }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    jQuery(document).ready(function($){
        $('.property').addClass('active-menu');
        $('.contact').addClass('active-sub-menu');
    });
</script>
@endsection
