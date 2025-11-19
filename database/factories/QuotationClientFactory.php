<?php

namespace Database\Factories;

use App\Models\Quotation;
use App\Models\QuotationClient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuotationClient>
 */
class QuotationClientFactory extends Factory
{
    protected $model = QuotationClient::class;

    public function definition(): array
    {
        return [
            'quotation_id' => Quotation::factory(),
            'client_description' => fake()->sentence(10), // Max 255 chars for string column
        ];
    }
}
