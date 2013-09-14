<?php

namespace LinkZone\Core\PublicBundle\Controller;

class DefaultController extends BaseController
{
    /**
     * Parses partials, requested by Front-end AngularJS application.
     *
     * In order to automate the routes generation for partials, required by front-end AngularJS app,
     * this action creates a convention, by which front-end is able to request any partial from any controller.
     *
     * @param type $controller
     * @param type $partial
     * @return type
     */
    public function partialsAction($controller, $partial) {
        return $this->render("LinkZoneCorePublicBundle:" . ucfirst(strtolower($controller)) . ":partials/" . strtolower($partial) . ".html.twig");
    }

    /**
     * This index action is required to implement "default route", so that Angular could be loaded even when
     * front-end application requests for non-existent route
     */
    public function indexAction() {
        return $this->render("LinkZoneCorePublicBundle:Default:index.html.twig");
    }
}
