<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Tests\TestCase;
use function PHPUnit\Framework\assertContains;

class ProductTest extends TestCase
{
    public function testProduct()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $product = Product::query()->first();

        $this->get("/api/products/$product->id")
            ->assertStatus(200)
            ->assertHeader("X-Powered-By", "Akmal Muhammad Pridianto")
            ->assertJson([
                "value" => [
                    "name" => $product->name,
                    "category" => [
                        "id" => $product->category->id,
                        "name" => $product->category->name
                    ],
                    "price" => $product->price,
                    "is_expensive" => $product->price > 500,
                    "createdAt" => $product->created_at->toJSON(),
                    "updatedAt" => $product->updated_at->toJSON()
                ]
            ]);
    }

    public function testCollectionWrap()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $response = $this->get("/api/products")
            ->assertStatus(200)
            ->assertHeader("X-Powered-By", "Akmal Muhammad Pridianto");

        $names = $response->json("data.*.name");

        for ($i = 0; $i < 5; $i++) {
            assertContains("Product $i of Food", $names);
        }
        for ($i = 0; $i < 5; $i++) {
            assertContains("Product $i of Gadget", $names);
        }
    }

    public function testCollectionPaging()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $response = $this->get("/api/products-paging")->assertStatus(200);

        self::assertNotNull($response->json("links"));
        self::assertNotNull($response->json("meta"));
        self::assertNotNull($response->json("data"));
    }

    public function testAdditional()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $product = Product::query()->first();

        $response = $this->get("/api/products-debug/$product->id")
            ->assertStatus(200)
            ->assertJson([
                "author" => "Akmal Muhammad Pridianto",
                "data" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "price" => $product->price
                ]
            ]);

        self::assertNotNull($response->json("server_time"));
    }


}
