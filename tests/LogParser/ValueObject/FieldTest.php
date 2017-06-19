<?php


namespace Tests\LogParser\ValueObject;


use App\LogParser\ValueObject\Field;
use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;

/**
 * @covers \App\LogParser\ValueObject\Field
 */
class FieldTest extends TestCase
{
    /**
     * @test
     * @dataProvider validFields
     */
    public function willReturnValidField(string $fieldName, array $fieldHolder) : void
    {
        $this->assertSame($fieldHolder, Field::createFromString($fieldName)->fieldHolder());
    }

    /**
     * @test
     */
    public function willThrowException() : void
    {
        $this->expectException(InvalidArgumentException::class);

        Field::createFromString('field');
    }

    public function validFields() : array
    {
        return [
            'field1' => ['year', [40, 4]],
            'field2' => ['month', [18, 3]],
            'field3' => ['designation', [40, 15]],
            'field4' => ['type', [121, 23]],
            'field5' => ['site', [160, 33]],
            'field6' => ['reference', [198, 20]],
        ];
    }
}
