<?php

namespace SclZfCartPayment\Entity;

use DateTime;
use SCL\Currency\Money;
use SCL\Currency\MoneyFactory;
use SclZfCartPayment\Exception\InvalidArgumentException;
use SclZfCart\Entity\MoneyFactoryAware;
use SclZfCart\Entity\Order;

class Payment implements MoneyFactoryAware
{

    const STATUS_PENDING = 'pending';
    const STATUS_FAILED  = 'failed';
    const STATUS_SUCCESS = 'success';

    /**
     * @var int
     */
    private $id;

    /**
     * A user/gateway defined transaction ID.
     *
     * @var string
     */
    private $transactionId;

    /**
     * @var string
     */
    private $status;

    /**
     * @var DoctrineOrder
     */
    private $order;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $type;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var MoneyFactory
     */
    private $moneyFactory;

    /**
     * List of allowed states.
     */
    private static $validStates = [
        self::STATUS_PENDING,
        self::STATUS_FAILED,
        self::STATUS_SUCCESS
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new DateTime();
    }

    public function setMoneyFactory(MoneyFactory $moneyFactory)
    {
        $this->moneyFactory = $moneyFactory;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = (string) $transactionId;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        if (!in_array($status, self::$validStates)) {
            throw InvalidArgumentException::invalidValue(self::$validStates, $status);
        }

        $this->status = (string) $status;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Money
     */
    public function getAmount()
    {
        return $this->moneyFactory->createFromUnits($this->amount);
    }

    /**
     * @param Money $amount
     */
    public function setAmount(Money $amount)
    {
        $this->amount = $amount->getUnits();
    }
}
