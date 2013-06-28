<?php

namespace LinkZone\Core\PublicBundle\Form\Type\Request;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReviewRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("senderLinkHTML", "textarea", array(
            'mapped' => false,
            'data'   => $options['senderLinkHTML'],
        ));

        $builder->add("receiverLink");

        $builder->add("receiverLinkText");

        $builder->add("id", "hidden", array(
            'mapped' => false,
            'data'   => $options['orderId'],
        ));
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            // While not always necessary, it's generally a good idea to explicitly specify the data_class option
            // http://symfony.com/doc/master/book/forms.html#creating-form-classes
            'data_class'     => "LinkZone\Core\PublicBundle\Entity\Request",
            'container'      => false,
            'senderLinkHTML' => false,
            'orderId'        => false,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'validation_groups' => array('receiver'),
        ));
    }

    public function getName()
    {
        return "review_request";
    }
}
