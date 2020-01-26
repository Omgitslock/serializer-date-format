<?php declare(strict_types=1);

namespace App\Provider;

trait ContextProviderTrait
{
    /**
     * @var ContextProvider
     */
    private $contextProvider;

    public function setContextProvider(ContextProvider $defaultCallbackChanger)
    {
        $this->contextProvider = $defaultCallbackChanger;
    }

    private function addDefaultContext($object): void
    {
        if ($this->contextProvider !== null) {
            $this->defaultContext = array_merge($this->defaultContext, $this->contextProvider->getContext($object));
        }
    }

}
