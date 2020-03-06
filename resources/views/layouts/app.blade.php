<html lang="{{ app()->getLocale() }}">

<head>

    <!-- Tabs jquery styles and js from jquery steps -->
    <link rel="shortcut icon" sizes="180x180" href="{{asset('images/biddr_logo.png')}}">
  <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Biddr') }}</title>
    
    <script
    src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>
    <script src="{{ asset('js/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('js/dialog_box.js')}}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/custom_jquery.js')}}"></script>
    <script src="{{ asset('js/time_clock.js')}}"></script>
    <script src="{{ asset('js/navigation.js')}}"></script>
    <script src="{{ asset('js/side-menu_search.js')}}"></script>
    <script src="{{ asset('js/select2.min.js')}}"></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-                UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="{{ asset('css/dialog_box.css')}}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/addon_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/datepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom_show_page.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/navigation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/mex.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/login.css')}}">
</head>

<body>

    <div id="app">

       
        @guest
        <div class="wrapper">
          <div class="container1">

            @yield('login-content')

          
        @else
        
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
        
        <div class="page-wrapper chiller-theme toggled">
          <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
            <i class="fas fa-bars"></i>
          </a>
          <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
              <div class="sidebar-brand">
                <a href="{{ url('/home') }}"><img src="{{asset('images/biddr_logo.png')}}"></a>
                <!-- <div id="close-sidebar">
                  <i class="fas fa-times"></i>
                </div> -->
              </div>
              <div class="sidebar-header">
                <!-- <div class="user-pic">
                  <img class="img-responsive img-rounded" src="img/user.png"
                    alt="User picture">
                </div> -->
                <div class="user-info">
                  <span class="user-name">
                    <strong style="color:#f5a522">{{ Auth::user()?Auth::user()->name:'' }}</strong>
                  </span>
                  <span class="user-role">Administrator</span>
                  <span class="user-status">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                  </span>
                </div>
              </div>
              <!-- sidebar-header  -->
              <div class="sidebar-search">
                <div>
                  <div class="input-group">
                    <input type="text" id="search" class="form-control search-menu" placeholder="Search...">
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="fa fa-search" aria-hidden="true"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <!-- sidebar-search  -->
                    <div class="sidebar-menu">
                      <ul>
                      @can('user_list')
                        <li class="sidebar-dropdown">
                          <a href="#" class="user">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>Users & Roles</span>
                            </a>
                            <div class="sidebar-submenu user">
                              <ul>
                                @can('user_list')
                                <li>
                                  <a href="{{ route('users.index') }}" class="user-inter">Users

                                  </a>
                                </li>
                                @endcan
                                @can('role_list')
                                <li>
                                  <a href="{{ route('roles.index') }}" class="role">Roles</a>
                                </li>
                                @endcan
                              </ul>
                            </div>
                          </li>
                          @endcan
                          @can('hotel_list')
                        <li class="sidebar-dropdown">
                          <a href="#" class="property">
                           <i class="fas fa-building"></i>
                            <span>Property</span>
                            </a>
                          <div class="sidebar-submenu property">
                            <ul>
                              @can('hotel_list')
                              <li>
                                <a href="{{ route('hotel.index') }}" class="hotel">Hotel

                                </a>
                              </li>
                              @endcan
                              @can('hotel_type_list')
                              <li>
                                <a href="{{ route('hotel_type.index') }}" class="hotel_type">Hotel Type</a>
                              </li>
                              @endcan
                              @can('room_list')
                              <li>
                                <a href="{{ route('room.index') }}" class="room">Room</a>
                              </li>
                              @endcan
                              @can('contact_detail_list')
                              <li>
                                <a href="{{ route('contact.index') }}" class="contact">Contact Details</a>
                              </li>
                              @endcan
                              @can('status_report')
                              <li>
                                <a href="{{ route('status_report.index') }}" class="status">Property Status</a>
                              </li>
                              @endcan
                             </ul>
                          </div>
                        </li>
                        @endcan
                        @can('channel_manager_list')
                        <li class="">
                          <a href="{{ route('channel.index') }}" class="channel">
                            <i class="fa fa-male" aria-hidden="true"></i>
                            <span>Channel Manager</span>
                          </a>
                        </li>
                        @endcan
                        @can('commission_list')
                        <li class="sidebar-dropdown">
                          <a href="#" class="commission">
                            <i class="fa fa-chart-line"></i>
                            <span>Commission</span>
                          </a>
                          <div class="sidebar-submenu commission">
                            <ul>
                              @can('commission_list')
                              <li>
                                <a href="{{ route('commission.index') }}" class="ms_com">MS Commission</a>
                              </li>
                              @endcan
                              @can('bank_detail_list')
                              <li>
                                <a href="{{ route('bank_detail.index') }}" class="bank">Bank Details</a>
                              </li>
                              @endcan
                             </ul>
                          </div>
                        </li>
                        @endcan
                        @can('discount_mapping_list')
                        <li class="">
                          <a href="{{ route('discount_mapping.index') }}" class="discount">
                            <i class="fa fa-globe"></i>
                            <span>Discount Mapping</span>
                          </a>
                        </li>
                        @endcan
                        @can('addon')
                        <li class="sidebar-dropdown">
                          <a href="#" class="add-on">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <span>Add Offers</span>
                          </a>
                          <div class="sidebar-submenu add-on">
                            <ul>
                              @can('addon')
                              <li>
                                <a href="{{ route('addon.index') }}" class="addon-inn">Addon</a>
                              </li>
                              @endcan
                              @can('addon_type')
                              <li>
                                <a href="{{ route('addon_type.index') }}" class="add-type">Addon Type</a>
                              </li>
                              @endcan
                             </ul>
                          </div>
                        </li>
                        @endcan
                        @can('daily_inventory')
                        <li class="sidebar-dropdown">
                          <a href="#" class="inventory">
                            <i class="fas fa-money-check"></i>
                            <span>Rate & Inventory</span>
                          </a>
                          <div class="sidebar-submenu inventory">
                            <ul>
                              @can('daily_inventory')
                              <li>
                                <a href="{{ route('inventory.index') }}" class="daily_invent">Inventory</a>
                              </li>
                              @endcan
                              @can('update_inventory')
                              <li>
                                <a href="{{ route('update_inventory.index') }}" class="update_invent">Update Inventory</a>
                              </li>
                              @endcan
                             </ul>
                          </div>
                        </li>
                        @endcan
                        @can('finance_report')
                        <li class="sidebar-dropdown">
                          <a href="#" class="fin-report">
                          <i class="fas fa-chart-bar"></i>
                            <span>Finance Reports</span>
                          </a>
                          <div class="sidebar-submenu fin-report">
                            <ul>
                              @can('booking_report')
                              <li>
                                <a href="{{ route('bookings') }}" class="booking">Booking Report</a>
                              </li>
                              @endcan
                              @can('payment_report')
                              <li>
                                <a href="{{ route('payment') }}" class="payment">Payment Report</a>
                              </li>
                              @endcan
                              @can('sales_report')
                              <li>
                                <a href="{{ route('sales') }}" class="sales">Sales Report</a>
                              </li>
                              @endcan
                             </ul>
                          </div>
                        </li>
                        @endcan
                        @can('bid_report')
                        <li class="sidebar-dropdown">
                          <a href="#" class="bid-report">
                          <i class="fas fa-gavel"></i>
                            <span>Bid Reports</span>
                          </a>
                          <div class="sidebar-submenu bid-report">
                            <ul>
                              @can('unsuccess_report')
                              <li>
                                <a href="{{ route('unsuccessful') }}" class="unsuccess">Unsuccessful Report</a>
                              </li>
                              @endcan
                              @can('booked_report')
                              <li>
                                <a href="{{ route('booked') }}" class="booked">Booked Report</a>
                              </li>
                              @endcan
                              @can('notbooked_report')
                              <li>
                                <a href="{{ route('notbooked') }}" class="notbooked">Notbooked Report</a>
                              </li>
                              @endcan
                              @can('nego_report')
                              <li>
                                <a href="{{ route('negotiable') }}" class="negotiable">Negotiable Report</a>
                              </li>
                              @endcan
                             </ul>
                          </div>
                        </li>
                        @endcan
                  			
                          
                      
                      </ul>
                    </div>
                    <!-- sidebar-menu  -->
                  </div>
                </nav>
               

              </div>
          </div>
     

              

                    <!-- <ul class="navbar-nav mr-auto"></ul> -->

                   
                       

        <div class="col-sm-10" style="overflow-x: hidden;">
            <main class="main-display">

                <div class="container-fluid">

                @yield('content')

                </div>

            </main>
        </div>
         @endguest
         </div>
     </div>

    

</body>

</html>
