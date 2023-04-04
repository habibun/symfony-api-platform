<?php

namespace App\Validator;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidIsActiveValidator extends ConstraintValidator
{
    private $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * @param Product $value
     * @param Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\ValidIsActive */

        if (!$value instanceof Product) {
            throw new \LogicException('Only Product is supported');
        }

        $originalData = $this->entityManager
            ->getUnitOfWork()
            ->getOriginalEntityData($value);

        $previousIsActive = ($originalData['isActive'] ?? false);
        if ($previousIsActive === $value->getIsActive()) {
//            dd($originalData);
            // isActive didn't change!

            return;
        }

//        dd($value);
        if ($value->getIsActive()) {

            // we are publishing!

            // don't allow short descriptions, unless you are an admin
            if (strlen($value->getDescription()) < 100 && !$this->security->isGranted('ROLE_ADMIN')) {
                $this->context->buildViolation('Cannot publish: description is too short!')
                    ->atPath('description')
                    ->addViolation();
            }

            return;
        }

        // we are UNpublishing
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            // you can return a 403
            //throw new AccessDeniedException('Only admin users can unpublish');

            // or a normal validation error
            $this->context->buildViolation('Only admin users can unpublish')
                ->addViolation();
        }
    }
}
