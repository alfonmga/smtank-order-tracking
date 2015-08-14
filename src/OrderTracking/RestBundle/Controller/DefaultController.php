<?php

namespace OrderTracking\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Config\Definition\Exception\Exception,
    Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException,
    Symfony\Component\HttpFoundation\Response;

use OrderTracking\BackendBundle\Entity\Pedidos,
    OrderTracking\BackendBundle\Form\PedidosType,
    OrderTracking\BackendBundle\Entity\Historial;

use FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations as Rest,
    FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Controller\Annotations\Put,
    FOS\RestBundle\Controller\Annotations\Delete;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class DefaultController extends FOSRestController
{
    /**
     * Extraer todos los pedidos
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Ver todos los pedidos",
     *   statusCodes = {
     *   }
     * )
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
     * @ApiDoc(
     *   resource = true,
     *   description = "Ver pedido",
     *   requirements={
     *      {
     *          "name"="Código de seguimiento",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     * },
     *   statusCodes = {
     *   }
     * )
     * @Get("/pedidos/{codigoSeguimiento}", name="_api_v1")
     */
    public function getPedidoAction(Pedidos $pedido)
    {
        return array('pedido' => $pedido);
    }

    /**
     * Crear pedido nuevo
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Crear pedido",
     *   input="OrderTracking\BackendBundle\Form\PedidosType",
     *   statusCodes = {
     *     201 = "Returned when successful"
     *   }
     * )
     * @Post("/pedidos", name="_api_v1")
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

            $response = new Response();
            $response->setStatusCode(201);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Location',
                $this->generateUrl(
                    'get_pedido_api_v1', array('codigoSeguimiento' => $pedido->getCodigoSeguimiento()),
                    true // absolute
                )
            );
            $serializer = $this->container->get('serializer');
            $serializedEntity = $serializer->serialize($pedido, 'json');
            $response->setContent($serializedEntity);

            return $response;
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Actualizar estado de un pedido
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Actualizar",
     *   requirements={
     *      {
     *          "name"="Código de seguimiento",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     * },
     *   statusCodes = {
     *   }
     * )
     * @Put("/pedidos/{codigoSeguimiento}", name="_api_v1")
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

            return $pedido;
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Eliminar pedido
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Eliminar pedido",
     *   requirements={
     *      {
     *          "name"="Código de seguimiento",
     *          "dataType"="integer",
     *          "requirement"="\d+"
     *      }
     * },
     *   statusCodes = {
     *   }
     * )
     * @Delete("/pedidos/{codigoSeguimiento}", name="_api_v1")
     */
    public function removePedidoAction(Pedidos $pedido)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($pedido);
        $em->flush();

        return array('response' => 'eliminado');
    }
}