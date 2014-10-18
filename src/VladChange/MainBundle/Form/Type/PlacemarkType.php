<?php

namespace VladChange\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlacemarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('POST')
                ->add('name', 'text')
                ->add('short_desc', 'text')
                ->add('desc', 'text')
                ->add('limit_voice', 'number', ['data' => 100])
                ->add('lat', 'integer')
                ->add('lon', 'integer')
                ->add('save', 'submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VladChange\StoreBundle\Entity\Placemark',
        ));
    }

    public function getName()
    {
        return 'placemark';
    }
}
