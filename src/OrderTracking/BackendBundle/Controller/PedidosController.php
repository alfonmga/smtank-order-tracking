<?php

namespace OrderTracking\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OrderTracking\BackendBundle\Entity\Pedidos;
use OrderTracking\FrontendBundle\Entity\Historial;
use OrderTracking\BackendBundle\Form\PedidosType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pedidos controller.
 *
 * @Route("/backend")
 */
class PedidosController extends Controller
{

    /**
     * Lists all Pedidos entities.
     *
     * @Route("/", name="backend")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OrderTrackingBackendBundle:Pedidos')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Pedidos entity.
     *
     * @Route("/", name="backend_create")
     * @Method("POST")
     * @Template("OrderTrackingBackendBundle:Pedidos:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Pedidos();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Pedidos entity.
     *
     * @param Pedidos $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Pedidos $entity)
    {
        $form = $this->createForm(new PedidosType(), $entity, array(
            'action' => $this->generateUrl('backend_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Pedidos entity.
     *
     * @Route("/new", name="backend_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Pedidos();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Pedidos entity.
     *
     * @Route("/{id}", name="backend_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OrderTrackingBackendBundle:Pedidos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedidos entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Pedidos entity.
     *
     * @Route("/{id}/edit", name="backend_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OrderTrackingBackendBundle:Pedidos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedidos entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Pedidos entity.
    *
    * @param Pedidos $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Pedidos $entity)
    {
        $form = $this->createForm(new PedidosType(), $entity, array(
            'action' => $this->generateUrl('backend_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Pedidos entity.
     *
     * @Route("/{id}", name="backend_update")
     * @Method("PUT")
     * @Template("OrderTrackingBackendBundle:Pedidos:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OrderTrackingBackendBundle:Pedidos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedidos entity.');
        }
        $currentEstado = $entity->getEstadoPedido();
        $emailCliente = $entity->getEmailCliente();
        $nombreCliente = $entity->getNombreCliente();
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $estadoForm = $editForm["estadoPedido"]->getData();
            $codigoSeguimiento = $editForm["codigoSeguimiento"]->getData();

            if ($currentEstado !== $estadoForm) {
                $historial = new Historial();
                $historial->setFecha(date_create(date('Y-m-d H:i:s')));
                $historial->setEstado($estadoForm);
                $historial->setIdPedido($codigoSeguimiento);
                $em->persist($historial);

                $message = \Swift_Message::newInstance()
                    ->setSubject('Pedido actualizado - SMTank.com')
                    ->setFrom('contacto@smtank.com')
                    ->setTo($emailCliente)
                    ->setBody('Hola ' . $nombreCliente . ',<br><br> El estado de tu pedido (ID: <b>' . $codigoSeguimiento . '</b>) ha sido actualizado.<br>Entra en http://pedidos.smtank.com para ver más detalles sobre tu pedido. <br><br>Un saludo,<br>SMTank.com.', "text/html");
                $this->get('mailer')->send($message);

            }
            if ($estadoForm == 'completado')
            {
                $actualizar = $em->getRepository('OrderTrackingBackendBundle:Pedidos')->find($id);
                $actualizar->setFechaCompletado(date_create(date('Y-m-d H:i:s')));
                $em->persist($actualizar);
            }

            $em->flush();
            return $this->redirect($this->generateUrl('backend_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Pedidos entity.
     *
     * @Route("/{id}", name="backend_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OrderTrackingBackendBundle:Pedidos')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pedidos entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('backend'));
    }

    /**
     * Creates a form to delete a Pedidos entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backend_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

}
