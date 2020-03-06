<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/',function(){
	return Redirect::route('login');
});

Route::get('/login', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('room/create_type','RoomController@create_type')->name('room.create_type');
Route::get('addon/create_type','AddonController@create_type')->name('addon.create_type');
Route::group(['middleware' => ['auth']], function() {

    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::resource('hotel_type','HotelTypeController');
	Route::resource('hotel','HotelController');
	Route::resource('room','RoomController');
	Route::resource('commission','CommissionsController');
	Route::resource('bank_detail','BankController');
	Route::resource('contact','ContactsController');
	Route::resource('discount_mapping','DiscountMappingController');
	Route::resource('channel','ChannelManagerController');
	Route::resource('addon_type','AddonTypeController');
	Route::resource('addon','AddonController');
	Route::resource('inventory','InventoryController');
	Route::resource('update_inventory','UpdateInventoryController');
	Route::resource('status_report','StatusReportController');
	Route::post('users/create/fetch','UserController@fetch')->name('user.fetch');
	Route::post('users/create/edit_fetch','UserController@edit_fetch')->name('edit_user.fetch');
	Route::post('users/create/super_fetch','UserController@super_fetch')->name('search_user.fetch');
	
	//ajax controller to fetch region and area based on city
	Route::post('hotel/create/user_fetch','HotelController@hotel_fetch')->name('hotel.fetch');
	Route::post('hotel/create/edit_user_fetch','HotelController@edit_hotel_fetch')->name('edit_hotel.fetch');
	Route::post('hotel/create/hotel_user_fetch','HotelController@search_hotel_fetch')->name('search_hotel.fetch');
	Route::post('hotel/create/rm_user_fetch','HotelController@rm_user_fetch')->name('rm.fetch');
	Route::post('hotel/create/revenue_user_fetch','HotelController@revenue_user_fetch')->name('revenue.fetch');
	Route::post('hotel/create/city_fetch','HotelController@city_fetch')->name('city.fetch');
	Route::post('hotel/create/region_fetch','HotelController@region_fetch')->name('region.fetch');
	Route::post('hotel/create/area_fetch','HotelController@area_fetch')->name('area.fetch');
	//Route::post('hotel/{id}/edit','HotelController@fetch');
	//ajax controller to fetch super user based on type of user
	Route::post('room/create/hotel_user_fetch','RoomController@hotel_fetch')->name('room_hotel.fetch');
	Route::post('room/create/edit_hotel_user_fetch','RoomController@edit_hotel_fetch')->name('edit_room_hotel.fetch');
	Route::post('hotel/create/room_user_fetch','RoomController@search_hotel_fetch')->name('search_room_hotel.fetch');
	Route::post('room/status_change_on','RoomController@status_change_on')->name('room_status_on.change');
	Route::post('room/status_change_off','RoomController@status_change_off')->name('room_status_off.change');
	Route::post('room/create/user_fetch','RoomController@user_fetch')->name('room_user.fetch');
	Route::post('commission/create/hotel_user_fetch','CommissionsController@hotel_fetch')->name('commission_hotel.fetch');
	Route::post('commission/create/edit_hotel_user_fetch','CommissionsController@edit_hotel_fetch')->name('edit_commission_hotel.fetch');
	Route::post('commission/create/search_hotel_user_fetch','CommissionsController@search_hotel_fetch')->name('search_commission_hotel.fetch');
	Route::post('bank_detail/create/hotel_user_fetch','BankController@hotel_fetch')->name('bank_detail_hotel.fetch');
	Route::post('bank_detail/create/edit_hotel_user_fetch','BankController@edit_hotel_fetch')->name('edit_bank_detail_hotel.fetch');
	Route::post('bank_detail/create/search_hotel_user_fetch','BankController@search_hotel_fetch')->name('search_bank_hotel.fetch');
	Route::post('bank_detail/create/user_fetch','BankController@user_fetch')->name('bank_detail_user.fetch');
	Route::post('contact/create/hotel_user_fetch','ContactsController@hotel_fetch')->name('contact_hotel.fetch');
	Route::post('conatct/create/user_fetch','ContactsController@user_fetch')->name('contact_user.fetch');
	Route::post('contact/create/search_hotel_user_fetch','ContactsController@search_hotel_fetch')->name('search_contact_hotel.fetch');
	Route::post('discount_mapping/create/hotel_user_fetch','DiscountMappingController@hotel_fetch')->name('discount_hotel.fetch');
	Route::post('discount_mapping/create/edit_hotel_user_fetch','DiscountMappingController@edit_hotel_fetch')->name('edit_discount_hotel.fetch');
	Route::post('discount_mapping/create/search_hotel_user_fetch','DiscountMappingController@search_hotel_fetch')->name('search_discount_hotel.fetch');
	Route::post('channel/hotel','ChannelManagerController@hotel_fetch')->name('channel_hotel.fetch');
	Route::post('channel/edit_hotel','ChannelManagerController@edit_hotel_fetch')->name('edit_channel_hotel.fetch');
	Route::post('channel/user','ChannelManagerController@user_fetch')->name('channel_user.fetch');
	Route::post('channel/partner','ChannelManagerController@channel_fetch')->name('channel_partner.fetch');
	Route::post('channel/status_on','ChannelManagerController@status_update_on')->name('channel_status_on.update');
	Route::post('channel/status_off','ChannelManagerController@status_update_off')->name('channel_status_off.update');
	Route::post('channel/create/search_hotel_user_fetch','ChannelManagerController@search_hotel_fetch')->name('search_channel_hotel.fetch');
	Route::post('addon/hotel','AddonController@hotel_fetch')->name('addon_hotel.fetch');
	Route::post('addon/search_hotel','AddonController@search_hotel_fetch')->name('search_addon_hotel.fetch');
	Route::post('addon/show','AddonController@show_addon')->name('addon.show');
	Route::post('addon/user','AddonController@user_fetch')->name('addon_user.fetch');
	Route::post('addon/toggle_on','AddonController@toggle_on')->name('addon_status_on.fetch');
	Route::post('addon/toggle_off','AddonController@toggle_off')->name('addon_status_off.fetch');
	Route::post('update_inventory/hotel_fetch','UpdateInventoryController@hotel_fetch')->name('update_inventory_hotel.fetch');
	Route::post('update_inventory/room_fetch','UpdateInventoryController@room_fetch')->name('room_update_inventory.fetch');
	Route::post('update_inventory/search_hotel_fetch','UpdateInventoryController@search_hotel_fetch')->name('search_update_inventory_hotel.fetch');
	Route::post('update_inventory/price_update','UpdateInventoryController@update_price')->name('update_inventory_price.update');
	Route::post('inventory/edit_hotel_user_fetch','InventoryController@edit_hotel_fetch')->name('edit_inventory_hotel.fetch');
	Route::post('inventory/hotel_fetch','InventoryController@hotel_fetch')->name('inventory_hotel.fetch');
	Route::post('inventory/search_hotel_fetch','InventoryController@search_hotel_fetch')->name('search_inventory_hotel.fetch');
	Route::post('inventory/room_fetch','InventoryController@room_fetch')->name('room_inventory.fetch');
	Route::post('inventory/show','InventoryController@show_inventory')->name('inventory.show');
	Route::post('inventory/update_data','InventoryController@update_inventory_data')->name('update_inventory_data.fetch');
	Route::post('inventory/update_status_on','InventoryController@status_update_on')->name('inventory_status_on.fetch');
	Route::post('inventory/update_status_off','InventoryController@status_update_off')->name('inventory_status_off.fetch');
	Route::post('status_report/launch_change_on','StatusReportController@status_change_on')->name('status_launch.change_on');
	Route::post('status_report/launch_change_off','StatusReportController@status_change_off')->name('status_launch.change_off');

	//reports
	Route::get('bookings', 'ReportController@showBookings')->name('bookings');
	Route::get('payment', 'ReportController@showPayment')->name('payment');
	Route::get('sales', 'ReportController@showSales')->name('sales');
	Route::get('notbooked', 'ReportController@NotBooked')->name('notbooked');
	Route::get('booked', 'ReportController@Booked')->name('booked');
	Route::get('unsuccessful', 'ReportController@Unsuccessful')->name('unsuccessful');
	Route::get('negotiable', 'ReportController@Negotiable')->name('negotiable');
	Route::get('negotiable', 'ReportController@Negotiable')->name('negotiable');
	Route::post('getPaymentReason', 'ReportController@GetReasonForPayment')->name('paymentReason');
	Route::post('getOtherPaymentReason', 'ReportController@GetOtherReason')->name('otherPaymentReason');
	Route::post('getCCDetails', 'ReportController@GetCCDetails')->name('getCCDetails');
	Route::post('getStorePaymentDetails', 'ReportController@GetPaymentDetails')->name('getStorePaymentDetails');
	Route::post('getBankDetails', 'ReportController@GetBankDetails')->name('getBankDetails');
	Route::get('hotels/hotelvoucher/{id}', function ($id) {
				return view('vouchers.new_templates.hotel-voucher')->with('id',$id);
				});
	Route::get('users/hotelvoucher/{id}', function ($id) {
			return view('vouchers.new_templates.booking_voucher')->with('id',$id);
				});
	Route::get('restaurant/Couponvoucher/{id}', function ($id) {
			return view('vouchers.restaurant-coupon-voucher')->with('id',$id);
				});
});


