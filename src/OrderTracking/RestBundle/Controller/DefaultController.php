<?php

namespace OrderTracking\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OrderTrackingRestBundle:Default:index.html.twig', array('name' => $name));
    }
}
