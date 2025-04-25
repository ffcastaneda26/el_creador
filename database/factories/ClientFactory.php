<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Country;
use App\Models\State;
use App\Models\Municipality;
use App\Models\City;
use App\Models\Zipcode;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'mother_surname' => $this->faker->lastName(),
            'company_name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => substr($this->faker->phoneNumber(), 0, 15),
            'mobile' => substr($this->faker->phoneNumber(), 0, 15),
            'curp' => strtoupper($this->faker->bothify('????######??????##')),
            'ine' => $this->faker->numerify('############'),
            'rfc' => strtoupper($this->faker->bothify('????######???')),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->buildingNumber(),
            'interior_number' => $this->faker->optional()->randomNumber(3),
            'colony' => $this->faker->word(),
            'zipcode' => 31300,
            'type' => $this->faker->randomElement(['Física', 'Moral']),
            'tax_type' => $this->faker->randomElement(['Iva', 'Retención']),
            'country_id' => 135,
            'state_id' => 8,
            'municipality_id' => 217,
            'city_id' =>250,
            'notes' => $this->faker->optional()->sentence(),
            'references' => $this->faker->optional()->sentence(),
        ];
    }
}
