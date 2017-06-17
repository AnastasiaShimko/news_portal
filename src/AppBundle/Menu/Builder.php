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
        $menu->addChild('Profile', array('route' => 'main'));
        $menu['Profile']->addChild('Log out', array('route' => 'logout'));
        $menu->addChild('New article', array('route' => 'new_article'));
        $menu->addChild('Users', array('route' => 'account_control'));
        $menu->addChild('Articles', array('route' => 'main'));
        return $menu;
    }
}