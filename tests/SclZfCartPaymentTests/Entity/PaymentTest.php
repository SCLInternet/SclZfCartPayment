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
     * @return void
     */
    public function testInitialisation()
    {
        $this->assertInstanceOf(
            'SclZfCartPayment\Entity\Payment',
            $this->entity,
            'Payment must implement SclZfCartPayment\Entity\Payment'
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
            array('order', $this->getMock('SclZfCart\Entity\Order')),
            array('date', new \DateTime()),
            array('type', 'PAYMENT'),
            array('amount', 22.33),
        );
    }

    public function test_setStatus_throws_for_bad_status()
    {
        $this->setExpectedException(
            'SclZfCartPayment\Exception\InvalidArgumentException',
            'Expected one of "pending|failed|success"; got "bad-status".'
        );

        $this->entity->setStatus('bad-status');
    }
}
