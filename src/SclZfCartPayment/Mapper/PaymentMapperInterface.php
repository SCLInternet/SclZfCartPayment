<?php

namespace SclZfCartPayment\Mapper;

use SclZfGenericMapper\MapperInterface as GenericMapperInterface;

/**
 * Interface for the a Payment entity mapper.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface PaymentMapperInterface extends GenericMapperInterface
{
    /**
     * Find a payment entity by the transaction ID.
     *
     * @param  string $id
     *
     * @return PaymentInterface
     */
    public function findByTransactionId($id);
}
