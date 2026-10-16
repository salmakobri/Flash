<?php

namespace Tests\Feature;

use App\Models\Chauffeur;
use App\Models\Client;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DriverDeliveryTest extends TestCase
{
    use RefreshDatabase;

    private function makeDriver(string $email): Chauffeur
    {
        $user = User::create([
            'name' => 'Driver '.$email,
            'email' => $email,
            'password' => 'password',
            'role' => 'chauffeur',
        ]);

        return Chauffeur::create(['user_id' => $user->id, 'nom' => $user->name]);
    }

    private function makeOrder(Chauffeur $driver, string $statut = 'affectee'): Commande
    {
        $client = Client::create(['nom' => 'Client Test', 'telephone' => '0600000000']);

        return Commande::create([
            'client_id' => $client->id,
            'chauffeur_id' => $driver->id,
            'date' => today(),
            'adresse_livraison' => '12 Rue Test',
            'statut' => $statut,
        ]);
    }

    public function test_driver_can_open_own_delivery(): void
    {
        $driver = $this->makeDriver('a@test.com');
        $order = $this->makeOrder($driver);

        Sanctum::actingAs($driver->user);

        $this->getJson("/api/livraisons/{$order->id}")->assertOk();
    }

    public function test_driver_cannot_open_another_drivers_delivery(): void
    {
        $driverA = $this->makeDriver('a@test.com');
        $driverB = $this->makeDriver('b@test.com');
        $orderOfB = $this->makeOrder($driverB);

        Sanctum::actingAs($driverA->user);

        $this->getJson("/api/livraisons/{$orderOfB->id}")->assertForbidden();
    }
        public function test_driver_can_start_then_complete_a_delivery(): void
    {
        $driver = $this->makeDriver('a@test.com');
        $order = $this->makeOrder($driver, 'affectee');

        Sanctum::actingAs($driver->user);

        $this->patchJson("/api/livraisons/{$order->id}/statut", ['statut' => 'en_cours'])
            ->assertOk()
            ->assertJsonPath('data.statut', 'en_cours');

        $this->patchJson("/api/livraisons/{$order->id}/statut", ['statut' => 'livree'])
            ->assertOk()
            ->assertJsonPath('data.statut', 'livree');
    }

    public function test_cannot_skip_from_affectee_to_livree(): void
    {
        $driver = $this->makeDriver('a@test.com');
        $order = $this->makeOrder($driver, 'affectee');

        Sanctum::actingAs($driver->user);

        $this->patchJson("/api/livraisons/{$order->id}/statut", ['statut' => 'livree'])
            ->assertStatus(422);

        $this->assertDatabaseHas('commandes', ['id' => $order->id, 'statut' => 'affectee']);
    }

    public function test_cannot_go_back_once_delivered(): void
    {
        $driver = $this->makeDriver('a@test.com');
        $order = $this->makeOrder($driver, 'livree');

        Sanctum::actingAs($driver->user);

        $this->patchJson("/api/livraisons/{$order->id}/statut", ['statut' => 'en_cours'])
            ->assertStatus(422);
    }

    public function test_unknown_status_is_rejected(): void
    {
        $driver = $this->makeDriver('a@test.com');
        $order = $this->makeOrder($driver, 'affectee');

        Sanctum::actingAs($driver->user);

        $this->patchJson("/api/livraisons/{$order->id}/statut", ['statut' => 'annulee'])
            ->assertStatus(422);
    }

    public function test_driver_cannot_change_status_of_another_drivers_delivery(): void
    {
        $driverA = $this->makeDriver('a@test.com');
        $driverB = $this->makeDriver('b@test.com');
        $orderOfB = $this->makeOrder($driverB, 'affectee');

        Sanctum::actingAs($driverA->user);

        $this->patchJson("/api/livraisons/{$orderOfB->id}/statut", ['statut' => 'en_cours'])
            ->assertForbidden();

        $this->assertDatabaseHas('commandes', ['id' => $orderOfB->id, 'statut' => 'affectee']);
    }
}