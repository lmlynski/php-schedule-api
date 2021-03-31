<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation;

use App\Infrastructure\Validation\Exception\ValidationException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data): void
    {
        $violationList = $this->validator->validate($data, $this->getConstraints());

        if ($violationList->count() > 0) {
            throw ValidationException::withViolationList($violationList);
        }
    }

    abstract protected function getConstraints(): Constraint;
}
