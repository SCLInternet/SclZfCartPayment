<?php

namespace SclZfCartPayment\Service;

use SclZfCartPayment\Entity\Payment;
use SclZfCartPayment\Mapper\PaymentMapperInterface;
use SclZfCart\Service\OrderCompletionService;

class PaymentService
{
    /**
     * @var PaymentMapperInterface
     */
    private $mapper;

    /**
     * @var OrderCompletionService
     */
    private $orderService;

    public function __construct(
        PaymentMapperInterface $mapper,
        OrderCompletionService $orderService
    ) {
        $this->mapper = $mapper;
        $this->orderService = $orderService;
    }

    /**
     * @param string $transactionId
     *
     * @return bool
     */
    public function isComplete(Payment $payment)
    {
        return Payment::STATUS_SUCCESS === $payment->getStatus()
            || Payment::STATUS_FAILED === $payment->getStatus();
    }

    public function complete(Payment $payment)
    {
        $payment->setStatus(Payment::STATUS_SUCCESS);

        $this->mapper->save($payment);

        $this->orderService->complete($payment->getOrder());
    }

    public function fail(Payment $payment)
    {
        $payment->setStatus(Payment::STATUS_FAILED);

        $this->mapper->save($payment);

        $this->orderService->fail($payment->getOrder());
    }
}
