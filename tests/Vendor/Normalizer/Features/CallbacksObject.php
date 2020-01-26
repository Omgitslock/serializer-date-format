<?php

namespace Tests\Vendor\Normalizer\Features;

class CallbacksObject
{
    public $bar;

    public function __construct($bar = null)
    {
        $this->bar = $bar;
    }

    public function getBar()
    {
        return $this->bar;
    }
}
