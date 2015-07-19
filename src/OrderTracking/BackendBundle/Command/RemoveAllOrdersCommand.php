<?php
namespace OrderTracking\BackendBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
class RemoveAllOrdersCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pedidos:remove:all')
            ->setDescription('Borra todos los pedidos de la BDD');
    }
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('¿Estás seguro de que quieres ELIMINAR TODOS LOS PEDIDOS de la base de datos? (Y/N): ', false);
        if (!$helper->ask($input, $output, $question)) {
            $output->write('<fg=red>Operación cancelada.</fg=red>', true);
            return;
        }
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $Pedidos = $em->getRepository('OrderTrackingBackendBundle:Pedidos')->findAll();
        $numPedidos = count($Pedidos);
        foreach ($Pedidos as $Pedido) {
            $em->remove($Pedido);
        }
        $em->flush();
        $output->write('', true);
        $output->write('<info>Operación realizada con éxito: ' . $numPedidos . ' pedidos eliminados</info>', true);
    }
}