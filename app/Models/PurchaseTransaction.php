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
}
