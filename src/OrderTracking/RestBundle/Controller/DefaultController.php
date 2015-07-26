<?php

namespace OrderTracking\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations as Rest,
    FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Controller\Annotations\Put,
    FOS\RestBundle\Controller\Annotations\Delete;

class DefaultController extends FOSRestController
{
    /**
     *@Get("/pedidos", name="_api_v1")
     */
    public function getPedidosAction()
    {
        $pedidos = $this->getDoctrine()->getRepository('OrderTrackingBackendBundle:Pedidos')->findAll();

        return array(
            'pedidos' => $pedidos,
        );
    }
}
