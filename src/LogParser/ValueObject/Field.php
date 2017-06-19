<?php

declare(strict_types = 1);

namespace App\LogParser\ValueObject;

use App\LogParser\ValueObject\Exception\InvalidFieldException;

class Field
{
    private const YEAR        = 'year';
    private const MONTH       = 'month';
    private const DESIGNATION = 'designation';
    private const TYPE        = 'type';
    private const SITE        = 'site';
    private const REF         = 'reference';

    private const FIELD_ARRAY = [
        self::YEAR        => [40, 4],
        self::MONTH       => [18, 3],
        self::DESIGNATION => [40, 15],
        self::TYPE        => [121, 23],
        self::SITE        => [160, 33],
        self::REF         => [198, 20],
    ];

    /** @var array */
    private $fieldHolder;

    public function __construct(string $field)
    {
        if (!array_key_exists($field, static::FIELD_ARRAY)) {
            throw new InvalidFieldException($field . ' field not found.');
        }
        $this->fieldHolder = static::FIELD_ARRAY[$field];
    }

    /**
     * Creates Field value object with given field name.
     *
     * @throws InvalidFieldException
     */
    public static function createFromString(string $field) : self
    {
        return new self($field);
    }

    public function fieldHolder() : array
    {
        return $this->fieldHolder;
    }
}
