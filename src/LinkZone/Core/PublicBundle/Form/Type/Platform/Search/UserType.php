<?php

namespace LinkZone\Core\PublicBundle\Form\Type\Platform\Search;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("lastLogin", "date", array(
            'attr' => array(
                'ng-model' => "filter_lastLogin"
            ),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            // While not always necessary, it's generally a good idea to explicitly specify the data_class option
            // http://symfony.com/doc/master/book/forms.html#creating-form-classes
            'data_class'     => "LinkZone\Core\PublicBundle\Entity\User",
            'csrf_protection'=> false, // TODO: make with CSRF protection (https://trello.com/c/D4bBdwPl)
        ));
    }

    public function getName() {
        return "platform_search_user";
    }
}
