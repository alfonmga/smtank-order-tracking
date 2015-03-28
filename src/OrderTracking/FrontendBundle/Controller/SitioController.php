<?php

namespace OrderTracking\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SitioController extends Controller
{
    public function indexAction()
    {
        return $this->render('OrderTrackingFrontendBundle:Frontend:index.html.twig');
    }

    public function pedidoAction($id)
    {
        return $this->render('OrderTrackingFrontendBundle:Frontend:pedido.html.twig', array('id' => $id));
    }

    public function buscarAction($id)
    {
        return new Response($id);
    }
}
