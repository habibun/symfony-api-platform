<?php

namespace App\Dto;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AppValidator;

class ProductInput
{
    #[Assert\NotBlank]
    #[Assert\Length(
        [
            'min' => 2,
            'max' => 50,
            'maxMessage'=>"Describe your name in 50 chars or less"
        ]
    )]
    #[Groups(['product:write', 'user:write'])]
    public string $name;

    #[Assert\NotBlank]
    #[Groups(['product:read', 'product:write', 'user:read', 'user:write'])]
    public float $price = 0;

    #[Groups('product:write')]
    public bool $isActive = false;

    #[Assert\NotBlank]
    public string $description;

    /**
     * @var User|null
     */
    #[Groups(['product:collection:post'])]
    #[AppValidator\isValidManufacturer()]
    public ?User $manufacturer = null;

    /** Description of product as raw text. */
    #[Groups(['product:write', 'user:write'])]
    #[SerializedName('description')]
    public function setTextDescription(?string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }

    public static function createFromEntity(?Product $product): self
    {
        $dto = new ProductInput();
        // not an edit, so just return an empty DTO
        if (!$product) {
            return $dto;
        }
        $dto->name = $product->getName();
        $dto->price = $product->getPrice();
        $dto->description = $product->getDescription();
        $dto->manufacturer = $product->getManufacturer();
        $dto->isActive = $product->getIsActive();
        return $dto;
    }

    public function createOrUpdateEntity(?Product $product): Product
    {
        if (!$product) {
            $product = new Product($this->name);
        }
        $product->setDescription($this->description);
        $product->setPrice($this->price);
        $product->setManufacturer($this->manufacturer);
        $product->setIsActive($this->isActive);
        return $product;
    }
}
