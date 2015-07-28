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
    }

    /**
     * Actualizar estado de un pedido
     *
     * @Put("/pedido/{codigoSeguimiento}", name="_api_v1")
     */
    public function updatePedidoAction(Pedidos $pedido, Request $request)
    {

        $form = $this->createForm(new PedidosType(), $pedido);
        // El usuario no debe introducir los siguientes valores
        $form->remove('fechaInicio');
        $form->remove('fechaCompletado');
        $form->remove('nombreCliente');
        $form->remove('emailCliente');
        $form->remove('nombreProducto');
        $form->remove('precioProducto');
        $form->remove('codigoSeguimiento');
        $form->submit($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->get('TransactionalEmails')->pedidoUpdated($pedido->getEstadoPedido(), $pedido->getNombreCliente(),
                $pedido->getEmailCliente(), $pedido->getCodigoSeguimiento());

            return $pedido;
        }

        return array(
            'form' => $form,
        );

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