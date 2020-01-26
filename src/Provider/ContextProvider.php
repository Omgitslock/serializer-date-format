<?php declare(strict_types=1);

namespace App\Provider;

interface ContextProvider
{
    public function getContext($object): array;
}
