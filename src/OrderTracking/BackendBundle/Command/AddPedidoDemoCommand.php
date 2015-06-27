<?php

namespace OrderTracking\BackendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use OrderTracking\BackendBundle\Entity\Pedidos;
use OrderTracking\FrontendBundle\Entity\Historial;

class AddPedidoDemoCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pedido:add:demo')
            ->setDescription('Generador de pedidos demo')
            ->addArgument(
                'cantidad',
                InputArgument::OPTIONAL,
                'Cantidad de pedidos a generar'
            )

            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cantidad = $input->getArgument('cantidad');
        if(!$cantidad) {
            $helper = $this->getHelper('question');
            $question = new Question('Cantidad de pedidos a generar: ');
            $question->setValidator(function ($answer) {
                if (!is_numeric($answer)) {
                    throw new \RuntimeException(
                        'Debes de introducir un número.'
                    );
                } elseif (is_numeric($answer) && !$answer > 0) {
                    throw new \RuntimeException(
                        'La cantidad debe ser superior a 0.'
                    );
                }
                return $answer;
            });
            $cantidad = $helper->ask($input, $output, $question);
        }

        for ($x = 0; $x < $cantidad; $x++) {
            $output->writeln('Ronda '.$x);
            $em = $this->getContainer()->get('doctrine')->getManager();
            $Pedido = new Pedidos();
            $Pedido->setNombreCliente($this->getContainer()->get('DemoDataGenerator')->nombreCliente());
            $Pedido->setEmailCliente($this->getContainer()->get('DemoDataGenerator')->emailCliente());
            $estadoPedido = $this->getContainer()->get('demodatagenerator')->estadoPedido();
            $Pedido->setEstadoPedido($estadoPedido);
            $codigoSeguimientoPedido = $this->getContainer()->get('DemoDataGenerator')->codigoSeguimiento();
            $Pedido->setCodigoSeguimiento($codigoSeguimientoPedido);
            $Pedido->setNombreProducto($this->getContainer()->get('DemoDataGenerator')->nombreProducto());
            $Pedido->setPrecioProducto($this->getContainer()->get('DemoDataGenerator')->precioProducto());

            $fechaInicioPedido = $this->getContainer()->get('DemoDataGenerator')->fechaInicio();
            $fechaObject = new \DateTime();
            $fechaObject->setTimestamp($fechaInicioPedido);

            $Pedido->setFechaInicio($fechaObject);

            if($estadoPedido === 'pendiente')
            {
                $Historial = new Historial();
                $Historial->setEstado('pendiente');
                $Historial->setFecha($fechaObject);
                $Historial->setIdPedido($codigoSeguimientoPedido);

                $em->persist($Historial);

            } elseif ($estadoPedido === 'en progreso') {

                $Historial = new Historial();
                $Historial->setEstado('pendiente');
                $Historial->setFecha($fechaObject);
                $Historial->setIdPedido($codigoSeguimientoPedido);

                $Historial2 = new Historial();
                $Historial2->setEstado('en progreso');

                $Historial2->setFecha($this->getContainer()->get('demodatagenerator')->fechaRandSuperior($fechaObject->getTimestamp()));
                $Historial2->setIdPedido($codigoSeguimientoPedido);

                $em->persist($Historial);
                $em->persist($Historial2);

            } elseif ($estadoPedido === 'completado') {

                $Historial = new Historial();
                $Historial->setEstado('pendiente');
                $Historial->setFecha($fechaObject);
                $Historial->setIdPedido($codigoSeguimientoPedido);

                $Historial2 = new Historial();
                $Historial2->setEstado('en progreso');

                $Historial2->setFecha($tempdate1 = $this->getContainer()->get('demodatagenerator')->fechaRandSuperior($fechaObject->getTimestamp()));
                $Historial2->setIdPedido($codigoSeguimientoPedido);

                $Historial3 = new Historial();
                $Historial3->setEstado('completado');
                $Historial3->setFecha($tempdate2 = $this->getContainer()->get('demodatagenerator')->fechaRandSuperior($tempdate1->getTimestamp()));
                $Historial3->setIdPedido($codigoSeguimientoPedido);

                $Pedido->setFechaCompletado($tempdate2);

                $em->persist($Historial);
                $em->persist($Historial2);
                $em->persist($Historial3);

            } elseif ($estadoPedido === 'cancelado') {

                $Historial = new Historial();
                $Historial->setEstado('pendiente');
                $Historial->setFecha($fechaObject);
                $Historial->setIdPedido($codigoSeguimientoPedido);

                $Historial2 = new Historial();
                $Historial2->setEstado('en progreso');
                $Historial2->setFecha($tempdate1 = $this->getContainer()->get('demodatagenerator')->fechaRandSuperior($fechaObject->getTimestamp()));
                $Historial2->setIdPedido($codigoSeguimientoPedido);

                $Historial3 = new Historial();
                $Historial3->setEstado('cancelado');
                $Historial3->setFecha($this->getContainer()->get('demodatagenerator')->fechaRandSuperior($tempdate1->getTimestamp()));
                $Historial3->setIdPedido($codigoSeguimientoPedido);

                $em->persist($Historial);
                $em->persist($Historial2);
                $em->persist($Historial3);
            }

            $em->persist($Pedido);
            $em->flush();
        }
    }
}
