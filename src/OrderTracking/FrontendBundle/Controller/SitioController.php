<?php

namespace OrderTracking\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class SitioController extends Controller
{
    /**
     * Inicio controller.
     *
     * @Route("/", name="order_tracking_frontend_homepage")
     */
    public function indexAction()
    {
        return $this->render('OrderTrackingFrontendBundle:Frontend:index.html.twig');
    }

    /**
     * Contact controller.
     *
     * @Route("/contactar", name="order_tracking_frontend_sendemail")
     */
    public function contactarAction(Request $request)
    {
        $referer = $request->headers->get('referer');

        if ($request->isMethod('POST') && $this->isCsrfTokenValid('authenticate', $request->request->get('_csrf_token'))) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Mensaje desde página de seguimiento - PEDIDO: ' . $request->request->get('id_pedido'))
                ->setFrom('contacto@smtank.com')
                ->setTo('contacto@smtank.com')
                ->setBody('Nombre: ' . $request->request->get('nombre_cliente') . 'Email: ' . $request->request->get('email_cliente') . ' Mensaje: ' .  $request->request->get('mensaje'));
            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('success', 'Hemos recibido correctamente tu mensaje :-)');
            return $this->redirect($referer);
        } else {
            return $this->redirect($referer);
        }
    }
}
