<?php

namespace SclZfCartPaymentTests\Entity;

use SclZfCartPayment\Entity\DoctrinePayment;

/**
 * Unit tests from {@see DoctrinePayment}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class DoctrinePaymentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The instance to be tested.
     *
     * @var DoctrinePayment
     */
    protected $entity;

    /**
     * Set up the instance to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->entity = new DoctrinePayment();
    }

    /**
     * Test that the object is initialised properly.
     *
     * @covers SclZfCartPayment\Entity\DoctrinePayment::__construct
     *
     * @return void
     */
    public function testInitialisation()
    {
        $this->assertInstanceOf(
            'SclZfCartPayment\Entity\PaymentInterface',
            $this->entity,
            'DoctrinePayment must implement SclZfCartPayment\Entity\PaymentInterface'
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
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::getId
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::setId
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::getStatus
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::setStatus
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::getOrder
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::setOrder
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::getDate
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::setDate
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::getType
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::setType
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::getAmount
     * @covers       SclZfCartPayment\Entity\DoctrinePayment::setAmount
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
     * @covers            SclZfCartPayment\Entity\DoctrinePayment::setStatus
     *
     * @return void
     */
    public function testSetStatusWithBadStatus()
    {
        $this->entity->setStatus('wrong');
    }
}
