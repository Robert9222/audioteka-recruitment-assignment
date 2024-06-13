<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "cart_products")]
class CartProducts
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    private UuidInterface $id;

    #[ORM\Column(name: "quantity", type: "integer", nullable: false, options: ["default" => 1])]
    private int $quantity = 1;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Product")]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "id")]
    private ?Product $product;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Cart")]
    #[ORM\JoinColumn(name: "cart_id", referencedColumnName: "id")]
    private ?Cart $cart;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;
        return $this;
    }
}
