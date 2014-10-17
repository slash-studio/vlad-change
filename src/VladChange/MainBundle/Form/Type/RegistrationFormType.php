<?php
namespace VladChange\MainBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('username')
                ->add('name')
                ->add('surname');
    }

    public function getName()
    {
        return 'vladchange_user_registration';
    }
}
