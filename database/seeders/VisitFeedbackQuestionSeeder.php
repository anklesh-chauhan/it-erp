<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitFeedbackQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            ['question' => 'Did the customer give proper attention?', 'code' => 'ATTENTION'],
            ['question' => 'Overall customer interest in products', 'code' => 'INTEREST'],
            ['question' => 'Response to product presentation', 'code' => 'PRESENTATION_RESPONSE'],
            ['question' => 'Likelihood of future business', 'code' => 'PURCHASE_INTENT'],
        ];

        foreach ($questions as $question) {
            DB::table('visit_feedback_questions')->updateOrInsert(
                ['code' => $question['code']], // prevent duplicates
                array_merge($question, [
                    'answer_type' => 'rating_1_5',
                    'is_active' => true,
                    'sort_order' => 0,
                ])
            );
        }
    }
}
