<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

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

    public $description;

    /** Description of product as raw text. */
    #[Groups(['product:write', 'user:write'])]
    #[SerializedName('description')]
    public function setTextDescription(?string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }
}
