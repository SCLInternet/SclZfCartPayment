<?php

namespace SclZfCartPaymentTests\Service;

use SclZfCartPayment\Entity\Payment;
use SclZfCartPayment\Service\PaymentService;

class PaymentServiceTest extends \PHPUnit_Framework_TestCase
{
    private $service;

    private $orderService;

    private $paymentMapper;

    /**
     * Set up the instance to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->orderService = $this->getMockBuilder('SclZfCart\Service\OrderCompletionService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentMapper = $this->getMock('SclZfCartPayment\Mapper\PaymentMapperInterface');

        $this->service = new PaymentService(
            $this->paymentMapper,
            $this->orderService
        );
    }

    /*
     * isComplete()
     */

    public function test_isComplete_returns_false_for_pending_payment()
    {
        $payment = new Payment();

        $payment->setStatus(Payment::STATUS_PENDING);

        $this->assertFalse($this->service->isComplete($payment));
    }

    public function test_isComplete_returns_true_for_success_payment()
    {
        $payment = new Payment();

        $payment->setStatus(Payment::STATUS_SUCCESS);

        $this->assertTrue($this->service->isComplete($payment));
    }

    public function test_isComplete_returns_true_for_failed_payment()
    {
        $payment = new Payment();

        $payment->setStatus(Payment::STATUS_FAILED);

        $this->assertTrue($this->service->isComplete($payment));
    }

    /*
     * complete()
     */

    public function test_complete()
    {
        $payment = $this->getMock('SclZfCartPayment\Entity\Payment');

        $payment->expects($this->once())
                ->method('setStatus')
                ->with($this->equalTo(Payment::STATUS_SUCCESS));

        $this->paymentMapper
             ->expects($this->atLeastOnce())
             ->method('save')
             ->with($this->equalTo($payment));

        $order = $this->getMock('SclZfCart\Entity\Order');

        $payment->expects($this->any())
                ->method('getOrder')
                ->will($this->returnValue($order));

        $this->orderService
             ->expects($this->once())
             ->method('complete')
             ->with($this->equalTo($order));

        $this->service->complete($payment);
    }

    /*
     * fail()
     */

    public function test_fail()
    {
        $payment = $this->getMock('SclZfCartPayment\Entity\Payment');

        $payment->expects($this->once())
                ->method('setStatus')
                ->with($this->equalTo(Payment::STATUS_FAILED));

        $this->paymentMapper
             ->expects($this->atLeastOnce())
             ->method('save')
             ->with($this->equalTo($payment));

        $order = $this->getMock('SclZfCart\Entity\Order');

        $payment->expects($this->any())
                ->method('getOrder')
                ->will($this->returnValue($order));

        $this->orderService
             ->expects($this->once())
             ->method('fail')
             ->with($this->equalTo($order));

        $this->service->fail($payment);
    }

    /*
     * Service manager tests
     */

    public function test_service_manager_create_instance()
    {
        $this->assertInstanceOf(
            'SclZfCartPayment\Service\PaymentService',
            \TestBootstrap::getApplication()
                          ->getServiceManager()
                          ->get('SclZfCartPayment\Service\PaymentService')
        );
    }
}
