<?php declare(strict_types=1);

namespace Test\Custom;

use App\Provider\DateFormatContextProvider;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Tests\Custom\Fixtures\DTO;
use Tests\Custom\Fixtures\NestedDTO;
use Tests\Vendor\Fixtures\PropertyNormalizer;

final class AnnotationTest extends TestCase
{
    private const DEFAULT_DATETIME_FORMAT = DATE_ATOM;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    protected function setUp(): void
    {
        $this->createNormalizer();
    }

    private function createNormalizer()
    {
        $classMetaDataFactory = new ClassMetadataFactory(
            new AnnotationLoader(
                new AnnotationReader()
            )
        );

        $propertyNormalizer = new PropertyNormalizer(
            $classMetaDataFactory,
            new MetadataAwareNameConverter($classMetaDataFactory),
            new PhpDocExtractor()
        );

        $propertyNormalizer->setContextProvider(
            new DateFormatContextProvider(
                $classMetaDataFactory,
                new AnnotationReader(),
                new DateTimeNormalizer()
            )
        );

        $this->serializer = new Serializer([
            new DateTimeNormalizer(
                ['datetime_format' => self::DEFAULT_DATETIME_FORMAT]
            ),
            new ArrayDenormalizer(),
            $propertyNormalizer
        ], [new JsonEncoder(),]);

        AnnotationRegistry::registerLoader('class_exists');
    }

    public function testDateFormat()
    {
        $dateTime = \DateTime::createFromFormat(DATE_ATOM, '2020-01-26T16:18:42+00:00');

        $nestedDto = new NestedDTO($dateTime, clone $dateTime);
        $obj = new DTO(
            clone $dateTime,
            clone $dateTime,
            'someString',
            clone $dateTime,
            $nestedDto
        );

        $this->assertEquals(
            [
                'dateWithFormatUnique' => '2020-01-26',
                'wrongPropertyTypeForAnnotation' => 'someString',
                'dateWithFormatDuplicate' => '2020-01-26',
                'nestedObject' => [
                    'dateWithoutFormat' => '2020-01-26T16:18:42+00:00',
                    'dateWithFormatDuplicate' => 'Sunday, 26-Jan-2020 16:18:42 GMT+0000'
                ],
                'dateWithoutFormat' => '2020-01-26T16:18:42+00:00'
            ],
            $this->serializer->normalize($obj, 'any')
        );
    }

}
