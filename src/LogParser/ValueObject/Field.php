<?php

namespace App\LogParser\ValueObject;

use App\LogParser\ValueObject\Exception\InvalidFieldException;

class Field
{
    private const YEAR = 'year';
    private const MONTH = 'month';
    private const DESIGNATION = 'designation';
    private const TYPE = 'type';
    private const SITE = 'site';
    private const REF = 'reference';

    private const FIELD_ARRAY = [
        self::YEAR => [40, 4],
        self::MONTH => [18, 3],
        self::DESIGNATION => [40, 15],
        self::TYPE => [121, 23],
        self::SITE => [160, 33],
        self::REF =>[198, 20]
    ];

    /** @var array */
    private $fieldHolder;

    public function __construct(array $field)
    {
        $this->fieldHolder = $field;
    }

    /**
     * Creates Field value object with correct field holder.
     * @throws InvalidFieldException
     */
    public static function createFromString(string $fieldName) : self
    {
        foreach(static::FIELD_ARRAY as $key => $value) {
            if ($fieldName === $key) {
                return new Field($value);
            }
        }
        throw new InvalidFieldException($fieldName . ' field not found.');
    }

    public function fieldHolder() : array
    {
        return $this->fieldHolder;
    }
}
