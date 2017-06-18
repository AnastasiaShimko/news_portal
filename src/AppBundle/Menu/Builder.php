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
        $menu->addChild('profile', array('route' => 'main'));
        $menu['profile']->addChild('log_out', array('route' => 'logout'));
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_MANAGER')) {
            $menu->addChild('new_article', array('route' => 'new_article'));
        }
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $menu->addChild('users', array('route' => 'account_control'));
        }
        $menu->addChild('articles', array('route' => 'show_articles'));
        return $menu;
    }
}