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
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaInicio')
            ->add('fechaCompletado')
            ->add('nombreCliente')
            ->add('emailCliente')
            ->add('nombreProducto')
            ->add('precioProducto')
            ->add('estadoPedido')
            ->add('codigoSeguimiento')
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
