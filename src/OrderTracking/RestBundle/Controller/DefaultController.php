<?php

namespace OrderTracking\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Config\Definition\Exception\Exception,
    Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

use OrderTracking\BackendBundle\Entity\Pedidos,
    OrderTracking\BackendBundle\Form\PedidosType,
    OrderTracking\BackendBundle\Entity\Historial;

use FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations as Rest,
    FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Controller\Annotations\Put,
    FOS\RestBundle\Controller\Annotations\Delete;

class DefaultController extends FOSRestController
{
    /**
     * Extraer todos los pedidos
     *
     * @Get("/pedidos", name="_api_v1")
     */
    public function getPedidosAction()
    {
        $pedidos = $this->getDoctrine()->getRepository('OrderTrackingBackendBundle:Pedidos')->findAll();

        return array('pedidos' => $pedidos);
    }

    /**
     * Extraer pedido
     *
     * @Get("/pedido/{codigoSeguimiento}", name="_api_v1")
     */
    public function getPedidoAction(Pedidos $pedido)
    {
        return array('pedido' => $pedido);
    }

    /**
     * Crear pedido nuevo
     *
     * @Post("/pedido", name="_api_v1")
     */
    public function newPedidoAction(Request $request)
    {
        $pedido = new Pedidos();
        $form = $this->createForm(new PedidosType(), $pedido);
        // El usuario no debe introducir los siguientes valores
        $form->remove('fechaInicio');
        $form->remove('fechaCompletado');
        $form->remove('estadoPedido');
        $form->remove('codigoSeguimiento');
        $form->submit($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($pedido);
            $em->flush();

            return $pedido;
        }

        return array(
            'form' => $form,
        );

        /*$nombreCliente = $this->get('request')->get('nombre_cliente');
        $emailCliente = $this->get('request')->get('email_cliente');
        $nombreProducto = $this->get('request')->get('nombre_producto');
        $precioProducto = $this->get('request')->get('precio_producto');

        if(!$nombreCliente || !$emailCliente || !$nombreProducto || !$precioProducto) {
            throw new NotAcceptableHttpException;
        }

        $em = $this->getDoctrine()->getManager();

        $codigoSeguimiento = '';
        for ($i = 0; $i < 12; $i++) {
            $codigoSeguimiento .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('A'), ord('Z')));
        }

        $pedido = new Pedidos();
        $pedido->setNombreCliente($nombreCliente);
        $pedido->setEmailCliente($emailCliente);
        $pedido->setNombreProducto($nombreProducto);
        $pedido->setPrecioProducto($precioProducto);
        $pedido->setEstadoPedido('pendiente');
        $pedido->setFechaInicio(date_create(date('Y-m-d H:i:s')));
        $pedido->setCodigoSeguimiento($codigoSeguimiento);
        $em->persist($pedido);
        $em->flush();

        $historial = new Historial();
        $historial->setEstado('pendiente');
        $historial->setFecha(date_create(date('Y-m-d H:i:s')));
        $historial->setIdPedido($codigoSeguimiento);
        $historial->setParentId($pedido);

        $em->persist($historial);
        $em->flush();

        $this->get('TransactionalEmails')->newPedido($pedido->getNombreCliente(), $pedido->getEmailCliente(), $codigoSeguimiento);

        return array (
            'estado' => 'success',
            'codigoSeguimiento' => $pedido->getCodigoSeguimiento()
        );*/
    }

    /**
     * Eliminar pedido
     *
     * @Delete("/pedido/borrar/{codigoSeguimiento}", name="_api_v1")
     */
    public function removePedidoAction(Pedidos $pedido)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($pedido);
        $em->flush();

        return array('estado' => 'eliminado');
    }

}