<?php

namespace OrderTracking\BackendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use OrderTracking\BackendBundle\Entity\Pedidos;
use OrderTracking\BackendBundle\Entity\Historial;
use Symfony\Component\Console\Helper\ProgressBar;

class HistorialParentIdCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('migrate:data:historial')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $Pedidos = $em->getRepository('OrderTrackingBackendBundle:Pedidos')->findAll();

        foreach ($Pedidos as $Pedido) {

         if($historial_pedido = $em->getRepository('OrderTrackingBackendBundle:Historial')->findBy(array('idPedido' => $Pedido->getCodigoSeguimiento()))) {
             foreach ($historial_pedido as $historialpedido) {
                 $historialpedido->setParentId($Pedido);
                 $em->persist($historialpedido);
             }
         }
        }

        $em->flush();
    }
}
