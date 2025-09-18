<?php

namespace Tests\Feature;

use App\Models\Conversion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListConversionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function lists_recent_conversions()
    {
        Conversion::factory()->create([
            'from_currency' => 'EUR',
            'to_currency'   => 'USD',
            'amount'        => 100,
            'rate'          => 1.1,
            'result'        => 110.00,
        ]);

        $res = $this->getJson('/api/conversions?limit=10');

        $res->assertOk()
            ->assertJsonStructure(['data' => [['id', 'from', 'to', 'amount', 'rate', 'result', 'created']]])
            ->assertJsonPath('data.0.from', 'EUR');
    }
}
