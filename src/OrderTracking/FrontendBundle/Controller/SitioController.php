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
}
