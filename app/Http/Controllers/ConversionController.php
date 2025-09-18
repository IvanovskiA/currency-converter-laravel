<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvertCurrencyRequest;
use App\Models\Conversion;
use App\Services\FixerClient;
use Illuminate\Http\JsonResponse;

class ConversionController extends Controller
{
    public function __construct(private FixerClient $fixer) {}

    public function convert(ConvertCurrencyRequest $request): JsonResponse
    {
        $from   = $request->input('from');
        $to     = $request->input('to');
        $amount = (float)$request->input('amount');

        $rate   = $this->fixer->rateFromTo($from, $to);
        $result = round($amount * $rate, 2);

        $record = Conversion::create([
            'from_currency' => $from,
            'to_currency'   => $to,
            'amount'        => $amount,
            'rate'          => $rate,
            'result'        => $result,
        ]);

        return response()->json([
            'data' => [
                'id'      => $record->id,
                'from'    => $from,
                'to'      => $to,
                'amount'  => $amount,
                'rate'    => round($rate, 6),
                'result'  => $result,
                'created' => $record->created_at,
            ]
        ], 200);
    }
}
