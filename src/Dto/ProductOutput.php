<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class ProductOutput
{
    #[Groups(['product:read'])]
    public $title;

}
