<?php

namespace Database\Factories;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition(): array
    {
    	return [
    	    'name' => "Voucher Anniversary Celebration",
            'voucher_code' => $this->faker->unique()->bothify('##??-#?#?-?#?#'),
            'voucher_worth' => 50,
            'expired' => '2022-12-31',
            'status' => 0
    	];
    }
}
