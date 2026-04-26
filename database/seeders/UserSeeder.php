<?php

namespace Database\Seeders;

use App\Enums\UserSubscriptionStatus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->create([
            'name' => 'Ziad Helani',
            'email' => 'admin@admin.com',
            'password' => '123456789',
        ]);
        $user->subscriptions()->create([
            'plan_id' => 3,
            'price' => 30,
            'start_at' => now()->subDay(),
            'end_at' => now()->addMonth(),
            'status' => UserSubscriptionStatus::ACTIVE,
        ]);
    }
}
