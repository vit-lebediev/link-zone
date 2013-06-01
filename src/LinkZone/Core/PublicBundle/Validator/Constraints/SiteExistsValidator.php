<?php

namespace LinkZone\Core\PublicBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Guzzle\Http\Client;
use Guzzle\Common\Exception\GuzzleException;

class SiteExistsValidator extends ConstraintValidator
{
    private $_container;

    public function __construct(ContainerInterface $container) {
        $this->_container = $container;
    }

    public function validate($value, Constraint $constraint)
    {
        try {
            $client = new Client($value);
            $client->head()->send();
        } catch (GuzzleException $e) {
            $this->context->addViolation($this->_container->get("translator")->trans("platforms.site_not_exists", array(), "LZCorePublicBundle"));
        }
    }
}
