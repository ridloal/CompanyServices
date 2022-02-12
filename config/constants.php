<?php 
//describe constants to use globally
return [
    'VOUCHER' => [
        'STATUS_AVAILABLE' => '0', // Voucher fresh available
        'STATUS_LOCKED' => '1', // Voucher locked by someone
        'STATUS_OWNED' => '2', // Voucher owned by customer
        'STATUS_REDEEMED' => '3', // Voucher is used by customer
        'STATUS_EXPIRED' => '4', // Voucher not used and going to be expired
    ],
    'ELIGIBLE' => [
        'MAX_LAST_TRANSACTION' => '30', // Maximum last transaction calculated to be eligible (days)
        'MIN_TOTAL_SPENT' => '100', // Minimum total spent from transaction to be eligible (amount)
        'MIN_TRANSACTION' => '3', // Minimum total transaction 
        'LOCK_TIME' => '10', // Minutes to limit voucher locked (minutes)
    ],
    'MESSAGES' => [
        'CUSTOMER_NO_TRANSACTION' => 'Customer have not made any transaction in last %d days.',
        'CUSTOMER_NEED_PROVIDE_PHOTO' => 'Please provide photo selfie. %d minute remaining.',
        'CUSTOMER_ONLY_ONE_VOUCHER' => 'Each customer is allowed to redeem 1 cash voucher only.',
        'CUSTOMER_IS_ELIGIBLE' => 'The customer eligible to get the voucher. Please provide required photo selfie within 10 minutes to get the voucher',
        'CUSTOMER_NOT_ELIGIBLE' => 'The customer are not eligible to get the voucher.',
        'CUSTOMER_NOT_YET_CLAIM' => 'Try claim the voucher first, and provide photo within %d minutes',
        'VOUCHER_OWNED' => 'Congratulations, you get a cash voucher !',
        'VOUCHER_NOT_AVAILABLE' => 'The voucher is not available right now, please try again later.',
        'IMAGE_NOT_VALID' => 'Image attached not valid. Please try again !'
    ]
];