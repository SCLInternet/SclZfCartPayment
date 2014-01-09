<?php

namespace SclZfCartPaymentTests\Listener;

use SclZfCartPayment\Listener\CartListener;
use SclZfCartPayment\Method\MethodSelectorInterface;
use SclZfCart\CartEvent;
use SclZfCart\Entity\Order;

class CartListenerTest extends \PHPUnit_Framework_TestCase
{
    private $cart;

    private $selector;

    private $mapper;

    protected function setUp()
    {
        $this->selector = $this->getMock('SclZfCartPayment\Method\MethodSelectorInterface');
        $this->mapper   = $this->getMock('SclZfCartPayment\Mapper\PaymentMapperInterface');

        $this->listener = new CartListener($this->selector, $this->mapper);
    }

    public function test_service_manager_can_create()
    {
        $this->assertInstanceOf(
            'SclZfCartPayment\Listener\CartListener',
            \TestBootstrap::getApplication()
                          ->getServiceManager()
                          ->get('SclZfCartPayment\Listener\CartListener')
        );
    }

    /**
     * Test that if a payment method is already selected during checkout that
     * nothing happens and checkout is continued.
     */
    public function test_checkout_with_selected_method()
    {
        $method = $this->getMock('SclZfCartPayment\PaymentMethodInterface');

        $this->setSelectedMethod($method);

        $this->assertNull($this->listener->checkout($this->createCartEvent()));
    }

    /**
     * Test that if no payment method is selected during checkout the user is
     * redirected to the payment method selection page.
     */
    public function test_checkout_without_selected_method()
    {
        $event = new CartEvent();

        $this->setSelectedMethod(MethodSelectorInterface::NO_METHOD_SELECTED);

        $result = $this->listener->checkout($event);

        $this->assertTrue($event->propagationIsStopped());

        $this->assertInstanceOf('SclZfUtilities\Model\Route', $result);
        $this->assertEquals('payment/select-payment', $result->route);
    }

    /**
     * Test when process() is passed an event with a target which isn't an instanceof
     * \SclZfCart\Entity\Order that an exception is throw.
     */
    public function test_process_with_bad_order()
    {
        $event = new CartEvent();

        $event->setTarget(7);

        $this->setExpectedException('SclZfCartPayment\Exception\RuntimeException');

        $this->listener->process($event);
    }

    /**
     * Test that when process() is called but a payment method isn't selected
     * that an exception is throw.
     */
    public function test_process_with_no_payment_method()
    {
        $this->setSelectedMethod('x');

        $this->setExpectedException('SclZfCartPayment\Exception\RuntimeException');

        $this->listener->process($this->createCartEvent());
    }

    public function test_process()
    {
        $method  = $this->getMock('SclZfCartPayment\PaymentMethodInterface');
        $payment = $this->getMock('SclZfCartPayment\Entity\Payment');

        $this->setSelectedMethod($method);

        $this->mapper
             ->expects($this->once())
             ->method('create')
             ->will($this->returnValue($payment));

        $this->mapper
             ->expects($this->once())
             ->method('save')
             ->with($this->equalTo($payment));

        $method->expects($this->once())
               ->method('updateCompleteForm');

        $this->listener->process($this->createCartEvent());
    }

    public function test_process_sets_payment_transaction_id()
    {
        $transactionId = 'TX-00001';

        $payment = $this->getMock('SclZfCartPayment\Entity\Payment');

        $payment->expects($this->once())
                ->method('setTransactionId')
                ->with($this->equalTo($transactionId));

        $method = $this->getMock('SclZfCartPayment\PaymentMethodInterface');

        $method->expects($this->any())
               ->method('generateTransactionId')
               ->will($this->returnValue($transactionId));

        $this->setSelectedMethod($method);

        $this->mapper
             ->expects($this->any())
             ->method('create')
             ->will($this->returnValue($payment));

        $this->listener->process($this->createCartEvent());
    }

    /*
     * Private methods
     */

    private function createCartEvent()
    {
       $event = new CartEvent();

       $event->setTarget(new Order());

       return $event;
    }

    private function setSelectedMethod($method)
    {
        $this->selector->expects($this->any())
             ->method('getSelectedMethod')
             ->will($this->returnValue($method));
    }
}
