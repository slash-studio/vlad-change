<?php
namespace VladChange\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildUserForm($builder, $options);

        $builder->add('current_password', 'password', array(
            'label' => 'Текущий пароль',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => new UserPassword(),
        ));
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Имя', 'translation_domain' => 'FOSUserBundle'))
            ->add('surname', null, array('label' => 'Фамилия', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'E-mail', 'translation_domain' => 'FOSUserBundle'))
        ;
    }

    public function getName()
    {
        return 'vladchange_user_profile';
    }
}
