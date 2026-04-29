<?php

namespace Database\Seeders;

use App\Models\Icon;
use App\Models\TimelineEvent;
use Illuminate\Database\Seeder;

class TimelineEventsSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'icon' => 'Market',
                'event_date' => '2026-02-01',
                'global_event_title' => 'Global energy markets adjust to new supply outlook',
                'global_event_excerpt' => 'Market participants react to updated production and demand forecasts across regions.',
                'global_event_link' => 'https://example.com/global-event-1',
                'impact_title' => 'Bangladesh reviews import cost projections',
                'impact_excerpt' => 'Policy teams reassess procurement plans as benchmark prices shift.',
                'impact_link' => 'https://example.com/bd-impact-1',
                'sort_order' => 10,
                'is_published' => true,
            ],
            [
                'icon' => 'Market',
                'event_date' => '2026-03-18',
                'global_event_title' => 'Regional LNG spot prices soften',
                'global_event_excerpt' => 'Short-term contracts reflect easing conditions in shipping and storage.',
                'global_event_link' => 'https://example.com/global-event-2',
                'impact_title' => 'Bangladesh considers opportunistic cargo purchases',
                'impact_excerpt' => 'Utilities evaluate short-term buys to stabilize generation costs.',
                'impact_link' => 'https://example.com/bd-impact-2',
                'sort_order' => 20,
                'is_published' => true,
            ],
            [
                'icon' => 'Renewables',
                'event_date' => '2026-04-05',
                'global_event_title' => 'New renewable procurement targets announced',
                'global_event_excerpt' => 'Governments and developers outline pipeline capacity and tender timelines.',
                'global_event_link' => 'https://example.com/global-event-3',
                'impact_title' => 'Bangladesh accelerates solar project pipeline',
                'impact_excerpt' => 'Agencies align land, grid, and financing to speed deployment.',
                'impact_link' => 'https://example.com/bd-impact-3',
                'sort_order' => 30,
                'is_published' => true,
            ],
            [
                'icon' => 'Grid',
                'event_date' => '2026-04-22',
                'global_event_title' => 'Volatility returns after a shipping disruption',
                'global_event_excerpt' => 'Logistics constraints increase short-term uncertainty for fuel deliveries.',
                'global_event_link' => 'https://example.com/global-event-4',
                'impact_title' => 'Bangladesh updates contingency dispatch plans',
                'impact_excerpt' => 'Operators optimize dispatch and demand management to reduce risk.',
                'impact_link' => 'https://example.com/bd-impact-4',
                'sort_order' => 40,
                'is_published' => true,
            ],
            [
                'icon' => 'Finance',
                'event_date' => '2026-05-12',
                'global_event_title' => 'Power sector investment discussions accelerate across Asia',
                'global_event_excerpt' => 'Developers and financiers explore updated risk frameworks and timelines.',
                'global_event_link' => 'https://example.com/global-event-5',
                'impact_title' => 'Bangladesh reviews project pipeline prioritization',
                'impact_excerpt' => 'Planners assess readiness of grid and land to reduce delays.',
                'impact_link' => 'https://example.com/bd-impact-5',
                'sort_order' => 50,
                'is_published' => true,
            ],
            [
                'icon' => 'Policy',
                'event_date' => '2026-06-03',
                'global_event_title' => 'Fuel price signals stabilize after policy guidance',
                'global_event_excerpt' => 'Markets respond to clearer medium-term policy direction in major economies.',
                'global_event_link' => 'https://example.com/global-event-6',
                'impact_title' => 'Bangladesh updates budget assumptions for imports',
                'impact_excerpt' => 'Revised assumptions support procurement planning and tariff forecasting.',
                'impact_link' => 'https://example.com/bd-impact-6',
                'sort_order' => 60,
                'is_published' => true,
            ],
            [
                'icon' => 'Grid',
                'event_date' => '2026-06-24',
                'global_event_title' => 'New grid standards published for renewable integration',
                'global_event_excerpt' => 'Updated technical requirements focus on stability, forecasting, and dispatch.',
                'global_event_link' => 'https://example.com/global-event-7',
                'impact_title' => 'Bangladesh prepares interconnection guidance for developers',
                'impact_excerpt' => 'Clearer interconnection steps reduce uncertainty and speed up commissioning.',
                'impact_link' => 'https://example.com/bd-impact-7',
                'sort_order' => 70,
                'is_published' => true,
            ],
            [
                'icon' => 'Policy',
                'event_date' => '2026-07-15',
                'global_event_title' => 'Renewables auction schedules released for the next cycle',
                'global_event_excerpt' => 'Tender calendars signal expected volumes and timeline milestones.',
                'global_event_link' => 'https://example.com/global-event-8',
                'impact_title' => 'Bangladesh aligns tender process with grid availability',
                'impact_excerpt' => 'Coordination aims to match project locations with evacuation capacity.',
                'impact_link' => 'https://example.com/bd-impact-8',
                'sort_order' => 80,
                'is_published' => true,
            ],
            [
                'icon' => 'Market',
                'event_date' => '2026-08-09',
                'global_event_title' => 'Industrial demand forecasts revised as trade shifts',
                'global_event_excerpt' => 'Revised outlook affects power demand expectations and capacity planning.',
                'global_event_link' => 'https://example.com/global-event-9',
                'impact_title' => 'Bangladesh updates load planning scenarios',
                'impact_excerpt' => 'Scenario planning informs generation mix and grid reinforcement decisions.',
                'impact_link' => 'https://example.com/bd-impact-9',
                'sort_order' => 90,
                'is_published' => true,
            ],
            [
                'icon' => 'Finance',
                'event_date' => '2026-09-01',
                'global_event_title' => 'Climate finance instruments gain momentum for emerging markets',
                'global_event_excerpt' => 'More blended finance options emerge for infrastructure and energy projects.',
                'global_event_link' => 'https://example.com/global-event-10',
                'impact_title' => 'Bangladesh explores blended finance for grid upgrades',
                'impact_excerpt' => 'New financing structures could accelerate resilience and modernization.',
                'impact_link' => 'https://example.com/bd-impact-10',
                'sort_order' => 100,
                'is_published' => true,
            ],
        ];

        foreach ($events as $e) {
            $iconName = $e['icon'];
            unset($e['icon']);

            $iconId = Icon::query()->where('name', $iconName)->value('id');

            TimelineEvent::query()->updateOrCreate(
                ['event_date' => $e['event_date'], 'global_event_title' => $e['global_event_title']],
                array_merge($e, ['icon_id' => $iconId])
            );
        }

        TimelineEvent::query()->updateOrCreate(
            ['event_date' => '2026-04-10', 'global_event_title' => 'Draft event (hidden)'],
            [
                'icon_id' => Icon::query()->where('name', 'Policy')->value('id'),
                'global_event_excerpt' => null,
                'global_event_link' => null,
                'impact_title' => 'Draft impact (hidden)',
                'impact_excerpt' => null,
                'impact_link' => null,
                'sort_order' => 999,
                'is_published' => false,
            ]
        );
    }
}
