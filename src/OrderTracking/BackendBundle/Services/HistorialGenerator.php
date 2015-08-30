<?php

namespace OrderTracking\BackendBundle\Services;

use OrderTracking\BackendBundle\Entity\Historial;

/**
 * Generador de historiales.
 * Service utilizado por el Command'pedido:add:demo'.
 *
 * Class HistorialGenerator
 * @package OrderTracking\BackendBundle\Services
 */
class HistorialGenerator
{
    private $em;
    private $container;

    public function __construct($em, $container) {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * Obtiene como parameter un objeto de Pedido y dependiendo del estado del pedido crea los historiales y asigna al
     * pedido una fecha de inicio/completado concreta.
     */
    public function generate($pedido) {
        switch($pedido->getEstadoPedido()) {
            case 'en progreso':
                $historial = new Historial();
                $historial->setParentId($pedido);
                $historial->setIdPedido($pedido->getCodigoSeguimiento());
                $historial->setEstado('pendiente');
                $historial->setFecha($date_pendiente = $this->container->get('DemoDataGenerator')->fechaRandInferior($pedido
                    ->getFechaInicio()->getTimestamp()));

                $pedido->setFechaInicio($date_pendiente);

                $this->em->persist($historial);
                $this->em->persist($pedido);
                break;
            case 'completado':
                $historial = new Historial();
                $historial->setParentId($pedido);
                $historial->setIdPedido($pedido->getCodigoSeguimiento());
                $historial->setEstado('pendiente');
                $historial->setFecha($date_pendiente = $this->container->get('DemoDataGenerator')->fechaRandInferior($pedido
                    ->getFechaInicio()->getTimestamp()));

                $pedido->setFechaInicio($date_pendiente);

                $historial2 = new Historial();
                $historial2->setParentId($pedido);
                $historial2->setIdPedido($pedido->getCodigoSeguimiento());
                $historial2->setEstado('en progreso');
                $historial2->setFecha($date_enprogreso = $this->container->get('DemoDataGenerator')->fechaRandSuperior($pedido->getFechaInicio()->getTimestamp()));

                $historial3 = $this->em->getRepository('OrderTrackingBackendBundle:Historial')->findOneBy(
                    array('parentId' => $pedido->getId(), 'estado' => 'completado'));
                $historial3->setFecha($date_completado = $this->container->get('DemoDataGenerator')
                    ->fechaRandSuperior($date_enprogreso->getTimestamp()));

                $pedido->setFechaCompletado($date_completado);

                $this->em->persist($historial);
                $this->em->persist($historial2);
                $this->em->persist($historial3);
                $this->em->persist($pedido);
                break;
            case 'cancelado':
                $historial = new Historial();
                $historial->setEstado('pendiente');
                $historial->setFecha($date_pendiente = $this->container->get('demodatagenerator')->fechaRandInferior($pedido->getFechaInicio()->getTimestamp()));
                $historial->setIdPedido($pedido->getCodigoSeguimiento());
                $historial->setParentId($pedido);

                $pedido->setFechaInicio($date_pendiente);

                $historial2 = new Historial();
                $historial2->setEstado('en progreso');
                $historial2->setFecha($date_enprogreso = $this->container->get('demodatagenerator')->fechaRandSuperior($pedido->getFechaInicio()->getTimestamp()));
                $historial2->setIdPedido($pedido->getCodigoSeguimiento());
                $historial2->setParentId($pedido);

                $historial3 = $this->em->getRepository('OrderTrackingBackendBundle:Historial')->findOneBy(
                    array('parentId' => $pedido->getId(), 'estado' => 'cancelado'));
                $historial3->setFecha($date_cancelado = $this->container->get('DemoDataGenerator')
                    ->fechaRandSuperior($date_enprogreso->getTimestamp()));

                $this->em->persist($pedido);
                $this->em->persist($historial);
                $this->em->persist($historial2);
                break;
                break;
        }
    }
}