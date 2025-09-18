<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConversionFactory extends Factory
{
    public function definition(): array
    {
        $from = 'EUR';
        $to   = 'USD';
        $amount = 100.00;
        $rate   = 1.10;
        $result = round($amount * $rate, 2);

        return [
            'from_currency' => $from,
            'to_currency'   => $to,
            'amount'        => $amount,
            'rate'          => $rate,
            'result'        => $result
        ];
    }
}
