<?php

namespace SclZfCartPayment\Service;

use SclZfCartPayment\Entity\PaymentInterface;
use SclZfCartPayment\Mapper\PaymentMapperInterface;
use SclZfCart\Service\OrderCompletionService;

class PaymentService
{
    /**
     * The mapper for saving a payment.
     *
     * @var PaymentMapperInterface
     */
    private $mapper;

    /**
     * The order completion service.
     *
     * @var OrderCompletionService
     */
    private $orderService;

    /**
     * Set collaborator objects.
     *
     * @param  PaymentMapperInterface $mapper
     * @param  OrderCompletionService $orderService
     */
    public function __construct(
        PaymentMapperInterface $mapper,
        OrderCompletionService $orderService
    ) {
        $this->mapper = $mapper;
        $this->orderService = $orderService;
    }

    /**
     * Check if a payment has completed.
     *
     * @param  string $transactionId
     *
     * @return bool
     */
    public function isComplete(PaymentInterface $payment)
    {
        return PaymentInterface::STATUS_SUCCESS === $payment->getStatus()
            || PaymentInterface::STATUS_FAILED === $payment->getStatus();
    }

    /**
     * Mark a payment as successfully completing.
     *
     * @param  PaymentInterface $payment
     * @return void
     */
    public function complete(PaymentInterface $payment)
    {
        $payment->setStatus(PaymentInterface::STATUS_SUCCESS);

        $this->mapper->save($payment);

        $this->orderService->complete($payment->getOrder());
    }

    /**
     * Mark a payment as failing.
     *
     * @param  PaymentInterface $payment
     * @return void
     */
    public function fail(PaymentInterface $payment)
    {
        $payment->setStatus(PaymentInterface::STATUS_FAILED);

        $this->mapper->save($payment);

        $this->orderService->fail($payment->getOrder());
    }
}
