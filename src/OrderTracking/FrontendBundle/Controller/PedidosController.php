<?php

namespace OrderTracking\FrontendBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OrderTracking\FrontendBundle\Entity\Pedidos;

/**
 * Pedidos controller.
 *
 * @Route("/pedido")
 */
class PedidosController extends Controller
{

    /**
     * Finds and displays a Pedidos entity.
     *
     * @Route("/{id}", name="pedido_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OrderTrackingFrontendBundle:Pedidos')->findOneBy(array('codigoSeguimiento' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedidos entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
