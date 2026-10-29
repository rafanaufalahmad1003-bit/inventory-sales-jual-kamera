<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Tests\TestCase;

class InventorySalesTest extends TestCase
{
    /**
     * Test 1: Guest cannot access dashboard, gets redirected to login.
     */
    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test 2: Invalid password login is rejected.
     */
    public function test_login_with_invalid_credentials_is_rejected()
    {
        $response = $this->post('/login', [
            'email' => 'owner@camera.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test 3: Owner can create a new camera product.
     */
    public function test_owner_can_create_product()
    {
        $owner = User::where('role', User::ROLE_OWNER)->first();
        $category = Category::first();
        $sku = 'TEST-CAM-' . mt_rand(10000, 99999);

        $response = $this->actingAs($owner)->post('/products', [
            'sku' => $sku,
            'name' => 'Sony FX3 Cinema Camera',
            'category_id' => $category->id,
            'purchase_price' => 50000000,
            'selling_price' => 59000000,
            'stock' => 5,
            'minimum_stock' => 2,
            'description' => 'Cinema line camera',
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['sku' => $sku]);
    }

    /**
     * Test 4: Admin and Staff CANNOT create product (403 Forbidden).
     */
    public function test_staff_and_admin_cannot_create_product()
    {
        $staff = User::where('role', User::ROLE_STAFF)->first();
        $admin = User::where('role', User::ROLE_ADMIN)->first();
        $category = Category::first();

        // Staff attempt
        $resStaff = $this->actingAs($staff)->post('/products', [
            'sku' => 'STAFF-FAIL-' . mt_rand(10000, 99999),
            'name' => 'Should Fail Camera',
            'category_id' => $category->id,
            'purchase_price' => 1000000,
            'selling_price' => 2000000,
            'stock' => 1,
            'minimum_stock' => 1,
        ]);
        $resStaff->assertStatus(403);

        // Admin attempt
        $resAdmin = $this->actingAs($admin)->post('/products', [
            'sku' => 'ADMIN-FAIL-' . mt_rand(10000, 99999),
            'name' => 'Should Fail Camera 2',
            'category_id' => $category->id,
            'purchase_price' => 1000000,
            'selling_price' => 2000000,
            'stock' => 1,
            'minimum_stock' => 1,
        ]);
        $resAdmin->assertStatus(403);
    }

    /**
     * Test 5: Duplicate SKU is rejected by validation.
     */
    public function test_duplicate_sku_is_rejected()
    {
        $owner = User::where('role', User::ROLE_OWNER)->first();
        $existingProduct = Product::first();

        $response = $this->actingAs($owner)->post('/products', [
            'sku' => $existingProduct->sku, // duplicate
            'name' => 'Duplicate Camera',
            'category_id' => $existingProduct->category_id,
            'purchase_price' => 1000000,
            'selling_price' => 2000000,
            'stock' => 2,
            'minimum_stock' => 1,
        ]);

        $response->assertSessionHasErrors('sku');
    }

    /**
     * Test 6: Staff can create Stock-In (Purchase) which increments stock.
     */
    public function test_purchase_stock_in_increments_product_stock()
    {
        $staff = User::where('role', User::ROLE_STAFF)->first();
        $supplier = Supplier::first();
        $product = Product::first();
        $initialStock = $product->stock;
        $qtyToAdd = 4;

        $response = $this->actingAs($staff)->post('/purchases', [
            'supplier_id' => $supplier->id,
            'purchased_at' => now()->format('Y-m-d H:i:s'),
            'notes' => 'Test restock purchase',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $qtyToAdd,
                    'unit_cost' => $product->purchase_price,
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $product->refresh();
        $this->assertEquals($initialStock + $qtyToAdd, $product->stock);
    }

    /**
     * Test 7: Sale successfully decrements stock.
     */
    public function test_sale_decrements_product_stock()
    {
        $staff = User::where('role', User::ROLE_STAFF)->first();
        $customer = Customer::first();
        $product = Product::where('stock', '>=', 2)->first();
        $initialStock = $product->stock;
        $qtyToSell = 2;

        $response = $this->actingAs($staff)->post('/sales', [
            'customer_id' => $customer->id,
            'sold_at' => now()->format('Y-m-d H:i:s'),
            'notes' => 'Test customer sale',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $qtyToSell,
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $product->refresh();
        $this->assertEquals($initialStock - $qtyToSell, $product->stock);
    }

    /**
     * Test 8: Insufficient stock rejects sale without altering stock or creating sale records.
     */
    public function test_insufficient_stock_rejects_sale()
    {
        $staff = User::where('role', User::ROLE_STAFF)->first();
        $customer = Customer::first();
        $product = Product::first();
        $initialStock = $product->stock;
        $excessiveQty = $initialStock + 1000;

        $response = $this->actingAs($staff)->post('/sales', [
            'customer_id' => $customer->id,
            'sold_at' => now()->format('Y-m-d H:i:s'),
            'notes' => 'Should fail due to insufficient stock',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $excessiveQty,
                ],
            ],
        ]);

        $response->assertSessionHasErrors('items');
        $product->refresh();
        $this->assertEquals($initialStock, $product->stock);
    }

    /**
     * Test 9: Pelanggan (Customer) can place an online order for a camera.
     */
    public function test_customer_can_place_online_order()
    {
        $product = Product::where('stock', '>', 2)->first();
        $initialStock = $product->stock;

        $response = $this->post("/catalog/{$product->id}/order", [
            'customer_name' => 'Dimas Anggara',
            'customer_phone' => '081299887766',
            'customer_email' => 'dimas@example.com',
            'customer_address' => 'Jl. Kebon Jeruk No. 10, Jakarta Barat',
            'quantity' => 1,
            'notes' => 'Packing ekstra aman',
        ]);

        $response->assertSessionHasNoErrors();
        $product->refresh();
        $this->assertEquals($initialStock - 1, $product->stock);
    }

    /**
     * Test 10: Owner can cancel completed sale and stock is restored.
     */
    public function test_owner_can_cancel_sale_and_restore_stock()
    {
        $owner = User::where('role', User::ROLE_OWNER)->first();
        $sale = Sale::where('status', 'completed')->first();
        $firstItem = $sale->saleItems->first();
        $product = $firstItem->product;
        $stockBeforeCancel = $product->stock;

        $response = $this->actingAs($owner)->post("/sales/{$sale->id}/cancel");

        $response->assertRedirect();
        $sale->refresh();
        $product->refresh();

        $this->assertEquals('cancelled', $sale->status);
        $this->assertEquals($stockBeforeCancel + $firstItem->quantity, $product->stock);
    }
}
