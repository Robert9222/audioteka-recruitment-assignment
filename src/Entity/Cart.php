<?php

namespace App\Entity;

use App\Service\Catalog\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class Cart implements \App\Service\Cart\Cart
{
    public const CAPACITY = 3;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\OneToMany(mappedBy: "cart", targetEntity: "CartProducts", cascade: ["persist"])]
    private $products;

    public function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
        $this->products = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getTotalPrice(): int
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->getProduct()->getPrice() * $product->getQuantity();
        }
        return $total;
    }

    #[Pure]
    public function isFull(): bool
    {
        $totalQuantity = 0;
        foreach ($this->products as $cartProduct) {
            $totalQuantity += $cartProduct->getQuantity();
        }
        return $totalQuantity >= self::CAPACITY;
    }

    public function getProducts(): iterable
    {
        return $this->products->getIterator();
    }

    #[Pure]
    public function hasProduct(\App\Entity\Product $product): bool
    {
        return $this->products->contains($product);
    }

    public function addProduct(Product $product, int $quantity = 1)
    {
        foreach ($this->products as $cartProduct) {
            if ($cartProduct->getProduct() === $product) {
                $cartProduct->setQuantity($cartProduct->getQuantity() + $quantity);
                return;
            }
        }
        $cartProduct = new CartProducts();
        $cartProduct->setCart($this);
        $cartProduct->setProduct($product);
        $cartProduct->setQuantity($quantity);
        $this->products->add($cartProduct);
    }

    public function removeProduct(\App\Entity\Product $product): void
    {
        foreach ($this->products as $cartProduct) {
            if ($cartProduct->getProduct() === $product) {
                if ($cartProduct->getQuantity() > 1) {
                    $cartProduct->setQuantity($cartProduct->getQuantity() - 1);
                } else {
                    $this->products->removeElement($cartProduct);
                }
                return;
            }
        }
    }
}
