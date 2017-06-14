<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->addChild('Log out', array('route' => 'logout'));
        $menu->addChild('Profile', array('route' => 'main'));
        $menu['Profile']->addChild('New password', array('route' => 'password_change'));
        $menu['Profile']->addChild('Change password', array('route' => 'change_user'));
        $menu->addChild('Add articles', array('route' => 'new_article'));
        $menu->addChild('Users', array('route' => 'account_control'));
        $menu->addChild('Home', array('route' => 'main'));

        return $menu;
    }
}

