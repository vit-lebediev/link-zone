<?php

namespace LinkZone\Core\PublicBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // check if $options contain senderPlatformId and receiverPlatformId
        if (!$options['senderPlatformId'] OR !$options['receiverPlatformId']) {
            throw new BadRequestHttpException("\$options['senderPlatformId'] AND \$options['receiverPlatformId'] must be specified");
        }

        $builder->add("message", "textarea", array(
            'required' => true,
        ));

        $builder->add("senderPlatformId", "hidden", array(
            'data' => $options['senderPlatformId'],
            'mapped' => false,
        ));

        $builder->add("receiverPlatformId", "hidden", array(
            'data' => $options['receiverPlatformId'],
            'mapped' => false,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            // While not always necessary, it's generally a good idea to explicitly specify the data_class option
            // http://symfony.com/doc/master/book/forms.html#creating-form-classes
            'data_class'         => "LinkZone\Core\PublicBundle\Entity\Message",
            'csrf_protection'    => true,
            'senderPlatformId'   => false,
            'receiverPlatformId' => false,
        ));
    }

    public function getName()
    {
        return "message";
    }
}