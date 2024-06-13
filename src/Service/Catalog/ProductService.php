<?php

namespace App\Service\Catalog;

use App\Entity\Product;

interface ProductService
{
    public function add(string $name, int $price): Product;

    public function remove(string $id): void;

    public function update(string $id, string $name, int $price): void;
}