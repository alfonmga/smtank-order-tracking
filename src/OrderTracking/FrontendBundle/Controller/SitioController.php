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
                ->setSubject('Mensaje desde página de seguimiento - PEDIDO: ' . $request->request->get('id_pedido'))
                ->setFrom('contacto@smtank.com')
                ->setTo('contacto@smtank.com')
                ->setBody('Nombre: ' . $request->request->get('nombre_cliente') . 'Email: ' . $request->request->get('email_cliente') . ' Mensaje: ' .  $request->request->get('mensaje'));
            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('success', 'Hemos recibido correctamente tu mensaje :-)');

            return $this->redirect($referer);

        } else {
            return new RedirectResponse($this->generateUrl('order_tracking_frontend_homepage'));
        }
    }
}
