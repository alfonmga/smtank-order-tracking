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

class AddPedidoDemoCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pedidos:add:demo')
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

        $progress = new ProgressBar($output, $cantidad);
        $progress->start();
        for ($x = 0; $x < $cantidad; $x++) {
            $em = $this->getContainer()->get('doctrine')->getManager();

            $Pedido = new Pedidos();
            $Pedido->setNombreCliente($this->getContainer()->get('DemoDataGenerator')->nombreCliente());
            $Pedido->setEmailCliente($this->getContainer()->get('DemoDataGenerator')->emailCliente());
            $Pedido->setEstadoPedido($this->getContainer()->get('DemoDataGenerator')->estadoPedido());
            $Pedido->setCodigoSeguimiento($this->getContainer()->get('trackcodegenerator')->generate());
            $Pedido->setNombreProducto($this->getContainer()->get('DemoDataGenerator')->nombreProducto());
            $Pedido->setPrecioProducto($this->getContainer()->get('DemoDataGenerator')->precioProducto());

            $fechaObject = new \DateTime();
            $fechaObject->setTimestamp($this->getContainer()->get('DemoDataGenerator')->fechaInicio());

            $Pedido->setFechaInicio($fechaObject);

            $em->persist($Pedido);
            $em->flush();

            // TODO Crear un Service para generar los historiales.
            switch ($Pedido->getEstadoPedido()) {

                case 'en progreso':
                    $Historial = new Historial();
                    $Historial->setEstado('pendiente');
                    $Historial->setFecha($this->getContainer()->get('demodatagenerator')->fechaRandInferior($fechaObject->getTimestamp()));
                    $Historial->setIdPedido($Pedido->getCodigoSeguimiento());
                    $Historial->setParentId($Pedido);

                    $em->persist($Historial);
                    break;

                case 'completado':
                    $Historial = new Historial();
                    $Historial->setEstado('pendiente');
                    $Historial->setFecha($this->getContainer()->get('demodatagenerator')->fechaRandInferior($fechaObject->getTimestamp()));
                    $Historial->setIdPedido($Pedido->getCodigoSeguimiento());
                    $Historial->setParentId($Pedido);

                    $Historial2 = new Historial();
                    $Historial2->setEstado('en progreso');
                    $Historial2->setFecha($tempdate1 = $this->getContainer()->get('demodatagenerator')->fechaRandSuperior($fechaObject->getTimestamp()));
                    $Historial2->setIdPedido($Pedido->getCodigoSeguimiento());
                    $Historial2->setParentId($Pedido);

                    $Historial3 = $em->getRepository('OrderTrackingBackendBundle:Historial')->findOneBy(
                        array('parentId' => $Pedido->getId(), 'estado' => 'completado'));
                    $Historial3->setFecha($this->getContainer()->get('demodatagenerator')
                        ->fechaRandSuperior($tempdate1->getTimestamp()));

                    $Pedido->setFechaCompletado($this->getContainer()->get('demodatagenerator')->fechaRandSuperior($tempdate1->getTimestamp()));
                    $em->persist($Pedido);
                    $em->persist($Historial);
                    $em->persist($Historial2);
                    $em->persist($Historial3);
                    break;

                case 'cancelado':
                    $Historial = new Historial();
                    $Historial->setEstado('pendiente');
                    $Historial->setFecha($this->getContainer()->get('demodatagenerator')->fechaRandInferior($fechaObject->getTimestamp()));
                    $Historial->setIdPedido($Pedido->getCodigoSeguimiento());
                    $Historial->setParentId($Pedido);

                    $Historial2 = new Historial();
                    $Historial2->setEstado('en progreso');
                    $Historial2->setFecha($tempdate1 = $this->getContainer()->get('demodatagenerator')->fechaRandSuperior($fechaObject->getTimestamp()));
                    $Historial2->setIdPedido($Pedido->getCodigoSeguimiento());
                    $Historial2->setParentId($Pedido);

                    $em->persist($Historial);
                    $em->persist($Historial2);
                    break;
            }

            $em->flush();
            $progress->advance();
        }
        $progress->finish();
        $output->write('', true);
        $output->write('<info>Operación realizada con éxito.</info>', true);
    }
}