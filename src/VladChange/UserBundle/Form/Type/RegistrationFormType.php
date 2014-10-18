<?php
namespace VladChange\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label' => 'E-mail', 'translation_domain' => 'FOSUserBundle'))
            // ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'Пароль'),
                'second_options' => array('label' => 'Подтвердите пароль'),
                'invalid_message' => 'Введенные пароли не совпадают!',
            ))
            ->add('name', null, ['label' => 'Имя' ])
            ->add('surname', null, ['label' => 'Фамилия' ]);

    }

    public function getName()
    {
        return 'vladchange_user_registration';
    }
}
