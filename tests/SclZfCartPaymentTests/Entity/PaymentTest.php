<?php

namespace SclZfCartPaymentTests\Entity;

use SclZfCartPayment\Entity\Payment;

/**
 * Unit tests from {@see Payment}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class PaymentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The instance to be tested.
     *
     * @var Payment
     */
    protected $entity;

    /**
     * Set up the instance to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->entity = new Payment();
    }

    /**
     * Test that the object is initialised properly.
     *
     * @covers SclZfCartPayment\Entity\Payment::__construct
     *
     * @return void
     */
    public function testInitialisation()
    {
        $this->assertInstanceOf(
            'SclZfCartPayment\Entity\PaymentInterface',
            $this->entity,
            'Payment must implement SclZfCartPayment\Entity\PaymentInterface'
        );

        $this->assertInstanceOf(
            'DateTime',
            $this->entity->getDate(),
            'getDate() did not return an instance of DateTime.'
        );
    }

    /**
     * testGettersAndSetters
     *
     * @dataProvider getSetProvider
     * @covers       SclZfCartPayment\Entity\Payment::getId
     * @covers       SclZfCartPayment\Entity\Payment::setId
     * @covers       SclZfCartPayment\Entity\Payment::getStatus
     * @covers       SclZfCartPayment\Entity\Payment::setStatus
     * @covers       SclZfCartPayment\Entity\Payment::getOrder
     * @covers       SclZfCartPayment\Entity\Payment::setOrder
     * @covers       SclZfCartPayment\Entity\Payment::getDate
     * @covers       SclZfCartPayment\Entity\Payment::setDate
     * @covers       SclZfCartPayment\Entity\Payment::getType
     * @covers       SclZfCartPayment\Entity\Payment::setType
     * @covers       SclZfCartPayment\Entity\Payment::getAmount
     * @covers       SclZfCartPayment\Entity\Payment::setAmount
     *
     * @param  string $member
     * @param  mixed  $value
     * @return void
     */
    public function testGettersAndSetters($member, $value)
    {
        $getter = 'get' . $member;
        $setter = 'set' . $member;

        $result = $this->entity->$setter($value);

        $this->assertEquals($this->entity, $result, $setter . '() did not return self.');

        $this->assertEquals(
            $value,
            $this->entity->$getter(),
            $getter . '() did not return the correct value.'
        );
    }

    /**
     * Data provider to test get/set methods.
     *
     * @return array
     */
    public function getSetProvider()
    {
        return array(
            array('id', 45),
            array('status', 'pending'),
            array('status', 'failed'),
            array('status', 'success'),
            array('order', $this->getMock('SclZfCart\Entity\OrderInterface')),
            array('date', new \DateTime()),
            array('type', 'PAYMENT'),
            array('amount', 22.33),
        );
    }

    /**
     * Test that setStatus() with an invalid status throws an exception.
     *
     * @expectedException SclZfCartPayment\Exception\InvalidArgumentException
     * @covers            SclZfCartPayment\Entity\Payment::setStatus
     *
     * @return void
     */
    public function testSetStatusWithBadStatus()
    {
        $this->entity->setStatus('wrong');
    }
}
