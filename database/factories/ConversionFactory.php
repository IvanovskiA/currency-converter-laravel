<?php

namespace Database\Factories;

use App\Models\Conversion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversionFactory extends Factory
{
    protected $model = Conversion::class;

    public function definition(): array
    {
        $from = $this->faker->currencyCode();
        $to   = $this->faker->currencyCode();
        $amount = $this->faker->randomFloat(2, 1, 1000);
        $rate   = $this->faker->randomFloat(6, 0.5, 2.0);
        $result = round($amount * $rate, 2);

        return [
            'from_currency' => $from,
            'to_currency'   => $to,
            'amount'        => $amount,
            'rate'          => $rate,
            'result'        => $result,
        ];
    }
}
