<?php

namespace LinkZone\Core\PublicBundle\Controller;

use LinkZone\Core\PublicBundle\Entity\User;
use LinkZone\Core\PublicBundle\Repository\PlatformRepository;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BaseController extends Controller
{
    /**
     * @var Logger
     */
    protected $_logger;
    protected $_doctrineManager;

    /**
     * @var User
     */
    protected $_user;

    /**
     * @var PlatformRepository
     */
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

    protected function _parseValidationErrors($errors) {
        $returnErrors = array();
        foreach ($errors as $error) {
            $returnErrors[] = array(
                'message' => $error->getMessage(),
                'propPath' => $error->getPropertyPath(),
                'invalidValue' => $error->getInvalidValue(),
                'code' => $error->getCode(),
            );
        }

        return $returnErrors;
    }

    protected function _getParameter($paramName)
    {
        return $this->container->getParameter($paramName);
    }
}
