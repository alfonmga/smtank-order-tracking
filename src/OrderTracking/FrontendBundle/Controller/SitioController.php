<?php

namespace OrderTracking\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


class SitioController extends Controller
{
    public function indexAction()
    {
        return $this->render('OrderTrackingFrontendBundle:Frontend:index.html.twig');
    }

    public function contactarAction()
    {
        $request = $this->getRequest();

        if ($request->isMethod('POST')) {

            $referer = $request->headers->get('referer');
            $message = \Swift_Message::newInstance()
                ->setSubject('Contact enquiry from symblog')
                ->setFrom('contacto@smtank.com')
                ->setTo('alfonsoimbusiness@gmail.com')
                ->setBody('probando..');
            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('success', 'Hemos recibido correctamente tu mensaje :-)');

            return $this->redirect($referer);

        } else {
            return new RedirectResponse($this->generateUrl('order_tracking_frontend_homepage'));
        }
    }
}
