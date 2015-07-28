<?php

namespace OrderTracking\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pedidos
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("codigoSeguimiento")
 */
class Pedidos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="Log", mappedBy="pedido_id", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="datetime")
     *
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_completado", type="datetime", nullable=true)
     */
    private $fechaCompletado;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_cliente", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nombreCliente;

    /**
     * @var string
     *
     * @ORM\Column(name="email_cliente", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $emailCliente;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_producto", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nombreProducto;

    /**
     * @var string
     *
     * @ORM\Column(name="precio_producto", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $precioProducto;

    /**
     * @var string
     *
     * @ORM\Column(name="estado_pedido", type="string", length=255)
     */
    private $estadoPedido;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_seguimiento", type="string", length=255, unique=true)
     *
     */
    private $codigoSeguimiento;

    public function __construct()
    {
        $this->id = new ArrayCollection();
        $this->fechaInicio = new \DateTime();
        $this->estadoPedido = 'pendiente';
        for ($i = 0; $i < 12; $i++) {
            $this->codigoSeguimiento .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('A'), ord('Z')));
        }
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return Pedidos
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaCompletado
     *
     * @param \DateTime $fechaCompletado
     * @return Pedidos
     */
    public function setFechaCompletado($fechaCompletado)
    {
        $this->fechaCompletado = $fechaCompletado;

        return $this;
    }

    /**
     * Get fechaCompletado
     *
     * @return \DateTime 
     */
    public function getFechaCompletado()
    {
        return $this->fechaCompletado;
    }

    /**
     * Set nombreCliente
     *
     * @param string $nombreCliente
     * @return Pedidos
     */
    public function setNombreCliente($nombreCliente)
    {
        $this->nombreCliente = $nombreCliente;

        return $this;
    }

    /**
     * Get nombreCliente
     *
     * @return string 
     */
    public function getNombreCliente()
    {
        return $this->nombreCliente;
    }

    /**
     * Set emailCliente
     *
     * @param string $emailCliente
     * @return Pedidos
     */
    public function setEmailCliente($emailCliente)
    {
        $this->emailCliente = $emailCliente;

        return $this;
    }

    /**
     * Get emailCliente
     *
     * @return string 
     */
    public function getEmailCliente()
    {
        return $this->emailCliente;
    }

    /**
     * Set nombreProducto
     *
     * @param string $nombreProducto
     * @return Pedidos
     */
    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = $nombreProducto;

        return $this;
    }

    /**
     * Get nombreProducto
     *
     * @return string 
     */
    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    /**
     * Set precioProducto
     *
     * @param string $precioProducto
     * @return Pedidos
     */
    public function setPrecioProducto($precioProducto)
    {
        $this->precioProducto = $precioProducto;

        return $this;
    }

    /**
     * Get precioProducto
     *
     * @return string
     */
    public function getPrecioProducto()
    {
        return $this->precioProducto;
    }

    /**
     * Set estadoPedido
     *
     * @param string $estadoPedido
     * @return Pedidos
     */
    public function setEstadoPedido($estadoPedido)
    {
        $this->estadoPedido = $estadoPedido;

        return $this;
    }

    /**
     * Get estadoPedido
     *
     * @return string 
     */
    public function getEstadoPedido()
    {
        return $this->estadoPedido;
    }

    /**
     * Set codigoSeguimiento
     *
     * @param string $codigoSeguimiento
     * @return Pedidos
     */
    public function setCodigoSeguimiento($codigoSeguimiento)
    {
        $this->codigoSeguimiento = $codigoSeguimiento;

        return $this;
    }

    /**
     * Get codigoSeguimiento
     *
     * @return string 
     */
    public function getCodigoSeguimiento()
    {
        return $this->codigoSeguimiento;
    }
}
