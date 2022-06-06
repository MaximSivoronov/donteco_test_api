<?php

namespace Tests\Feature;

use App\Models\category;
use App\Models\product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class categoryControllerTest extends TestCase
{
    public function testGetCategories()
    {
        $response = $this->get('/api/v1/category');
        $response->assertStatus(200);

        $response = $this->get('/api/v1/something_else');
        $response->assertStatus(404);
    }

    public function testGetCategory()
    {
        $lastCategoryId = $this->getLastCategoryId();

        $response = $this->get('/api/v1/category/' . $lastCategoryId);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/category/' . 'qwerty');
        $response->assertStatus(404);
    }

    public function testStoreCategory()
    {
        $response = $this->post('/api/v1/category', []);
        $response->assertStatus(302);

        $response = $this->post('/api/v1/category', [
            'title' => 'test title',
        ]);

        $response->assertStatus(201);
    }

    public function testUpdateCategory()
    {
        $lastCategoryId = $this->getLastCategoryId();

        $response = $this->patch('/api/v1/category/' . $lastCategoryId, []);
        $response->assertStatus(302);

        $response = $this->patch('/api/v1/category/' . $lastCategoryId, [
            'title' => 'updated title',
        ]);

        $response->assertStatus(200);
    }

    public function testDeleteCategory()
    {
        $lastCategoryId = $this->getLastCategoryId();

        $response = $this->delete('/api/v1/category/' . $lastCategoryId);
        $response->assertStatus(204);

        $response = $this->delete('/api/v1/category/' . $lastCategoryId);
        $response->assertStatus(404);
    }

    private function getLastCategoryId()
    {
        $lastCategoryId = category::latest()->first()->id;

        return $lastCategoryId;
    }
}
