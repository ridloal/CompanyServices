<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
    	return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement($array = array ('Male','Female')),
            'date_of_birth' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'contact_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->email
    	];
    }
}
