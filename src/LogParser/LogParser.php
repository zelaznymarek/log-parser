<?php


namespace App\LogParser;

use Psr\Log\InvalidArgumentException;

class LogParser
{
    private const YEAR_START = [40, 4];
    private const MONTH_START = [18, 3];
    private const DESIGNATION_START = [40, 15];
    private const TYPE_START = [121, 23];
    private const SITE_START = [160, 33];
    private const SUCCESS_START = [193, 1];
    private const REF_START = [198, 20];
    private const YEAR = 'year';
    private const MONTH = 'month';
    private const DESIGNATION = 'designation';
    private const TYPE = 'type';
    private const SITE = 'site';
    private const REF = 'reference';

    public function group_by(string $source, string $field, ?bool $success) : array
    {
        $sourceArray = file($source, FILE_IGNORE_NEW_LINES);
        $fieldHolder = $this->matchField($field);
        $results = [];

        foreach ($sourceArray as $line) {
            if ('#' === $line{0}) {
                continue;
            }
            $launchSuccess = substr($line, static::SUCCESS_START[0], static::SUCCESS_START[1]);
            $key = trim(substr($line, $fieldHolder[0], $fieldHolder[1]));
            if (null === $success) {
                $this->add($results, $key);
            } else if ($success && 'S' === $launchSuccess) {
                $this->add($results, $key);
            } else if (!$success && 'F' === $launchSuccess) {
                $this->add($results, $key);
            }
        }

        return $results;
    }

    /**
     * Returns matched field start index and length.
     * @throws InvalidArgumentException
     */
    private function matchField(string $field) : array
    {
        switch ($field) {
            case (static::YEAR) :
                return static::YEAR_START;
                break;
            case (static::MONTH) :
                return static::MONTH_START;
                break;
            case (static::DESIGNATION) :
                return static::DESIGNATION_START;
                break;
            case (static::TYPE) :
                return static::TYPE_START;
                break;
            case (static::SITE) :
                return static::SITE_START;
                break;
            case (static::REF) :
                return static::REF_START;
                break;
            default :
                throw new InvalidArgumentException($field . '  field not found.');
        }
    }

    private function add(array &$results, $key) : void {
        if (!array_key_exists($key, $results)) {
            $results[$key] = 1;
        } else {
            ++$results[$key];
        }
    }
}
