<?php

namespace SclZfCartPayment\Entity;

use DateTime;
use SclZfCart\Entity\OrderInterface;

/**
 * Interface for a payment entity
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface PaymentInterface
{
    const STATUS_PENDING = 'pending';
    const STATUS_FAILED  = 'failed';
    const STATUS_SUCCESS = 'success';

    /**
     * Get the payment ID
     *
     * @return int
     */
    public function getId();

    /**
     * Set the payment ID.
     *
     * @param  int $id
     */
    public function setId($id);

    /**
     * Get the user/gateway defined transaction ID.
     *
     * @return string
     */
    public function getTransactionId();

    /**
     * Set the user/gateway defined transaction ID.
     *
     * @param  string $id
     */
    public function setTransactionId($id);

    /**
     * Return the order this payment was for.
     *
     * @return OrderInterface
     */
    public function getOrder();

    /**
     * Set the order this payment is for.
     *
     * @param  OrderInterface $order
     */
    public function setOrder(OrderInterface $order);

    /**
     * Returns the status of the payment.
     *
     * @return string
     */
    public function getStatus();

    /**
     * Sets the status fo the payment.
     *
     * @param  string $status
     */
    public function setStatus($status);

    /**
     * Get the date the payment was made.
     *
     * @return DateTime
     */
    public function getDate();

    /**
     * Set the date the payment was made.
     *
     * @param  DateTime $date
     */
    public function setDate(DateTime $date);

    /**
     * Get the type of transaction.
     *
     * @return string
     */
    public function getType();

    /**
     * Set the type of transaction.
     *
     * @param  string $type
     */
    public function setType($type);

    /**
     * Get the amount the payment was for.
     *
     * @return float
     */
    public function getAmount();

    /**
     * Set the amount the payment was for.
     *
     * @param  float $amount
     */
    public function setAmount($amount);
}
