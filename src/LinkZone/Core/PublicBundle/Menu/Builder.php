<?php
namespace LinkZone\Core\PublicBundle\Menu;

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

        $menu->addChild($translator->trans("menu.home", array(), "LZCorePublicBundle"), array('route' => 'linkzone_core_public_home'));
        $menu->addChild($translator->trans("menu.platforms", array(), "LZCorePublicBundle"), array('route' => 'linkzone_core_public_platforms'));
        $menu->addChild($translator->trans("menu.platforms_search", array(), "LZCorePublicBundle"), array('route' => 'linkzone_core_public_platforms_search'));

        return $menu;
    }
}
