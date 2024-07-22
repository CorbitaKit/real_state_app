<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;
    public function test_if_can_get_all_property_datas()
    {
        $properties = Property::factory(10)->create();

        $response = $this->postJson(route('properties.index'))
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'province',
                    'city',
                    'barangay',
                    'phase',
                    'purok'
                ]
            ]
        ]);
    }
}
