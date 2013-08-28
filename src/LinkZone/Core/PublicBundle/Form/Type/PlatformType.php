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

        // TODO: make use of these translated values
        $choicesArray = array();
        foreach ($options['container']->get('doctrine')->getRepository("LinkZoneCorePublicBundle:PlatformTopic")->findAll() as $topic) {
            $choicesArray[$topic->getId()] = $translator->trans("platforms.topics." . $topic->getTransKey(), array(), "LZCorePublicBundle");
        }

        // Field Type Guessing
        // http://symfony.com/doc/current/book/forms.html#field-type-guessing
        $builder->add("url", "url", array(
            'attr' => array(
                'ng-model' => "platform.url",
                'ng-disabled' => "editing"
            ),
        ));

        $builder->add("topic", "entity", array(
            'class'       => "LinkZoneCorePublicBundle:PlatformTopic",
            'empty_value' => $translator->trans("platforms.topics.empty", array(), "LZCorePublicBundle"),
            'required'    => false,
            'attr' => array(
                'ng-model' => "platform.topic_id",
            ),
        ));

        $builder->add("description", "textarea", array(
            'required' => false,
            'attr' => array(
                'ng-model' => "platform.description",
            ),
        ));

        $builder->add("hidden", "checkbox", array(
            'required' => false,
            'attr' => array(
                'ng-model' => "platform.hidden",
                'ng-checked' => "platform.hidden"
            ),
        ));

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
            'csrf_protection'=> true,
            'container'      => false,
            'validation_groups' => array('Default', 'creation'),
        ));
    }

    public function getName()
    {
        return "platform";
    }
}
