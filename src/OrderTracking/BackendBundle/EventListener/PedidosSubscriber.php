<?php

namespace OrderTracking\BackendBundle\EventListener;

use Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Event\OnFlushEventArgs,
    Doctrine\ORM\Events,
    Doctrine\ORM\Event\LifecycleEventArgs;

use OrderTracking\BackendBundle\Entity\Pedidos,
    OrderTracking\BackendBundle\Entity\Historial;

use Symfony\Component\DependencyInjection\ContainerInterface;

class PedidosSubscriber implements EventSubscriber
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postPersist(LifecycleEventArgs $evento)
    {
        $PedidoEntity = $evento->getEntity();
        $em = $evento->getEntityManager();

        if($PedidoEntity instanceof Pedidos)
        {
            $Historial = new Historial();
            $Historial->setEstado($PedidoEntity->getEstadoPedido());
            $Historial->setIdPedido($PedidoEntity->getCodigoSeguimiento());
            $Historial->setParentId($PedidoEntity);
            $Historial->setFecha(new \DateTime('now'));
            $em->persist($Historial);
            $em->flush();

            $this->container->get('TransactionalEmails')->newPedido($PedidoEntity->getNombreCliente(),
                $PedidoEntity->getEmailCliente(), $PedidoEntity->getCodigoSeguimiento());
        }
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $updated) {
            if ($updated instanceof Pedidos) {
                $PedidoEntity = $updated;
                /**
                 * 1. Comprobamos el último historial del pedido
                 * 2. En caso de que no conste ningún historial entonces creamos uno con estado 'pendiente'
                 * 3. Si existe historial comparamos el estado del pedido con el estado del último historial,
                 *    en caso de que sean iguales no hacemos nada, si es diferente creamos un nuevo historial con el
                 *    nuevo estado.
                 * 4. Notificar cliente si el checkbox es TRUE o NULL (API REST)
                 **/
                $ultimoHistorial = $em->getRepository('OrderTrackingBackendBundle:Historial')->findOneBy(array('parentId' =>
                    $PedidoEntity), array('id' => 'DESC'));

                if($ultimoHistorial) {
                    if($PedidoEntity->getEstadoPedido() !== $ultimoHistorial->getEstado()) {
                        $Historial = new Historial();
                        $Historial->setEstado($PedidoEntity->getEstadoPedido());
                        $Historial->setIdPedido($PedidoEntity->getCodigoSeguimiento());
                        $Historial->setParentId($PedidoEntity);
                        $Historial->setFecha(new \DateTime('now'));
                        $em->persist($Historial);

                        if($PedidoEntity->getEstadoPedido() == 'completado')
                        {
                            $PedidoEntity->setFechaCompletado(new \DateTime('now'));
                            $PedidoEntity->setEstadoPedido('completado');
                            $md = $em->getClassMetadata('OrderTracking\BackendBundle\Entity\Pedidos');
                            $uow->recomputeSingleEntityChangeSet($md, $PedidoEntity);
                        }
                       $notificarCliente = $this->container->get('request')->getSession()->get($PedidoEntity->getId().
                           $PedidoEntity->getCodigoSeguimiento());

                        if($notificarCliente == true) {
                            $this->container->get('TransactionalEmails')->pedidoUpdated(
                                $PedidoEntity->getEstadoPedido(), $PedidoEntity->getNombreCliente(),
                                $PedidoEntity->getEmailCliente(), $PedidoEntity->getCodigoSeguimiento()
                            );
                        }
                    }
                }
            }
        }

        $uow->computeChangeSets();
    }

    public function getSubscribedEvents()
    {
        return array(Events::onFlush);
    }
}