<?php

namespace Database\Seeders;

use App\Models\Quote;
use Illuminate\Database\Seeder;

class QuotesSeeder extends Seeder
{
    public function run(): void
    {
        $quotes = [
            ['quote_text' => 'Renewables are now a competitiveness strategy, not just a climate choice.', 'author_name' => 'Energy Analyst', 'author_designation' => 'Policy Researcher'],
            ['quote_text' => 'Energy security improves when you diversify sources and reduce import exposure.', 'author_name' => 'Market Expert', 'author_designation' => 'Energy Economist'],
            ['quote_text' => 'Grid modernization is the foundation for integrating more clean power.', 'author_name' => 'Grid Specialist', 'author_designation' => 'Power Systems Engineer'],
            ['quote_text' => 'Long-term planning reduces volatility during global shocks.', 'author_name' => 'Industry Leader', 'author_designation' => 'Strategic Advisor'],
            ['quote_text' => 'Good data makes policy faster and more transparent.', 'author_name' => 'Data Lead', 'author_designation' => 'Energy Program Manager'],
            ['quote_text' => 'Efficiency is the cheapest unit of energy you never had to import.', 'author_name' => 'Efficiency Advisor', 'author_designation' => 'Demand-Side Specialist'],
            ['quote_text' => 'Resilience comes from planning for extremes, not averages.', 'author_name' => 'Risk Advisor', 'author_designation' => 'Resilience Consultant'],
            ['quote_text' => 'Clean power grows when financing matches real project timelines.', 'author_name' => 'Finance Expert', 'author_designation' => 'Project Finance Analyst'],
            ['quote_text' => 'A modern grid enables both reliability and clean integration.', 'author_name' => 'Utility Expert', 'author_designation' => 'Distribution Planner'],
            ['quote_text' => 'Clear rules and predictable tenders unlock private investment.', 'author_name' => 'Regulatory Specialist', 'author_designation' => 'Energy Lawyer'],
        ];

        foreach ($quotes as $q) {
            Quote::query()->updateOrCreate(
                ['quote_text' => $q['quote_text'], 'author_name' => $q['author_name']],
                [
                    ...$q,
                    'author_image' => null,
                    'is_published' => true,
                ]
            );
        }

        Quote::query()->updateOrCreate(
            ['quote_text' => 'This is a draft quote and should not show publicly.', 'author_name' => 'Draft Author'],
            [
                'author_designation' => null,
                'author_image' => null,
                'is_published' => false,
            ]
        );
    }
}
