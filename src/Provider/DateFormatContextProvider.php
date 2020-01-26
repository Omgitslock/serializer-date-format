<?php declare(strict_types=1);

namespace App\Provider;

use App\Annotation\DateFormat;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

final class DateFormatContextProvider implements ContextProvider
{
    /**
     * @var ClassMetadataFactoryInterface
     */
    private $classMetadataFactory;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var string
     */
    private $outerObjectClass;

    /**
     * @var string
     */
    private $format;

    /**
     * @var DateTimeNormalizer
     */
    private $dateTimeNormalizer;

    public function __construct(ClassMetadataFactoryInterface $classMetadataFactory, Reader $reader, DateTimeNormalizer $dateTimeNormalizer)
    {
        $this->classMetadataFactory = $classMetadataFactory;
        $this->reader = $reader;
        $this->dateTimeNormalizer = $dateTimeNormalizer;
    }

    private function getCallback(): callable
    {
        return function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            if ($this->outerObjectClass !== get_class($outerObject)) {
                return $innerObject;
            }

            if (!$this->dateTimeNormalizer->supportsNormalization($innerObject)) {
                return $innerObject;
            }

            return $this->dateTimeNormalizer->normalize($innerObject, $format, [DateTimeNormalizer::FORMAT_KEY => $this->format]);
        };
    }

    public function getContext($object): array
    {
        $reflectionClass = $this->classMetadataFactory->getMetadataFor($object)->getReflectionClass();

        $serializingFormatContext = [];

        foreach($reflectionClass->getProperties() as $property) {
            /** @var DateFormat $annotation */
            $annotation = $this->reader->getPropertyAnnotation($property, DateFormat::class);

            if ($annotation !== null){
                $this->format =  $annotation->format;
                $this->outerObjectClass = get_class($object);

                $serializingFormatContext[AbstractNormalizer::CALLBACKS][$property->getName()] = $this->getCallback();
            }
        }

        return $serializingFormatContext;
    }
}
