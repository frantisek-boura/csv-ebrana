<?php

namespace App\Validator;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EAN13Validator extends ConstraintValidator
{

    public function __construct(private LoggerInterface $logger) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var EAN13 $constraint */

        if (strlen($value) !== 13 || !ctype_digit($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

        $data_part = substr($value, 0, -1);
        $check_sum = intval(substr($value, 12, 1));

        $data_part = str_split($data_part);

        $alternate = true;
        foreach ($data_part as &$v) {
            $v = intval($v) * ($alternate ? 1 : 3);
            $alternate = !$alternate;
        }
        unset($v);

        $data_sum = 0;
        foreach ($data_part as $v) {
            $data_sum += $v;
        }

        $nearest_multiple = ceil((float) $data_sum / 10.0) * 10;
        $diff = $nearest_multiple - $data_sum;

        if ($check_sum == $diff) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
