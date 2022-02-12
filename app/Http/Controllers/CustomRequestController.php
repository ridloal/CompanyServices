<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\PurchaseTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;

class CustomRequestController extends Controller
{
	public function checkEligible(Request $request)
    {
		$data = ['eligible' => false];

		try {
			
			// Get data transaction for customer
			$checkResult = PurchaseTransaction::checkCustomer($request->customer_id);
			if (empty($checkResult)) {
				$message = sprintf(config('constants.MESSAGES.CUSTOMER_NO_TRANSACTION'), config('constants.ELIGIBLE.MAX_LAST_TRANSACTION'));
				return $this->responseTemplate($data, $message, Response::HTTP_OK);
			}

			// Check if the customer is eligible
			if ($checkResult->grand_total >= config('constants.ELIGIBLE.MIN_TOTAL_SPENT') 
				&& $checkResult->total_transaction >= config('constants.ELIGIBLE.MIN_TRANSACTION')) {
				
				// Check is customer have a voucher (in lock or owned)
				$checkCustomerVoucher = Voucher::checkCustomerVoucher($request->customer_id);
				if(!empty($checkCustomerVoucher)) {

					if ($checkCustomerVoucher->time_running < config('constants.ELIGIBLE.LOCK_TIME')
						&& $checkCustomerVoucher->status == config('constants.VOUCHER.STATUS_LOCKED')) {

						$timeRemaining = config('constants.ELIGIBLE.LOCK_TIME') - $checkCustomerVoucher->time_running;
						$message = sprintf(config('constants.MESSAGES.CUSTOMER_NEED_PROVIDE_PHOTO'), $timeRemaining);

					}else {
						$message = config('constants.MESSAGES.CUSTOMER_ONLY_ONE_VOUCHER');
					}

					return $this->responseTemplate($data, $message, Response::HTTP_OK);
				}

				// Check voucher availability
				$checkVoucher = Voucher::checkVoucher($request->customer_id);
				if(!empty($checkVoucher)) {
					Voucher::where('id', $checkVoucher->id)->update([
						'customer_id' => $request->customer_id,
						'status' => config('constants.VOUCHER.STATUS_LOCKED'),
						]
					);
					$data = ['eligible' => true];
					$message = config('constants.MESSAGES.CUSTOMER_IS_ELIGIBLE');
				} else {
					$message = config('constants.MESSAGES.VOUCHER_NOT_AVAILABLE');
				}
			} else {
				$message = config('constants.MESSAGES.CUSTOMER_NOT_ELIGIBLE');
			}

			return $this->responseTemplate($data, $message, Response::HTTP_OK);

    	} catch (Exception $e) {

			return $this->responseTemplate($data, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
    	}
    }

	public function validatePhoto(Request $request)
    {
		$data = '';

		// Check if image attached is valid
		$checkImage = $this->imageRecognitionAPI($request->image_url);
		if ($checkImage) {
			// Check is the time still meet the requirement
			$checkCustomerVoucher = Voucher::checkCustomerVoucher($request->customer_id);
			if(!empty($checkCustomerVoucher)) {

				if ($checkCustomerVoucher->time_running < config('constants.ELIGIBLE.LOCK_TIME') 
					&& $checkCustomerVoucher->status == config('constants.VOUCHER.STATUS_LOCKED')) {
					$voucher = Voucher::find($checkCustomerVoucher->id);
					$voucher->status = config('constants.VOUCHER.STATUS_OWNED');
					$voucher->save();
					
					$data = $voucher;
					$message = config('constants.MESSAGES.VOUCHER_OWNED');
				} else {
					$message = config('constants.MESSAGES.CUSTOMER_ONLY_ONE_VOUCHER');
				}

			} else {
				$message = sprintf(config('constants.MESSAGES.CUSTOMER_NOT_YET_CLAIM'), config('constants.ELIGIBLE.LOCK_TIME'));
			}
		} else {
			$message = config('constants.MESSAGES.IMAGE_NOT_VALID');
		}

		return $this->responseTemplate($data, $message, Response::HTTP_OK);
	}

	public function imageRecognitionAPI($imageUrl)
    {
		// The magic will be here

		// If valid return true
		if (!empty($imageUrl)) {
			return true;
		} else {
			return false;
		}
	}
}
