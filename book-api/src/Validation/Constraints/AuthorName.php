<?php
namespace App\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class AuthorName extends Constraint
{
    public $message = 'The book author must have capital letter in name and surname';
}