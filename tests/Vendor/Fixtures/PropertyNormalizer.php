<?php declare(strict_types=1);

namespace Tests\Vendor\Fixtures;

use App\Provider\ContextProviderTrait;
use \Symfony\Component\Serializer\Normalizer\PropertyNormalizer as BasePropertyNormalizer;

final class PropertyNormalizer extends BasePropertyNormalizer
{
    use ContextProviderTrait;

    /**
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $this->addDefaultContext($object);

        return parent::normalize($object, $format, $context);
    }
}
