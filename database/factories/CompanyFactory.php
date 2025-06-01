<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $name = $this->faker->company;
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '', str_replace(' ', '', $name)));
        return [
            'name' => $name,
            'email' => 'info@' . $slug . '.com',
            'logo' => "https://picsum.photos/seed/" . $this->faker->uuid . "/100/100",
            'website' => "https://" . $slug . ".com",
            'created_by' => User::factory(), // assumes you have a UserFactory
        ];
    }
}
