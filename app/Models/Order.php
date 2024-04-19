<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $customer_id
 * @property int        $order_datetime
 * @property int        $order_upd_datetime
 * @property string     $gift_message
 * @property string     $is_gift_order
 * @property int        $coupon_id
 * @property string     $coupon_code
 * @property string     $gc_code
 * @property string     $payment_type
 * @property string     $payment_method
 * @property string     $ccinfo
 * @property string     $transaction_info
 * @property string     $payment_gateway_response
 * @property string     $order_comment
 * @property string     $customer_comment
 * @property string     $admin_remark
 * @property string     $customer_ip
 * @property string     $customer_browser
 * @property string     $currency_info
 * @property string     $bill_first_name
 * @property string     $bill_last_name
 * @property string     $bill_company
 * @property string     $bill_address1
 * @property string     $bill_address2
 * @property string     $bill_city
 * @property string     $bill_zip
 * @property string     $bill_state
 * @property string     $bill_country
 * @property string     $bill_phone
 * @property string     $bill_email
 * @property string     $shipping_information
 * @property string     $shipping_carrier
 * @property string     $ship_first_name
 * @property string     $ship_last_name
 * @property string     $ship_company
 * @property string     $ship_address1
 * @property string     $ship_address2
 * @property string     $ship_city
 * @property string     $ship_zip
 * @property string     $ship_state
 * @property string     $ship_country
 * @property string     $ship_phone
 * @property string     $ship_email
 * @property string     $ship_method
 * @property string     $tracking_no
 * @property string     $refund_transaction_response
 * @property string     $refund_comment
 * @property int        $representative_id
 * @property string     $shipper
 * @property string     $available_shipping_method
 * @property string     $terminal_address
 * @property string     $installation_info
 * @property int        $delayed_email_send_date
 */
class Order extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hba_orders';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'order_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'order_datetime', 'order_upd_datetime', 'sub_total', 'shipping_amt', 'tax', 'handling_charge', 'auto_discount', 'quantity_discount', 'coupon_amount', 'coupon_id', 'coupon_code', 'order_total', 'payment_type', 'payment_method', 'pay_status', 'status', 'ccinfo', 'transaction_info', 'payment_gateway_response', 'order_comment', 'customer_comment', 'admin_remark', 'customer_ip', 'customer_browser', 'currency_info', 'checkout_type', 'bill_first_name', 'bill_last_name', 'bill_company', 'bill_address1', 'bill_address2', 'bill_city', 'bill_zip', 'bill_state', 'bill_country', 'bill_phone', 'bill_email', 'shipping_information', 'ship_first_name', 'ship_last_name', 'ship_company', 'ship_address1', 'ship_address2', 'ship_city', 'ship_zip', 'ship_state', 'ship_country', 'ship_phone', 'ship_email', 'ship_status', 'ship_method', 'tracking_no', 'total_refund_amount', 'refund_transaction_response', 'refund_comment','avalara_transaction_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'customer_id' => 'int', 'order_datetime' => 'timestamp', 'order_upd_datetime' => 'timestamp', 'coupon_id' => 'int', 'coupon_code' => 'string', 'payment_type' => 'string', 'payment_method' => 'string', 'ccinfo' => 'string', 'transaction_info' => 'string', 'payment_gateway_response' => 'string', 'order_comment' => 'string', 'customer_comment' => 'string', 'admin_remark' => 'string', 'customer_ip' => 'string', 'customer_browser' => 'string', 'currency_info' => 'string', 'bill_first_name' => 'string', 'bill_last_name' => 'string', 'bill_company' => 'string', 'bill_address1' => 'string', 'bill_address2' => 'string', 'bill_city' => 'string', 'bill_zip' => 'string', 'bill_state' => 'string', 'bill_country' => 'string', 'bill_phone' => 'string', 'bill_email' => 'string', 'shipping_information' => 'string', 'shipping_carrier' => 'string', 'ship_first_name' => 'string', 'ship_last_name' => 'string', 'ship_company' => 'string', 'ship_address1' => 'string', 'ship_address2' => 'string', 'ship_city' => 'string', 'ship_zip' => 'string', 'ship_state' => 'string', 'ship_country' => 'string', 'ship_phone' => 'string', 'ship_email' => 'string', 'ship_method' => 'string', 'tracking_no' => 'string', 'refund_transaction_response' => 'string', 'refund_comment' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'order_datetime', 'order_upd_datetime'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;
    const CREATED_AT = 'order_datetime';
    const UPDATED_AT = 'order_upd_datetime';

    // Scopes...

    // Functions ...
    public function next(){
		// get next order for admin panel
		return Order::where('order_id', '>', $this->order_id)->orderBy('order_id', 'asc')->first();
	}
	public  function previous(){
		// get previous  order for admin panel
		return Order::where('order_id', '<', $this->order_id)->orderBy('order_id', 'desc')->first();
	}

    // Relations ...
    public function customer()
	{
		return $this->belongsTo(Customer::class, 'customer_id');
	}

	public function orderItems()
	{
		return $this->hasMany(OrderDetail::class, 'order_id');
	}

    public function returnOrderItems()
	{
		return $this->hasMany(OrderDetail::class, 'order_id')->where('is_return_request', '1');
	}

    public function returnAcceptedOrderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id')->where('is_return_request', '1')->where('return_request_accept_reject', '1');
    }

    public function returnRejectedOrderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id')->where('is_return_request', '1')->where('return_request_accept_reject', '0');
    }
	
	public function products()
	{
		return $this->belongsToMany(Product::class, 'hba_order_detail', 'order_id', 'products_id');
	}

}
