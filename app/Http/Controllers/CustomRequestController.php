<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\PurchaseTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;

class CustomRequestController extends Controller
{
    public function index($customerId)
    {

		$customSql = "SELECT customer_id, SUM(total_spent) as grand_total, count(customer_id) as total_transaction
			FROM purchase_transactions
			WHERE customer_id = ? AND transaction_at >= (CURDATE() - INTERVAL ? DAY)
			GROUP BY customer_id";
		
		$customSql = "SELECT * FROM vouchers
			WHERE status = ? OR (status = ? AND TIMESTAMPDIFF(MINUTE, created_at, updated_at) >= ?)
			LIMIT 1";

		// $tes = DB::select($customSql, [$customerId, config('constants.ELIGIBLE.MAX_LAST_TRANSACTION')]);
		$tes = DB::select($customSql, [config('constants.VOUCHER.STATUS_AVAILABLE'), config('constants.VOUCHER.STATUS_LOCKED'), config('constants.ELIGIBLE.LOCK_TIME')]);
		// $tes = Voucher::where('status', config('constants.VOUCHER.STATUS_AVAILABLE'))->first();
		// $tes->status = 1;
		// $tes->save();
		return $tes;
    }

    public function checkEligible(Request $request)
    {
		$data = ['eligible' => false];

		try {
			
			// Get data transaction for customer
			$checkResult = PurchaseTransaction::checkCustomer($request->customer_id);
			if (empty($checkResult)) {
				$message = "Customer have not made any transaction in last ".config('constants.ELIGIBLE.MAX_LAST_TRANSACTION')." days.";
				return $this->responseTemplate($data, $message, Response::HTTP_OK);
			}

			// Check if the customer is eligible
			if ($checkResult->grand_total >= config('constants.ELIGIBLE.MIN_TOTAL_SPENT') 
				&& $checkResult->total_transaction >= config('constants.ELIGIBLE.MIN_TRANSACTION')) {
				
				// Check is customer have a voucher (in lock or owned)
				$checkCustomerVoucher = PurchaseTransaction::checkCustomerVoucher($request->customer_id);
				if(!empty($checkCustomerVoucher)) {

					if ($checkCustomerVoucher->time_running < config('constants.ELIGIBLE.LOCK_TIME')) {
						$timeRemaining = config('constants.ELIGIBLE.LOCK_TIME') - $checkCustomerVoucher->time_running;
						$message = "Please provide photo selfie. ".$timeRemaining." minute remaining.";
					}else {
						$message = "Each customer is allowed to redeem 1 cash voucher only.";
					}

					return $this->responseTemplate($data, $message, Response::HTTP_OK);
				}

				// Check voucher availability
				$checkVoucher = PurchaseTransaction::checkVoucher($request->customer_id);
				if(!empty($checkVoucher)) {
					Voucher::where('id', $checkVoucher->id)->update([
						'customer_id' => $request->customer_id,
						'status' => config('constants.VOUCHER.STATUS_LOCKED'),
						]
					);
					$data = ['eligible' => true];
					$message = "The customer eligible to get the voucher. Please provide required photo selfie within 10 minutes to get the voucher";
				} else {
					$message = "The voucher is not available right now, please try again later.";
				}
			} else {
				$message = "The customer are not eligible to get the voucher.";
			}

			return $this->responseTemplate($data, $message, Response::HTTP_OK);

    	} catch (Exception $e) {

			return $this->responseTemplate($data, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
    	}
    }
}
