<?php

namespace Tests\LogParser;

use App\LogParser\LogParser;
use App\LogParser\ValueObject\Exception\InvalidFieldException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\LogParser\LogParser
 */
class LogParserTest extends TestCase
{
    /** @var LogParser */
    private $logParser;

    /** @var string */
    private $input;

    protected function setUp(): void
    {
        $this->logParser = new LogParser();
        $this->input = __DIR__ . '/testData';
    }

    /**
     * @test
     * @dataProvider validData
     */
    public function willReturnValidOutput(array $output, string $field, ?bool $success): void
    {
        $this->assertSame($output, $this->logParser->group_by($this->input, $field, $success));
    }

    /**
     * @test
     */
    public function willCatchException(): void
    {
        $this->expectException(InvalidFieldException::class);

        $this
            ->logParser
            ->group_by(
                $this->input,
                'some_field',
                null
            );
    }

    public function validData(): array
    {
        return [
            'data1' => [
                [
                '1957' => 2,
                '1958' => 2,
                ],
                'year',
                true
            ],
            'data2' => [
                [
                    '1957-U01' => 2,
                    '1957 BET 1' => 1,
                    '1958 ALP' => 3,
                ],
                'designation',
                null
            ],
            'data3' => [
                [
                    'Nov' => 1,
                    'Dec' => 1,
                ],
                'month',
                false
            ],
            'data4' => [
                [
                    'Sputnik 8K71PS' => 1,
                    'Aerobee' => 1,
                    'Vanguard' => 2,
                ],
                'type',
                true
            ],
            'data5' => [
                [
                    'NIIP-5   LC1' => 2,
                    'HADC     A' => 1,
                    'CC       LC18A' => 2,
                ],
                'site',
                null
            ],
            'data6' => [
                [
                    'Energiya' => 1,
                    'EngSci1.58' => 1,
                    'JunoFam' => 2,
                ],
                'reference',
                true
            ]
        ];
    }
}
