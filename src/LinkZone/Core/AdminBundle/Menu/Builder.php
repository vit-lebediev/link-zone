<?php
// src/Acme/DemoBundle/Menu/Builder.php
namespace LinkZone\Core\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class Builder
{
    private $_factory;

    public function __construct(FactoryInterface $factory) {
        $this->_factory = $factory;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->_factory->createItem('root');

        $menu->addChild('Home', array('route' => 'linkzone_core_admin_home'));
//        $menu->addChild('About Me', array(
//            'route' => 'linkzone_core_admin_home',
//            'routeParameters' => array('id' => 42)
//        ));
        // ... add more children

        return $menu;
    }
}
