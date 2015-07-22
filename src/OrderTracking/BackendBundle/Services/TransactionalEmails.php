<?php

namespace OrderTracking\BackendBundle\Services;

class TransactionalEmails {

    private $mailer;

    public function __construct($mailer) {
        $this->mailer = $mailer;
    }

    public function newPedido($nombre, $email, $codigoSeguimiento) {

        $mensaje = \Swift_Message::newInstance()
            ->setSubject('Sigue el estado de tu pedido #' . $codigoSeguimiento . ' - SMTank.com')
            ->setFrom('contacto@smtank.com')
            ->setTo($email)
            ->setBody('Hola ' . $nombre . ',<br><br> Desde ahora puedes seguir el estado de tu pedido (ID: <b>' . $codigoSeguimiento . '</b>) en tiempo real. Entra en http://pedidos.smtank.com e introduce tu código de seguimiento. <br><br>Un saludo,<br>SMTank.com.', "text/html");
        $this->mailer->send($mensaje);

    }

    public function pedidoUpdated($estado, $nombre, $email, $codigoSeguimiento) {

        $mensaje = \Swift_Message::newInstance()
            ->setSubject('Pedido actualizado (' . $estado . ') - SMTank.com')
            ->setFrom('contacto@smtank.com')
            ->setTo($email)
            ->setBody('Hola ' . $nombre . ',<br><br> El estado de tu pedido (ID: <b>' . $codigoSeguimiento . '</b>) ha sido actualizado.<br>Entra en http://pedidos.smtank.com para ver más detalles sobre tu pedido. <br><br>Un saludo,<br>SMTank.com.', "text/html");
        $this->mailer->send($mensaje);

    }

}