<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	use HasFactory;

    public function purchase_transactions()
    {
        return $this->hasMany(PurchaseTransaction::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }
}
