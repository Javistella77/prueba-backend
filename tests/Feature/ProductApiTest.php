<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    private Currency $usd;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usd = Currency::create(['name' => 'USD', 'symbol' => '$', 'exchange_rate' => 1]);
    }

    private function productData(array $extra = []): array
    {
        return array_merge([
            'name' => 'Teclado',
            'description' => 'Teclado mecánico',
            'price' => 100,
            'currency_id' => $this->usd->id,
            'tax_cost' => 21,
            'manufacturing_cost' => 40,
        ], $extra);
    }

    private function login(): void
    {
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_requiere_autenticacion(): void
    {
        $this->getJson('/api/products')->assertUnauthorized();
    }

    public function test_crea_un_producto(): void
    {
        $this->login();

        $this->postJson('/api/products', $this->productData())
            ->assertCreated()
            ->assertJsonPath('data.name', 'Teclado');

        $this->assertDatabaseHas('products', ['name' => 'Teclado']);
    }

    public function test_valida_los_datos(): void
    {
        $this->login();

        $this->postJson('/api/products', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'price', 'currency_id']);
    }

    public function test_actualiza_y_elimina(): void
    {
        $this->login();
        $product = Product::create($this->productData());

        $this->putJson("/api/products/{$product->id}", ['name' => 'Mouse'])
            ->assertOk()
            ->assertJsonPath('data.name', 'Mouse');

        $this->deleteJson("/api/products/{$product->id}")->assertNoContent();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_agrega_precios_sin_duplicar_divisa(): void
    {
        $this->login();
        $product = Product::create($this->productData());
        $eur = Currency::create(['name' => 'EUR', 'symbol' => '€', 'exchange_rate' => 0.92]);
        $url = "/api/products/{$product->id}/prices";

        $this->postJson($url, ['currency_id' => $eur->id, 'price' => 92])->assertCreated();
        $this->postJson($url, ['currency_id' => $eur->id, 'price' => 95])->assertUnprocessable();

        $this->getJson($url)->assertOk()->assertJsonCount(1, 'data');
    }
}