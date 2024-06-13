<?php

namespace App\Tests\Functional\Controller\Catalog\UpdateController;


use App\Tests\Functional\WebTestCase;
use App\Entity\Product;

class UpdateControllerTest extends WebTestCase
{
    private const PRODUCT_UUID = 'fa0d7a85-d478-41e6-8b6e-833f6f02e2ed';

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_updates_product_successfully(): void
    {
        $product = new Product(self::PRODUCT_UUID, 'Old Name', 1000);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $this->client->request('PUT', '/products/' . self::PRODUCT_UUID, [
            'name' => 'New Name',
            'price' => 2000,
        ]);

        self::assertResponseStatusCodeSame(202);

        $updatedProduct = $this->entityManager->getRepository(Product::class)->find(self::PRODUCT_UUID);
        self::assertEquals('New Name', $updatedProduct->getName());
        self::assertEquals(2000, $updatedProduct->getPrice());
    }

    public function test_rejects_update_with_invalid_data(): void
    {
        $product = new Product(self::PRODUCT_UUID, 'Old Name', 1000);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $this->client->request('PUT', '/products/' . self::PRODUCT_UUID, [
            'name' => '',
            'price' => -10,
        ]);

        self::assertResponseStatusCodeSame(422);
    }

}