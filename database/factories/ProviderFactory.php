<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\Municipality;
use App\Models\State;
use App\Models\Zipcode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Provider>
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $country = Country::where('include',1)->inRandomOrder()->first(); // País
        $state = $country->states()->inRandomOrder()->first();                              // Estado aleatorio
        $municipality = $state->municipalities()->inRandomOrder()->first();                 // Municipio aleatorio
        $city = $municipality->cities()->inRandomOrder()->first();                          // Ciudad
        $zipcode =Zipcode::where('country_id',$country->id)
                        ->where('state_id',$state->id)
                        ->where('municipality_id',$municipality->id)
                        ->where('city_id',$city->id)
                        ->inRandomOrder()->first();
                                  // Código Postal de pais,estado,municipio y ciudad aleatorio
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->regexify('^[0-9]{10,15}$'),
            'rfc' => $this->faker->regexify('[A-Z]{3}[0-9]{6}[A-Z0-9]{3}'), // RFC genérico
            'type' => $this->faker->randomElement(['Física', 'Moral']),
            'address' => $this->faker->streetAddress(),
            'colony' => $zipcode->name ?? null, // Puedes usar city() o una lista de colonias
            'references' => $this->faker->text(200), // Texto para referencias
            'zipcode' => $zipcode->zipcode ?? null, // Código Postal
            'country_id' => $country->id, //
            'state_id' => $state->id, // Estado aleatorio o null
            'municipality_id' => $municipality->id ?? null, // Municipio aleatorio o null
            'city_id' => $city->id ?? null, // Ciudad aleatoria o null
            'notes' => $this->faker->text(200), // Notas aleatorias
            'active' => $this->faker->boolean() ? true : false,
        ];
    }
}
