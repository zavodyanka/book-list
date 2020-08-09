<?php
namespace App\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */

class AuthorNameValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        preg_match('/([A-Z][a-z]+)\s([A-Z][a-z]+)/', $value, $matches);

        if (!isset($matches[0]) || $matches[0] != $value) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}