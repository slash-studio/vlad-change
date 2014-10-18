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
                ->add('name', 'text', ['label' => 'Название'])
                ->add('short_desc', 'text', ['label' => 'Краткое описание'])
                ->add('desc', 'text', ['label' => 'Полное описание'])
                ->add('limit_voice', 'number', ['data' => 100, 'label' => 'Порог голосов'])
                ->add('lat', 'number', ['label' => 'Широта', 'precision' => 20])
                ->add('lon', 'number', ['label' => 'Долгота', 'precision' => 20])
                ->add('save', 'submit', ['label' => 'Сохранить'])
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
