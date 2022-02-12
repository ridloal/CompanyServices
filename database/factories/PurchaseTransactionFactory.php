<?php

namespace Database\Factories;

use App\Models\PurchaseTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseTransactionFactory extends Factory
{
    protected $model = PurchaseTransaction::class;

    public function definition(): array
    {
    	return [
    	    'customer_id' => $this->faker->numberBetween($min = 1, $max = 25),
            'total_spent' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100),
            'total_saving' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 30),
            'transaction_at' => $this->faker->dateTimeBetween($startDate = '-40 days', $endDate = 'now', $timezone = null),
    	];
    }
}
