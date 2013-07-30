<?php

namespace LinkZone\Core\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BaseController extends Controller
{
    protected $_logger;
    protected $_doctrineManager;
    protected $_user;
    protected $_platformRepository;
    protected $_translator;
    protected $_validator;

    protected function _init()
    {
        $this->_logger             = $this->get("logger");
        $this->_doctrineManager    = $this->getDoctrine()->getManager();
        $this->_user               = $this->get("security.context")->getToken()->getUser();
        $this->_platformRepository = $this->getDoctrine()->getRepository("LinkZoneCorePublicBundle:Platform");
        $this->_translator         = $this->get("translator");
        $this->_validator          = $this->get("validator");
    }

    protected function _verifyIsXmlHttpRequest()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new BadRequestHttpException("This method should only be called as xmlHttp");
        }
    }
}
