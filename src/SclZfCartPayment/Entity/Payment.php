<?php

namespace SclZfCartPayment\Entity;

use DateTime;
use SclZfCart\Entity\OrderInterface;
use SclZfCartPayment\Exception\InvalidArgumentException;

/**
 * Payment entity.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Payment implements PaymentInterface
{
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
     * @var DoctrineOrderInterface
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

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     *
     * @param  int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the value of transactionId.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Sets the value of transactionId.
     *
     * @param  string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = (string) $transactionId;
    }

    /**
     * {@inheritDoc}
     *
     * @return OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * {@inheritDoc}
     *
     * @param  OrderInterface $order
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritDoc}
     *
     * @param  string $status
     */
    public function setStatus($status)
    {
        if (!in_array($status, self::$validStates)) {
            throw InvalidArgumentException::invalidValue(self::$validStates, $status);
        }

        $this->status = (string) $status;
    }

    /**
     * Get the date the payment was made.
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the date the payment was made.
     *
     * @param  DateTime $date
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * Get the type of transaction.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the type of transaction.
     *
     * @param  string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get the amount the payment was for.
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set the amount the payment was for.
     *
     * @param  float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
}
