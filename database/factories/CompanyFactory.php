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
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->companyEmail,
            'logo' => $this->faker->imageUrl(100, 100, 'business', true, 'logo'),
            'website' => $this->faker->unique()->url,
            'created_by' => User::factory(), // assumes you have a UserFactory
        ];
    }
}
