<?php

namespace SclZfCartPayment\Mapper;

use Doctrine\ORM\EntityManager;
use SclZfGenericMapper\DoctrineMapper;
use SclZfGenericMapper\Doctrine\FlushLock;
use SCL\Currency\MoneyFactory;
use SclZfCartPayment\Entity\Payment;

/**
 * A doctrine version of PaymentMapperInterface.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class DoctrinePaymentMapper extends DoctrineMapper implements
    PaymentMapperInterface
{
    /**
     * @var MoneyFactory
     */
    private $moneyFactory;

    public function __construct(
        EntityManager $entityManager,
        FlushLock $flushLock,
        MoneyFactory $moneyFactory
    ) {
        parent::__construct(
            new \SclZfCartPayment\Entity\Payment(),
            $entityManager,
            $flushLock
        );

        $this->moneyFactory = $moneyFactory;
    }

    /**
     * @return Payment
     */
    public function create()
    {
        $payment = parent::create();
        $payment->setMoneyFactory($this->moneyFactory);

        return $payment;
    }

    /**
     * @param  string $id
     *
     * @return Payment
     */
    public function findByTransactionId($id)
    {
        return $this->singleEntity($this->findBy(['transactionId' => $id]));
    }
}
