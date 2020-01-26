<?php declare(strict_types=1);

namespace App\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class DateFormat
{
    public $format;

    public function __construct(array $values)
    {
        $this->format = $values['value'] ?? \DATE_ATOM;
    }
}
