<?php

namespace Tests\Vendor\Normalizer\Features;

class TypeEnforcementNumberObject
{
    /**
     * @var float
     */
    public $number;

    public function setNumber($number)
    {
        $this->number = $number;
    }
}
