<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'user')->first();
        $plan = Plan::where('active', true)->first();

        if (! $user || ! $plan) {
            return;
        }

        Transaction::updateOrCreate(
            ['reference' => 'TXN-DEMO-001'],
            ['user_id' => $user->id, 'plan_id' => $plan->id, 'amount' => $plan->price, 'status' => 'paid', 'payment_method' => 'Mobile Money'],
        );
    }
}