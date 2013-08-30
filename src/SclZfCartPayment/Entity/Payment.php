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
    protected $id;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var DoctrineOrderInterface
     */
    protected $order;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var float
     */
    protected $amount;

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
     * @return self
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
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
     * @return self
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;

        return $this;
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
     * @return self
     */
    public function setStatus($status)
    {
        if (!in_array(
            $status,
            array(self::STATUS_PENDING, self::STATUS_FAILED, self::STATUS_SUCCESS)
        )) {
            throw new InvalidArgumentException(
                sprintf('"%s", "%s" or "%s"', self::STATUS_PENDING, self::STATUS_FAILED, self::STATUS_SUCCESS),
                $status,
                __METHOD__,
                __LINE__
            );
        }

        $this->status = (string) $status;

        return $this;
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
     * @return self
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;

        return $this;
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
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
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
     * @return self
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }
}
