<?php

namespace Tests\Feature;

use App\Models\Conversion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ConvertCurrencyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function converts_currency_and_stores_the_result()
    {
        // Fake Fixer одговор
        Http::fake([
            'https://data.fixer.io/api/latest*' => Http::response([
                'success' => true,
                'timestamp' => 1700000000,
                'base' => 'EUR',
                'date' => '2025-01-01',
                'rates' => [
                    'EUR' => 1.0,
                    'USD' => 1.1,
                    'MKD' => 61.5,
                ],
            ], 200),
        ]);

        $payload = ['from' => 'EUR', 'to' => 'USD', 'amount' => 100];

        $response = $this->postJson('/api/convert', $payload);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => ['id', 'from', 'to', 'amount', 'rate', 'result', 'created']
            ])
            ->assertJsonPath('data.from', 'EUR')
            ->assertJsonPath('data.to', 'USD')
            ->assertJsonPath('data.amount', 100);

        // проверка база
        $this->assertDatabaseCount('conversions', 1);

        $c = Conversion::first();
        $this->assertSame('EUR', $c->from_currency);
        $this->assertSame('USD', $c->to_currency);
        $this->assertSame(100.00, (float)$c->amount);
        $this->assertSame(110.00, (float)$c->result); // 100 * 1.1
    }

    /** @test */
    public function validates_request_payload()
    {
        $response = $this->postJson('/api/convert', [
            'from' => 'EU',     // погрешна должина
            'to' => '',         // празно
            'amount' => -5      // негативно
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['from', 'to', 'amount']);
    }
}
