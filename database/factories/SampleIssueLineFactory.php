<?php

namespace Database\Factories;

use App\Models\SampleRequestLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SampleIssueLine>
 */
class SampleIssueLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sample_request_line_id' => SampleRequestLine::factory(),
            'sample_issue_id' => function (array $attributes): int {
                $requestLine = SampleRequestLine::query()->findOrFail($attributes['sample_request_line_id']);

                return \App\Models\SampleIssue::factory()->create([
                    'sample_request_id' => $requestLine->sample_request_id,
                ])->getKey();
            },
            'item_master_id' => fn (array $attributes): int => (int) SampleRequestLine::query()
                ->findOrFail($attributes['sample_request_line_id'])
                ->item_master_id,
            'quantity' => 1,
            'unit_cost' => null,
            'remarks' => fake()->optional()->sentence(),
        ];
    }
}
