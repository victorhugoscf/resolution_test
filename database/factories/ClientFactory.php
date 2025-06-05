<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
	protected $model = \App\Models\Client::class;

	public function definition()
	{
		return [
			'name' => $this->faker->name, 
			'email' => $this->faker->safeEmail,
			'document' => $this->faker->numerify('###.###.###-##'),
		];
	}
}