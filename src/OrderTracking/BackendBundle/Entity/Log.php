<?php

namespace OrderTracking\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Log
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Pedidos", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="pedido_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $pedido;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_check", type="datetime")
     */
    private $fechaCheck;


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
     * Set pedido
     *
     * @param integer $pedido
     * @return Log
     */
    public function setPedido($pedido)
    {
        $this->pedido = $pedido;

        return $this;
    }

    /**
     * Get pedido
     *
     * @return integer 
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * Set fechaCheck
     *
     * @param \DateTime $fechaCheck
     * @return Log
     */
    public function setFechaCheck($fechaCheck)
    {
        $this->fechaCheck = $fechaCheck;

        return $this;
    }

    /**
     * Get fechaCheck
     *
     * @return \DateTime 
     */
    public function getFechaCheck()
    {
        return $this->fechaCheck;
    }
}
