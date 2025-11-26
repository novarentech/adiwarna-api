<?php

namespace Database\Factories;

use App\Models\Quotation;
use App\Models\QuotationAdiwarna;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuotationAdiwarna>
 */
class QuotationAdiwarnaFactory extends Factory
{
    protected $model = QuotationAdiwarna::class;

    public function definition(): array
    {
        return [
            'quotation_id' => Quotation::factory(),
            'adiwarna_description' => fake()->sentence(10), // Max 255 chars for string column
        ];
    }
}
