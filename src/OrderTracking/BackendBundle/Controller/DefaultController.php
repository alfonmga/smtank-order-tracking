<?php

namespace OrderTracking\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OrderTracking\BackendBundle\Entity\Pedidos;
use OrderTracking\BackendBundle\Entity\Historial;
use Symfony\Component\HttpFoundation\Response;

use Uecode\Bundle\ApiKeyBundle\Util;

class DefaultController extends Controller
{
    /**
     * @Route("/backend/logs", name="backend_logs")
     */
    public function logsAction()
    {

        $em = $this->getDoctrine()->getManager();
        $logs = $em->getRepository('OrderTrackingBackendBundle:Log')->findBy(array(), array('fechaCheck'=>'desc'));

        return $this->render('@OrderTrackingBackend/Pedidos/logs.html.twig', array(
            'logs' => $logs
        ));
    }

    /**
     * @Route("/backend/api", name="backend_api_index")
     */
    public function apiAction()
    {
        $apikey = $this->getDoctrine()->getManager()->getRepository('OrderTrackingBackendBundle:User')->find(1)
            ->getApiKey();
        return $this->render('@OrderTrackingBackend/Pedidos/api_index.html.twig', array(
            'apikey' => $apikey
        ));
    }

    /**
     * @Route("/backend/api/apikey/reset", name="backend_api_reset")
     * @Method("POST")
     */
    public function resetApiAction()
    {
        $apigenerador = new Util\ApiKeyGenerator();
        $newApiKey = $apigenerador->generateApiKey();
        $usuario = $this->getDoctrine()->getRepository('OrderTrackingBackendBundle:User')->find(1);
        $usuario->setApiKey($newApiKey);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('backend_api_index');
    }
}
