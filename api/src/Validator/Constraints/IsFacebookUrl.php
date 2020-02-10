<?php declare(strict_types = 1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsFacebookUrl extends Constraint
{
    /** @var string */
    public $message = 'The string "{{ string }}" is not a valid Facebook url: it should start with https://facebook.com/.';
}
