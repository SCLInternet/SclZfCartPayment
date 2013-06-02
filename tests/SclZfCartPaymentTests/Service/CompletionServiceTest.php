<?php

namespace SclZfCartPaymentTests\Service;

use SclZfCartPayment\Entity\PaymentInterface;
use SclZfCartPayment\Service\CompletionService;

class CompletionServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected $orderService;

    protected $paymentMapper;

    protected function setUp()
    {
        $this->orderService = $this->getMockBuilder('SclZfCart\Service\OrderCompletionService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentMapper = $this->getMock('SclZfCartPayment\Mapper\PaymentMapperInterface');

        $this->service = new CompletionService($this->paymentMapper, $this->orderService);
    }

    /**
     * Test the complete method.
     *
     * @covers SclZfCartPayment\Service\CompletionService::complete
     * @covers SclZfCartPayment\Service\CompletionService::__construct
     *
     * @return void
     */
    public function testComplete()
    {
        $payment = $this->getMock('SclZfCartPayment\Entity\PaymentInterface');

        $payment->expects($this->once())
                ->method('setStatus')
                ->with($this->equalTo(PaymentInterface::STATUS_SUCCESS));

        $this->paymentMapper
             ->expects($this->atLeastOnce())
             ->method('save')
             ->with($this->equalTo($payment));

        $order = $this->getMock('SclZfCart\Entity\OrderInterface');

        $payment->expects($this->any())
                ->method('getOrder')
                ->will($this->returnValue($order));

        $this->orderService
             ->expects($this->once())
             ->method('complete')
             ->with($this->equalTo($order));

        $this->service->complete($payment);
    }

    /**
     * Test the fail method.
     *
     * @covers SclZfCartPayment\Service\CompletionService::fail
     * @covers SclZfCartPayment\Service\CompletionService::__construct
     *
     * @return void
     */
    public function testFail()
    {
        $payment = $this->getMock('SclZfCartPayment\Entity\PaymentInterface');

        $payment->expects($this->once())
                ->method('setStatus')
                ->with($this->equalTo(PaymentInterface::STATUS_FAILED));

        $this->paymentMapper
             ->expects($this->atLeastOnce())
             ->method('save')
             ->with($this->equalTo($payment));

        $order = $this->getMock('SclZfCart\Entity\OrderInterface');

        $payment->expects($this->any())
                ->method('getOrder')
                ->will($this->returnValue($order));

        $this->orderService
             ->expects($this->once())
             ->method('fail')
             ->with($this->equalTo($order));

        $this->service->fail($payment);
    }
}
