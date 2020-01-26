<?php declare(strict_types=1);

namespace Tests\Custom\Fixtures;

use App\Annotation\DateFormat;
use DateTime;

class DTO
{
    /**
     * @var DateTime
     *
     * @DateFormat("Y-m-d")
     */
    private $dateWithFormatUnique;

    /**
     * @var string
     *
     * @DateFormat("Y-m-d")
     */
    private $wrongPropertyTypeForAnnotation;

    /**
     * @var DateTime
     *
     * @DateFormat("Y-m-d")
     */
    private $dateWithFormatDuplicate;

    /**
     * @var DateTime
     */
    private $dateWithoutFormat;

    /**
     * @var NestedDTO
     */
    private $nestedObject;

    public function __construct(
        DateTime $dateWithFormatUnique,
        DateTime $dateWithFormatDuplicate,
        string $wrongPropertyTypeForAnnotation,
        DateTime $dateWithoutFormat,
        NestedDTO $nestedObject
    )
    {
        $this->dateWithFormatUnique = $dateWithFormatUnique;
        $this->dateWithFormatDuplicate = $dateWithFormatDuplicate;
        $this->wrongPropertyTypeForAnnotation = $wrongPropertyTypeForAnnotation;
        $this->dateWithoutFormat = $dateWithoutFormat;
        $this->nestedObject = $nestedObject;
    }

}
