<?php

namespace SclZfCartPayment\Mapper;

use SclZfGenericMapper\MapperInterface as GenericMapperInterface;

interface PaymentMapperInterface extends GenericMapperInterface
{
    /**
     * Find a payment entity by the transaction ID.
     *
     * @param string $id
     *
     * @return \SclZfCartPayment\Entity\Payment
     */
    public function findByTransactionId($id);
}
