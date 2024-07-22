<?php

namespace Tests;

use App\Models\Client;
use App\Models\Employee;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory as Faker;

abstract class TestCase extends BaseTestCase
{
    protected $faker;
    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
    }

    public function generateDummyDataForEmployee(): array
    {
        return [
            'first_name' => $this->faker->firstname,
            'last_name' => $this->faker->lastname,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phonenumber,
            'birth_day' => $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'address' => $this->faker->address,
            'role_id' => rand(2, 7),
        ];
    }

    public function generateDummyDataForProject(): array
    {
        $client = Client::factory()->create();
        $employees = Employee::factory()->count(rand(2, 5))->create();

        return [
            'name' => $this->faker->sentence,
            'client_id' => $client->id,
            'employee_ids' => $employees->pluck('id')
        ];
    }
}
