<?php
namespace VladChange\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ChangePasswordFormType as BaseType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $builder->add('current_password', 'password', array(
            // 'label' => 'form.current_password',
            // 'translation_domain' => 'FOSUserBundle',
            // 'mapped' => false,
            // 'constraints' => new UserPassword(),
        // ));
        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'Новый пароль'),
            'second_options' => array('label' => 'Подтверждение'),
            'invalid_message' => 'fos_user.password.mismatch',
        ));
    }

    public function getName()
    {
        return 'vladchange_user_change_password';
    }
}
