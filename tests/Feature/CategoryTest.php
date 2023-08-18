<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testResource()
    {
        $this->seed([CategorySeeder::class]);

        $category = Category::query()->first();

        $this->get("/api/categories/$category->id")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'createdAt' => $category->created_at->toJSON()
                ]
            ]);
    }

    public function testResourceCollection()
    {
        $this->seed([CategorySeeder::class]);

        $category = Category::all();

        $this->get("/api/categories")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $category[0]->id,
                        'name' => $category[0]->name,
                        'description' => $category[0]->description,
                        'createdAt' => $category[0]->created_at->toJSON()
                    ],
                    [
                        'id' => $category[1]->id,
                        'name' => $category[1]->name,
                        'description' => $category[1]->description,
                        'createdAt' => $category[1]->created_at->toJSON()
                    ]
                ]
            ]);
    }

    public function testResourceCollectionCustom()
    {
        $this->seed([CategorySeeder::class]);

        $category = Category::all();

        $this->get("/api/categories-custom")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $category[0]->id,
                        'name' => $category[0]->name,
                    ],
                    [
                        'id' => $category[1]->id,
                        'name' => $category[1]->name,
                    ]
                ],
                'total' => 2
            ]);
    }

}
