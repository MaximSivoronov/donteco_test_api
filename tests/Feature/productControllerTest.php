<?php

namespace Tests\Feature;

use App\Models\category;
use App\Models\product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class productControllerTest extends TestCase
{
    public function testGetProducts()
    {
        $response = $this->get('/api/v1/product');
        $response->assertStatus(200);

        $response = $this->get('/api/v1/something_else');
        $response->assertStatus(404);
    }

    public function testGetProduct()
    {
        $lastProdId = $this->getLastProductId();

        $response = $this->get('/api/v1/product/' . $lastProdId);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/product/' . 'qwerty');
        $response->assertStatus(404);
    }

    public function testStoreProduct()
    {
        $response = $this->post('/api/v1/product', []);
        $response->assertStatus(302);

        $response = $this->post('/api/v1/product', [
            'title' => 'test title',
            'description' => 'test description',
            'price' => 101,
            'category_id' => $this->getLastCategoryId(),
        ]);

        $response->assertStatus(201);
    }

    public function testUpdateProduct()
    {
        $lastProdId = $this->getLastProductId();

        $response = $this->patch('/api/v1/product/' . $lastProdId, []);
        $response->assertStatus(302);

        $response = $this->patch('/api/v1/product/' . $lastProdId, [
            'title' => 'updated title',
            'description' => 'updated description',
            'price' => 404,
        ]);

        $response->assertStatus(200);
    }

    public function testDeleteProduct()
    {
        $lastProdId = $this->getLastProductId();

        $response = $this->delete('/api/v1/product/' . $lastProdId);
        $response->assertStatus(204);

        $response = $this->delete('/api/v1/product/' . $lastProdId);
        $response->assertStatus(404);
    }

    private function getLastProductId() {

        $lastProductId = product::latest()->first()->id;

        return $lastProductId;
    }

    private function getLastCategoryId()
    {
        $lastCategoryId = category::latest()->first()->id;

        return $lastCategoryId;
    }
}
