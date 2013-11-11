<?php

namespace SclZfCartPayment\Mapper;

use SclZfGenericMapper\DoctrineMapper as GenericDoctrineMapper;

/**
 * A doctrine version of PaymentMapperInterface.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class DoctrinePaymentMapper extends GenericDoctrineMapper implements
    PaymentMapperInterface
{
    public function __construct($entityManager, $flushLock)
    {
        parent::__construct(
            $entityManager,
            $flushLock,
            'SclZfCartPayment\Entity\Payment'
        );
    }
}
