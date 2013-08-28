<?php

namespace LinkZone\Core\PublicBundle\Form\Type\Platform\Search;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use LinkZone\Core\PublicBundle\Form\Type\Platform\Search\UserType;

class PlatformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $options['container']->get('translator');

        // TODO: make use of these translated values
        $choicesArray = array();
        foreach ($options['container']->get('doctrine')->getRepository("LinkZoneCorePublicBundle:PlatformTopic")->findAll() as $topic) {
            $choicesArray[$topic->getId()] = $translator->trans("platforms.topics." . $topic->getTransKey(), array(), "LZCorePublicBundle");
        }

        $builder->add("topic", "entity", array(
            'class'       => "LinkZoneCorePublicBundle:PlatformTopic",
            'empty_value' => $translator->trans("platforms.topics.empty", array(), "LZCorePublicBundle"),
            'required'    => false,
            'attr' => array(
                'ng-model' => "filter_topic"
            ),
        ));

        $builder->add("owner", new UserType());

        $builder->add("tags", null, array(
            "mapped" => false,
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
            'cascade_validation' => true,
            'container'          => false,
            'lastLogin'          => false,
//            'validation_groups' => array('Default', 'creation'),
        ));
    }

    public function getName() {
        return "platform_search";
    }
}
