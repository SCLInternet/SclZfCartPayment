<?php
namespace SclZfCartPayment\Service;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-02-16 at 00:29:05.
 */
class MethodSelectorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MethodSelectorFactory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new MethodSelectorFactory;
    }

    /**
     * @covers SclZfCartPayment\Service\MethodSelectorFactory::createService
     */
    public function testCreateService()
    {
        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $serviceLocator->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('SclZfCartPayment\MethodFetcher'))
            ->will($this->returnValue($this->getMock('SclZfCartPayment\Method\MethodFetcherInterface')));

        $session = $this->getMockBuilder('Zend\Session\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceLocator->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('SclZfCartPayment\Session'))
            ->will($this->returnValue($session));

        $selector = $this->object->createService($serviceLocator);

        $this->assertInstanceOf('SclZfCartPayment\Method\MethodSelector', $selector);
    }
}
