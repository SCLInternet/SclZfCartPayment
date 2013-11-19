<?php

namespace SclZfCartPayment\Exception;

/**
 * Invalid arguement exception class
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class InvalidArgumentException extends \InvalidArgumentException implements
    ExceptionInterface
{
    public static function invalidValue(array $values, $value)
    {
        return new self(sprintf(
            'Expected one of "%s"; got "%s".',
            implode('|', $values),
            $value
        ));
    }
}
