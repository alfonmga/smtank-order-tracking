<?php

namespace OrderTracking\BackendBundle\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs,
    Doctrine\ORM\Events,
    Doctrine\ORM\Event\LifecycleEventArgs,
    Doctrine\Common\EventSubscriber;

use OrderTracking\BackendBundle\Entity\Pedidos,
    OrderTracking\BackendBundle\Entity\Historial;

class PedidosSubscriber implements EventSubscriber
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function postPersist(LifecycleEventArgs $evento)
    {
        $PedidoEntity = $evento->getEntity();
        $em = $evento->getEntityManager();

        if($PedidoEntity instanceof Pedidos)
        {
            /**
             * Un historial es creado con el estado del nuevo pedido que ha sido persistido.
             */
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

        /**
         * 1. Comprobamos el último historial del pedido
         * 2. En caso de que no conste ningún historial entonces creamos uno con estado 'pendiente'
         * 3. Si existe historial comparamos el estado del pedido con el estado del último historial,
         *    en caso de que sean iguales no hacemos nada, si es diferente creamos un nuevo historial con el
         *    nuevo estado.
         * 4. Notificar cliente si el valor del checkbox es TRUE o NULL (API REST)
         **/
        foreach ($uow->getScheduledEntityUpdates() as $updated) {
            if ($updated instanceof Pedidos) {
                $PedidoEntity = $updated;

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

                        /**
                         * Si el pedido ha sido actualizado desde el backend deberiamos obtener un true or false para
                         * saber si notificar por e-mail al cliente sobre la actualización de su pedido.
                         * En caso de que el pedido haya sido actualizado desde la API REST el valor siempre será NULL.
                         */
                       $notificarCliente = $this->container->get('request')->getSession()->get($PedidoEntity->getId().
                           $PedidoEntity->getCodigoSeguimiento());

                        /**
                         * $notificarCliente == true && false (pedido actualizado desde backend)
                         * $notificarCliente == null (pedido actualizado desde API REST, siempre notificar al cliente)
                         */
                        if($notificarCliente == true || $notificarCliente == null) {
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