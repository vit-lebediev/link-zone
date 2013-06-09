<?php

namespace LinkZone\Core\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BaseController extends Controller
{
    protected function _verifyIsXmlHttpRequest()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new BadRequestHttpException("This method should only be called as xmlHttp");
        }
    }
}
