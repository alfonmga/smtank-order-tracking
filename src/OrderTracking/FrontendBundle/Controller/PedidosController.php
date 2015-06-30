<?php

namespace OrderTracking\FrontendBundle\Controller;


use OrderTracking\BackendBundle\Entity\Log;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OrderTracking\BackendBundle\Entity\Pedidos;

/**
 * Pedidos controller.
 *
 * @Route("/pedido", name="pedido_inicio")
 */
class PedidosController extends Controller
{

    /**
     * @Route("/", name="pedido_index")
     */
    public function inicioAction() {

    }

    /**
     * Finds and displays a Pedidos entity.
     *
     * @Route("/", name="pedido_search_form")
     * @Route("/{id}", name="pedido_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OrderTrackingBackendBundle:Pedidos')->findOneBy(array('codigoSeguimiento' => $id));

        if (!$entity) {

            return $this->render('OrderTrackingFrontendBundle:Frontend:404.html.twig');
        }

        if(!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $log = new Log();
            $log->setNombreCliente($entity->getNombreCliente());
            $log->setFechaCheck(date_create(date('Y-m-d H:i:s')));
            $log->setPedidoId($entity->getId());
            $em->persist($log);
            $em->flush($log);
        }
        
        $entity2 = $em->getRepository('OrderTrackingFrontendBundle:Historial')->findBy(array('idPedido' => $id), array( 'fecha' => 'DESC' ));

        return array(
            'entity'      => $entity,
            'entity2'     => $entity2,
        );
    }
}
