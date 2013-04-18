<?php

namespace SclZfCartPayment\Service;

use SclZfCartPayment\Entity\PaymentInterface;
use SclZfCartPayment\Mapper\PaymentMapperInterface;
use SclZfCart\Service\OrderCompletionService;

/**
 * Marks a payment as completed.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class CompletionService
{
    /**
     * The mapper for saving a payment.
     *
     * @var PaymentMapperInterface
     */
    protected $mapper;
    
    /**
     * The order completion service.
     *
     * @var OrderCompletionService
     */
    protected $orderService;

    /**
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
     * Mark a payment as successfully completing.
     *
     * @param  PaymentInterface $payment
     * @return void
     */
    public function complete(PaymentInterface $payment)
    {
        $payment->setStatus(PaymentInterface::STATUS_SUCCESS);

        $this->mapper->save($payment);

        $order = $payment->getOrder();
        $this->orderService->complete($order);
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

        $order = $payment->getOrder();
        $this->orderService->fail($order);
    }
}
