<?php

namespace App\LogParser;

use App\LogParser\ValueObject\Exception\InvalidFieldException;
use App\LogParser\ValueObject\Field;

class LogParser
{
    /** @var array */
    private const SUCCESS_HOLDER = [193, 1];

    /**
     * Aggregates launches according to passed field.
     * @throws InvalidFieldException
     */
    public function group_by(string $source, string $field, ?bool $success) : ?array
    {
        $sourceArray = file($source, FILE_IGNORE_NEW_LINES);

        $fieldHolder = Field::createFromString($field)->fieldHolder();

        $results = [];

        if (null === $success) {
            $this->addAll($sourceArray, $fieldHolder, $results);
            return $results;
        }

        if ($success) {
            $this->addSuccessfull($sourceArray, $fieldHolder, $results);
            return $results;
        }

        if (!$success) {
            $this->addFailed($sourceArray, $fieldHolder, $results);
            return $results;
        }

        return null;
    }

    /**
     * Aggregates all launches with not empty field.
     */
    private function addAll(array $sourceArray, array $fieldHolder, array &$results) : void
    {
        foreach ($sourceArray as $line) {
            $key = trim(substr($line, $fieldHolder[0], $fieldHolder[1]));
            if ($this->isKeyEmpty($key) || $this->isLineAHeader($line)) {
                continue;
            }

            $this->add($results, $key);
        }
    }

    /**
     * Aggregates succesful launches with not empty field.
     */
    private function addSuccessfull(array $sourceArray, array $fieldHolder, array &$results) : void
    {
        foreach ($sourceArray as $line) {
            $key = trim(substr($line, $fieldHolder[0], $fieldHolder[1]));
            $launchSuccess = trim(substr($line, static::SUCCESS_HOLDER[0], static::SUCCESS_HOLDER[1]));
            if ($this->isKeyEmpty($key)
                || $this->isLineAHeader($line)
                || !$this->isSuccessful($launchSuccess)) {
                continue;
            }

            $this->add($results, $key);
        }
    }

    /**
     * Aggregates failed launches with not empty field.
     */
    private function addFailed(array $sourceArray, array $fieldHolder, array &$results) : void
    {
        foreach ($sourceArray as $line) {
            $key = trim(substr($line, $fieldHolder[0], $fieldHolder[1]));
            $launchSuccess = trim(substr($line, static::SUCCESS_HOLDER[0], static::SUCCESS_HOLDER[1]));
            if ($this->isKeyEmpty($key)
                || $this->isLineAHeader($line)
                || !$this->isFailed($launchSuccess)) {
                continue;
            }

            $this->add($results, $key);
        }
    }

    /**
     * Returns true if aggregated field if empty.
     */
    private function isKeyEmpty(string $key) : bool
    {
        return '' === $key;
    }

    /**
     * Returns true if line starts with #.
     */
    private function isLineAHeader(string $line) : bool
    {
        return '#' === $line{0};
    }

    /**
     * Returns true if launch was failed;
     */
    private function isFailed(string $launchSuccess) : bool
    {
        return 'F' === $launchSuccess;
    }

    /**
     * Returns true if launch was successful;
     */
    private function isSuccessful(string $launchSuccess) : bool
    {
        return 'S' === $launchSuccess;
    }

    /**
     * If key does not exist in array, adds it.
     * If it exists, increments its counter.
     */
    private function add(array &$results, string $key) : void
    {
        if (!array_key_exists($key, $results)) {
            $results[$key] = 1;
        } else {
            ++$results[$key];
        }
    }
}
