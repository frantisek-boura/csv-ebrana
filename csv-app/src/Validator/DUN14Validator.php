<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DUN14Validator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var DUN14 $constraint */


        if (strlen($value) === 14 && ctype_digit($value)) {
            return;
        }

        // melo by zde byt overeni existence kodu v nejake databazi
        // ke ktere nemam pristup

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
