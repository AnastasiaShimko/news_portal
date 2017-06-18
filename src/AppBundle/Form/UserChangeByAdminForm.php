<?php

namespace AppBundle\Form;

use AppBundle\Entity\Roles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserChangeByAdminForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('roles', CollectionType::class, array(
            'entry_type'   => ChoiceType::class,
            'entry_options'  => array(
                'label'=>'user_role',
                'choices'  => array(
                    'admin' => 'ROLE_ADMIN',
                    'manager'     => 'ROLE_MANAGER',
                    'user'    => 'ROLE_USER',
                    'delete_account'    => 'ROLE_DELETED',
                ),
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Roles::class,
        ));
    }
}