<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Indent
 *
 * @ORM\Table(name="indent")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IndentRepository")
 */
class Indent
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_time", type="datetime")
     */
    private $orderTime;

    /**
     * @var int
     *
     * @ORM\Column(name="product_id", type="integer")
     */
    private $productId;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", fetch="EAGER")
     */
    private $product;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string")
     */
    private $user;

    /**
     * @var bool
     *
     * @ORM\Column(name="sent", type="boolean")
     */
    private $sent = false;

    /**
     * @ORM\Column(name="sent_at", type="datetime", columnDefinition="DATETIME on update CURRENT_TIMESTAMP")
     */
    private $sentAt;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orderTime
     *
     * @param \DateTime $orderTime
     *
     * @return Indent
     */
    public function setOrderTime($orderTime)
    {
        $this->orderTime = $orderTime;

        return $this;
    }

    /**
     * Get orderTime
     *
     * @return \DateTime
     */
    public function getOrderTime()
    {
        return $this->orderTime;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return Indent
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Indent
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return Indent
     */
    public function setuser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getuser()
    {
        return $this->user;
    }

    /**
     * Set sent
     *
     * @param bool $value
     *
     * @return Indent
     */
    public function setSent($value)
    {
        $this->sent = $value;

        return $this;
    }

    /**
     * Get sent
     *
     * @return bool
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Get sentAt
     *
     * @return datetime
     */
    public function getsentAt()
    {
        return $this->sentAt;
    }
}
