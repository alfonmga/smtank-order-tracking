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
