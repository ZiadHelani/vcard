<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Premium',
                'description' => 'Premium description',
                'features' => [
                    'feature1',
                    'feature2',
                ],
                'price' => 10.99,
            ],
            [
                'name' => 'Standard',
                'description' => 'Standard description',
                'features' => [
                    'feature1',
                    'feature2',
                ],
                'price' => 9.99,
            ],
            [
                'name' => 'Basic',
                'description' => 'Basic description',
                'features' => [
                    'feature1',
                    'feature2',
                ],
                'price' => 8.99,
            ],
        ];
        foreach ($plans as $plan) {
            Plan::query()->create($plan);
        }
    }
}
