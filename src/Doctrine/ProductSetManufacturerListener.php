<?php

namespace App\Doctrine;

use App\Entity\Product;
use Symfony\Component\Security\Core\Security;

class ProductSetManufacturerListener
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Product $product)
    {
        if ($product->getManufacturer()) {
            return;
        }

        if ($this->security->getUser()) {
            $product->setManufacturer($this->security->getUser());
        }

    }
}
