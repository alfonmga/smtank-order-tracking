<?php

namespace OrderTracking\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OrderTracking\BackendBundle\Entity\Pedidos;
use OrderTracking\FrontendBundle\Entity\Historial;
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
        if ($secretkey === 'yoursecretkeyhere') {

            $em = $this->getDoctrine()->getManager();

            $codigoSeguimiento = '';
            for ($i = 0; $i < 12; $i++) {
                $codigoSeguimiento .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('A'), ord('Z')));
            }

            $entity = $em->getRepository('OrderTrackingFrontendBundle:Pedidos')->findOneBy(array('codigoSeguimiento' => $codigoSeguimiento));

            if ($entity) {
                $codigoSeguimiento = '';
                for ($i = 0; $i < 12; $i++) {
                    $codigoSeguimiento .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('A'), ord('Z')));
                }
            }

            $pedido = new Pedidos();
            $pedido->setNombreCliente($nombre);
            $pedido->setEmailCliente($email);
            $pedido->setNombreProducto($nombreproducto);
            $pedido->setPrecioProducto($precio);
            $pedido->setEstadoPedido('pendiente');
            $pedido->setFechaInicio(date_create(date('Y-m-d H:i:s')));
            $pedido->setFechaCompletado(date_create(date('Y-m-d H:i:s')));
            $pedido->setCodigoSeguimiento($codigoSeguimiento);

            $historial = new Historial();
            $historial->setEstado('pendiente');
            $historial->setFecha(date_create(date('Y-m-d H:i:s')));
            $historial->setIdPedido($codigoSeguimiento);

            $em->persist($pedido);
            $em->persist($historial);
            $em->flush();

            $array = array (
                "estado" => "success",
                "codigoSeguimiento" => "$codigoSeguimiento"
            );

            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        else {
            $array = array (
                "estado" => "denied"
            );

            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }
}
