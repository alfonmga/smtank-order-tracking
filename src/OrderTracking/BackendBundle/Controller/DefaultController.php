<?php

namespace OrderTracking\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OrderTracking\BackendBundle\Entity\Pedidos;
use OrderTracking\BackendBundle\Entity\Historial;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * Simple backend API & crear pedidos nuevos.
     *
     * @Route("api/crear/{nombre}/{email}/{nombreproducto}/{precio}/{secretkey}", name="backend_api")
     * @Method("POST")
     */
    public function apiAction($nombre, $email, $nombreproducto, $precio, $secretkey)
    {

        if ($secretkey === $this->container->getParameter('apikey')) {
            $em = $this->getDoctrine()->getManager();

            $codigoSeguimiento = '';
            for ($i = 0; $i < 12; $i++) {
                $codigoSeguimiento .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('A'), ord('Z')));
            }

            $pedido = new Pedidos();
            $pedido->setNombreCliente($nombre);
            $pedido->setEmailCliente($email);
            $pedido->setNombreProducto($nombreproducto);
            $pedido->setPrecioProducto($precio);
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

            $arrayResponse = array (
                'estado' => 'success',
                'codigoSeguimiento' => $pedido->getCodigoSeguimiento()
            );

            $response = new Response(json_encode($arrayResponse));
            $response->headers->set('Content-Type', 'application/json');

            $this->get('TransactionalEmails')->newPedido($pedido->getNombreCliente(), $pedido->getEmailCliente(), $codigoSeguimiento);

            return $response;
        }
        else {
            $arrayResponse = array (
                'estado' => 'access denied'
            );

            $response = new Response(json_encode($arrayResponse));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/backend/logs", name="backend_logs")
     */
    public function logsAction() {

        $em = $this->getDoctrine()->getManager();
        $logs = $em->getRepository('OrderTrackingBackendBundle:Log')->findBy(array(), array('fechaCheck'=>'desc'));

        return $this->render('@OrderTrackingBackend/Pedidos/logs.html.twig', array(
            'logs' => $logs
        ));
    }
}
