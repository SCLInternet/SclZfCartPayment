<?php

namespace SclZfCartPaymentTests\Mapper;

use SclZfCartPayment\Mapper\DoctrinePaymentMapper;
use SCL\Currency\MoneyFactory;
use SCL\Currency\Money;

class DoctrinePaymentMapperTest extends \PHPUnit_Framework_TestCase
{
    private $mapper;

    protected function setUp()
    {
        $this->mapper = new DoctrinePaymentMapper(
            $this->getMockBuilder('Doctrine\ORM\EntityManager')
                 ->disableOriginalConstructor()
                 ->getMock(),
            $this->getMockBuilder('SclZfGenericMapper\Doctrine\FlushLock')
                 ->disableOriginalConstructor()
                 ->getMock(),
            MoneyFactory::createDefaultInstance()
        );
    }

    public function test_create_allows_entity_to_return_money_objects()
    {
        $payment = $this->mapper->create();

        $this->assertInstanceOf(
            'SCL\Currency\Money',
            $payment->getAmount()
        );
    }
}
