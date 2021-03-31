<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\Task;

use App\Infrastructure\Validation\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class TaskAddValidator extends AbstractValidator
{
    protected function getConstraints(): Constraint
    {
        return new Constraints\Collection(
            [
                'title' => [
                    new Constraints\Required(),
                    new Constraints\NotBlank(),
                    new Constraints\Type('string'),
                    new Constraints\Length(['min' => 3, 'max' => 256])
                ],
                'description' => [
                    new Constraints\Required(),
                    new Constraints\NotBlank(),
                    new Constraints\Type('string'),
                    new Constraints\Length(['min' => 3, 'max' => 2000])
                ],
                'assigneeId' => [
                    new Constraints\Required(),
                    new Constraints\NotBlank(),
                    new Constraints\Type('string'),
                    new Constraints\Regex(
                        [
                            'pattern' => '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
                            'message' => 'This value should be GUID format.'
                        ]
                    ),
                ],
                'status' => [
                    new Constraints\Required(),
                    new Constraints\NotBlank(),
                    new Constraints\Type('string'),
                    new Constraints\Length(['min' => 3, 'max' => 20])
                ],
                'dueDate' => [
                    new Constraints\Required(),
                    new Constraints\NotBlank(),
                    new Constraints\Type('string'),
                    new Constraints\Date(
                        [
                            'message' => 'This value should be date format.'
                        ]
                    )
                ]
            ]
        );
    }
}
