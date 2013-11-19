<?php

namespace SclZfCartPayment\Mapper;

use SclZfGenericMapper\DoctrineMapper;

/**
 * A doctrine version of PaymentMapperInterface.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class DoctrinePaymentMapper extends DoctrineMapper implements
    PaymentMapperInterface
{
    public function __construct($entityManager, $flushLock)
    {
        parent::__construct(
            new \SclZfCartPayment\Entity\Payment(),
            $entityManager,
            $flushLock
        );
    }

    public function findByTransactionId($id)
    {
        return $this->singleEntity($this->findBy(['transactionId' => $id]));
    }
}
