<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Validator\IsValidManufacturer;

class IsValidManufacturerValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var IsValidManufacturer $constraint */
        if (null === $value || '' === $value) {
            return;
        }

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
