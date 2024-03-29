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
        $menu->addChild($translator->trans("menu.messages", array(), "LZCorePublicBundle"), array('route' => 'linkzone_core_public_messages'));
        $menu->addChild($translator->trans("menu.requests_exchange", array(), "LZCorePublicBundle"), array('route' => 'linkzone_core_public_requests_exchange'));
        $menu->addChild($translator->trans("menu.requests_in_progress", array(), "LZCorePublicBundle"), array('route' => 'linkzone_core_public_requests_in_progress'));
        $menu->addChild($translator->trans("menu.requests_finished", array(), "LZCorePublicBundle"), array('route' => 'linkzone_core_public_requests_finished'));

        return $menu;
    }
}
