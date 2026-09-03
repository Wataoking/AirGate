<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_client_list_with_live_data(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $client = User::factory()->create(['name' => 'Bébé Mpa', 'role' => 'user']);
        $plan = Plan::create([
            'name' => 'ACCES',
            'duration' => '1H',
            'price' => 500,
            'speed' => 5,
            'active' => true,
        ]);

        Transaction::create([
            'user_id' => $client->id,
            'plan_id' => $plan->id,
            'amount' => 500,
            'status' => 'paid',
            'payment_method' => 'Mobile Money',
            'reference' => 'TXN-TEST-001',
        ]);

        $response = $this->actingAs($admin)->get(route('super-admin.client'));

        $response->assertOk();
        $response->assertSee('Gestion des clients');
        $response->assertSee('Bébé Mpa');
        $response->assertSee('ACCES');
    }
}
