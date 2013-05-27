<?php

namespace LinkZone\Core\PublicBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlatformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $options['container']->get('translator');

        $choicesArray = array();
        foreach ($options['container']->get('doctrine')->getRepository("LinkZoneCorePublicBundle:PlatformTopic")->findAll() as $topic) {
            $choicesArray[$topic->getId()] = $translator->trans("platforms.topics." . $topic->getTransKey(), array(), "LZCorePublicBundle");
        }

        // Field Type Guessing
        // http://symfony.com/doc/current/book/forms.html#field-type-guessing
        $builder->add("url");

        $builder->add("topic", "entity", array(
            'class'       => "LinkZoneCorePublicBundle:PlatformTopic",
            'empty_value' => $translator->trans("platforms.topics.empty", array(), "LZCorePublicBundle"),
            'required'    => false,
        ));

        $builder->add("description", "textarea", array(
            'required' => false,
        ));

        $builder->add("hidden", "checkbox", array(
            'required' => false,
        ));
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            // While not always necessary, it's generally a good idea to explicitly specify the data_class option
            // http://symfony.com/doc/master/book/forms.html#creating-form-classes
            'data_class'     => "LinkZone\Core\PublicBundle\Entity\Platform",
            'csrf_protection'=> false, // TODO: make with CSRF protection (https://trello.com/c/D4bBdwPl)
            'container'      => false,
        ));
    }

    public function getName()
    {
        return "platform";
    }
}
