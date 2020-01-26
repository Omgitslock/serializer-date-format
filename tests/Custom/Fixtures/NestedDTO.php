<?php declare(strict_types=1);

namespace Tests\Custom\Fixtures;

use App\Annotation\DateFormat;
use DateTime;

class NestedDTO
{
    /**
     * @var DateTime
     */
    private $dateWithoutFormat;

    /**
     * @var DateTime
     *
     * @DateFormat(DATE_COOKIE)
     */
    private $dateWithFormatDuplicate;

    public function __construct(DateTime $dateWithoutFormat, DateTime $dateWithFormatDuplicate)
    {
        $this->dateWithoutFormat = $dateWithoutFormat;
        $this->dateWithFormatDuplicate = $dateWithFormatDuplicate;
    }
}

