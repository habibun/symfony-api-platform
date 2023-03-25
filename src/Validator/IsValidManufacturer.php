<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsValidManufacturer extends Constraint
{
    public $message = 'Cannot set owner to a different user';

    public $anonymousMessage = 'Cannot set owner unless you are authenticated';
}
