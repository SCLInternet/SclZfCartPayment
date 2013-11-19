<?php

namespace SclZfCartPayment\Exception;

/**
 * RuntimeException
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class RuntimeException extends \RuntimeException implements
    ExceptionInterface
{
    /**
     * 'Expected instance of "%s"; got "%s".'
     *
     * @param  sting $expected
     * @param  mixed $got
     *
     * @return RuntimeException
     */
    public static function invalidObjectType($expected, $got)
    {
        return new self(sprintf(
            'Expected instance of "%s"; got "%s".',
            $expected,
            is_object($got) ? get_class($got) : gettype($got)
        ));
    }
}
