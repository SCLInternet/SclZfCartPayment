<?php

namespace SclZfCartPayment\Mapper;

use SclZfUtilities\Mapper\GenericDoctrineMapper;

class DoctrinePaymentMapper extends GenericDoctrineMapper implements
    PaymentMapperInterface
{
    public function __construct($entityManager, $flushLock)
    {
        parent::__construct(
            $entityManager,
            $flushLock,
            'SclZfCartPayment\Entity\DoctrinePayment'
        );
    }
}
