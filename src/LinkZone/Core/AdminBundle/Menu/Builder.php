<?php
namespace LinkZone\Core\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class Builder
{
    private $_factory;

    public function __construct(FactoryInterface $factory) {
        $this->_factory = $factory;
    }

    public function createMainMenu(Request $request, Translator $translator)
    {
        $menu = $this->_factory->createItem('root');

        $menu->addChild($translator->trans("menu.home", array(), "LZCoreAdminBundle"), array('route' => 'linkzone_core_admin_home'));
        $menu->addChild($translator->trans("menu.users_management", array(), "LZCoreAdminBundle"), array('route' => 'linkzone_core_admin_manage_users'));
        $menu->addChild($translator->trans("menu.platforms_management", array(), "LZCoreAdminBundle"), array('route' => 'linkzone_core_admin_manage_platforms'));
//        $menu->addChild('About Me', array(
//            'route' => 'linkzone_core_admin_home',
//            'routeParameters' => array('id' => 42)
//        ));
        // ... add more children

        return $menu;
    }
}
