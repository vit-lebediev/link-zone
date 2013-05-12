<?php

namespace LinkZone\Core\PublicBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class SiteExists extends Constraint
{
    public function validatedBy()
    {
        return "site_exists";
    }
}
