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

/**
 * Genera un número de pedidos demo determinado.
 * Los datos de cada pedido son generados por el Service DemoDataGenerator.
 *
 * Class AddPedidoDemoCommand
 * @package OrderTracking\BackendBundle\Command
 */
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

            $this->getContainer()->get('HistorialGenerator')->generate($Pedido);

            $em->flush();
            $progress->advance();
        }
        $progress->finish();
        $output->write('', true);
        $output->write('<info>Operación realizada con éxito.</info>', true);
    }
}