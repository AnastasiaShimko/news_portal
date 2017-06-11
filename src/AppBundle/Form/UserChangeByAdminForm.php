<?php
namespace AppBundle\Form;

use AppBundle\Entity\RegisteredUser;
use AppBundle\Entity\User;
use AppBundle\Entity\Roules;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserChangeByAdminForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('roles', CollectionType::class, array(
            'entry_type'   => ChoiceType::class,
            'entry_options'  => array(
                'choices'  => array(
                    'Admin' => 'ROLE_ADMIN',
                    'Manager'     => 'ROLE_MANAGER',
                    'User'    => 'ROLE_USER',
                    'Delete Account'    => 'ROLE_DELETED',
                ),
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Roules::class,
        ));
    }
}