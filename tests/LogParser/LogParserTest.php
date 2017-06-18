<?php

namespace Tests\LogParser;

use App\LogParser\LogParser;
use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;

/**
 * @covers \App\LogParser\LogParser
 */
class LogParserTest extends TestCase
{
    /** @var LogParser */
    private $logParser;

    /** @var string */
    private $input;

    protected function setUp() : void
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
    public function willThrowException() : void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->logParser->group_by($this->input, 'field', null);
    }

    public function validData(): array
    {
        return [
            'data1' => [
                [
                    '1957' => 2,
                    '1958' => 1
                ],
                'year',
                true
            ],
            'data2' => [
                [
                    '1957-U01' => 2,
                    '1957 BET 1' => 1,
                    '1958 ALP' => 2,
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
                    'Vanguard' => 1,
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
                    'JunoFam' => 1,
                ],
                'reference',
                true
            ]
        ];
    }
}