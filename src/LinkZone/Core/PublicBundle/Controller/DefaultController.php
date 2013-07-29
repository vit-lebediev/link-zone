<?php

namespace LinkZone\Core\PublicBundle\Controller;

class DefaultController extends BaseController
{
    public function partialsAction($controller, $partial) {
        return $this->render("LinkZoneCorePublicBundle:" . ucfirst(strtolower($controller)) . ":partials/" . strtolower($partial) . ".html.twig");
    }
}
