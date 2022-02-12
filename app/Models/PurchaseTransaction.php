<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PurchaseTransaction extends Model
{
	use HasFactory;
	
    //disable automatic timestamps since the required field is not available
    public $timestamps = false;

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public static function checkCustomer($customer_id) {
        $sqlCheckCustomer = "SELECT customer_id, SUM(total_spent) as grand_total, count(customer_id) as total_transaction
				FROM purchase_transactions
				WHERE customer_id = ? AND transaction_at >= (CURDATE() - INTERVAL ? DAY)
				GROUP BY customer_id";

        $result = DB::select($sqlCheckCustomer, [$customer_id, config('constants.ELIGIBLE.MAX_LAST_TRANSACTION')]);
        
        if (!empty($result)) {
            return $result[0];
        } else {
            return null;
        }
    }

    public static function checkCustomerVoucher($customer_id) {
        $sqlCustomerVoucher = "SELECT *, TIMESTAMPDIFF(MINUTE, updated_at, now()) as time_running
				FROM vouchers 
				WHERE customer_id = ? AND (status >= ? OR (status = ? AND TIMESTAMPDIFF(MINUTE, updated_at, now()) < ?))";

        $result = DB::select($sqlCustomerVoucher, [
            $customer_id, 
            config('constants.VOUCHER.STATUS_OWNED'), 
            config('constants.VOUCHER.STATUS_LOCKED'),
            config('constants.ELIGIBLE.LOCK_TIME')
        ]);

        if (!empty($result)) {
            return $result[0];
        } else {
            return null;
        }
    }

    public static function checkVoucher($customer_id) {
        $sqlCheckVoucher = "SELECT * FROM vouchers
				WHERE (status = ? OR (status = ? AND TIMESTAMPDIFF(MINUTE, updated_at, now()) >= ?))
				AND (SELECT count(customer_id) FROM vouchers WHERE customer_id = ? and status >= ?) < 1
				LIMIT 1";
        
        $result = DB::select($sqlCheckVoucher, [
            config('constants.VOUCHER.STATUS_AVAILABLE'), 
            config('constants.VOUCHER.STATUS_LOCKED'), 
            config('constants.ELIGIBLE.LOCK_TIME'),
            $customer_id,
            config('constants.VOUCHER.STATUS_OWNED'),
        ]);

        if (!empty($result)) {
            return $result[0];
        } else {
            return null;
        }
    }
}
