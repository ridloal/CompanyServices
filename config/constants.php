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
        'LOCK_TIME' => '10', // Minute to limit voucher locked (minutes)
    ]
];