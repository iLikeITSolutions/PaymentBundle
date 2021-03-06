<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ilis\Bundle\PaymentBundle\Exception\Exception;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ilis_payment_transactions")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="method_type", type="string")
 * @ORM\DiscriminatorMap({
        "cc"      = "Ilis\Bundle\PaymentBundle\Entity\Transaction\CreditCard",
 *      "paypal"  = "Ilis\Bundle\PaymentBundle\Entity\Transaction\Paypal",
   })
 * @ORM\HasLifecycleCallbacks()
 */
class Transaction
{

    const STATUS_PENDING = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_ERROR   = 2;

    const IDENTIFIER_SEPARATOR = '-';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", nullable=true)
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=11, scale=2)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $method;

    /**
     * @ORM\PrePersist
     */
    public function setDefaults()
    {
        if (null === $this->status)
            $this->status = self::STATUS_PENDING;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Transaction
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set method
     *
     * @param Method $method
     * @return Transaction
     */
    public function setMethod(Method $method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return Method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Transaction
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set Identifier
     *
     * @param string $suffix
     * @return Transaction
     */
    public function setIdentifier($suffix = null)
    {
        if (null === $this->id)
           throw new Exception('Transaction identifier can be set only if the Entity has the id set.');;

        if (!empty($suffix))
            $this->identifier = $this->getId() . self::IDENTIFIER_SEPARATOR . $suffix;
        else
            $this->identifier = $this->getId();

        $this->identifier = sprintf('%012s', $this->identifier);

        return $this;


    }

    /**
     * Get Identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }


}