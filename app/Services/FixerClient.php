<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class FixerClient
{
    public function __construct(
        private string $baseUrl = '',
        private string $apiKey = '',
        private string $baseCurrency = 'EUR',
    ) {
        $this->baseUrl = config('services.fixer.base_url', env('FIXER_BASE_URL'));
        $this->apiKey = config('services.fixer.key', env('FIXER_API_KEY'));
        $this->baseCurrency = config('services.fixer.base_currency', env('FIXER_BASE_CURRENCY', 'EUR'));
    }

    /**
     * Враќа курс за пара (пример EUR→USD).
     */
    public function rate(string $toCurrency): float
    {
        $response = Http::get("{$this->baseUrl}/latest", [
            'access_key' => $this->apiKey,
            'base'       => $this->baseCurrency,
            'symbols'    => $toCurrency,
        ]);

        if (!$response->ok()) {
            throw new RuntimeException('Fixer request failed.');
        }

        $data = $response->json();

        if (!($data['success'] ?? false)) {
            throw new RuntimeException($data['error']['type'] ?? 'Fixer error');
        }

        $rate = $data['rates'][$toCurrency] ?? null;

        if (!$rate) {
            throw new RuntimeException('Rate not found.');
        }

        return (float)$rate;
    }

    /**
     * Враќа курс од било која валута „from“ до „to“,
     * преку базната валута (стандардно EUR).
     */
    public function rateFromTo(string $from, string $to): float
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) return 1.0;

        // земи сите потребни симболи одеднаш
        $symbols = implode(',', array_unique([$from, $to]));

        $response = Http::get("{$this->baseUrl}/latest", [
            'access_key' => $this->apiKey,
            'base'       => $this->baseCurrency,
            'symbols'    => $symbols,
        ]);

        if (!$response->ok()) {
            throw new RuntimeException('Fixer request failed.');
        }

        $data = $response->json();
        if (!($data['success'] ?? false)) {
            throw new RuntimeException($data['error']['type'] ?? 'Fixer error');
        }

        $rates = $data['rates'] ?? [];
        $rFrom = $rates[$from] ?? null;
        $rTo   = $rates[$to]   ?? null;

        if (!$rFrom || !$rTo) {
            throw new RuntimeException('Rate not available.');
        }

        // конверзија преку baseCurrency
        return $rTo / $rFrom;
    }
}
