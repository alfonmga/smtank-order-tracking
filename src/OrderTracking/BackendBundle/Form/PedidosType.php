<?php

namespace OrderTracking\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PedidosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * Prueba
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaInicio', 'datetime')
            ->add('fechaCompletado', 'datetime')
            ->add('nombreCliente')
            ->add('emailCliente')
            ->add('nombreProducto')
            ->add('precioProducto')
            ->add('estadoPedido', 'choice', array(
                'choices' => array('pendiente' => 'Pendiente', 'en progreso' => 'En progreso', 'completado' => 'Completado', 'cancelado' => 'Cancelado')
            ))
            ->add('codigoSeguimiento', 'text', array(
                'read_only' => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OrderTracking\BackendBundle\Entity\Pedidos'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ordertracking_backendbundle_pedidos';
    }
}
