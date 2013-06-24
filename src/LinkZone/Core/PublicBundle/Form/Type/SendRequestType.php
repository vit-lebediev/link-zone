<?php

namespace LinkZone\Core\PublicBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

class SendRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $options['container']->get('translator');

        $builder->add("senderPlatform", "entity", array(
            'class'         => "LinkZoneCorePublicBundle:Platform",
            'query_builder' => function(EntityRepository $er) use ($options) {
                $qb = $er->createQueryBuilder("platform");
                $qb->where($qb->expr()->andx(
                        $qb->expr()->eq("platform.hidden", ":hidden"),
                        $qb->expr()->eq("platform.owner", ":owner")
                ));

                $qb->setParameter(":hidden", 0);
                $qb->setParameter(":owner", $options['user']);

                $qb->orderBy("platform.created", "DESC");

                return $qb;
            },
            'empty_value'   => $translator->trans("platforms.topics.empty", array(), "LZCorePublicBundle"),
            'required'      => false,
        ));

        $builder->add("senderLink");

        $builder->add("senderLinkText");

        $builder->add("receiverPlatformId", "hidden", array(
            'data' => $options['default_receiver_platform_id'],
            'mapped' => false,
        ));
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            // While not always necessary, it's generally a good idea to explicitly specify the data_class option
            // http://symfony.com/doc/master/book/forms.html#creating-form-classes
            'data_class'      => "LinkZone\Core\PublicBundle\Entity\Request",
            'container'       => false,
            'user'            => false,
            'default_receiver_platform_id' => null,
            'csrf_protection'              => true, // TODO: make with CSRF protection (https://trello.com/c/D4bBdwPl)
            'csrf_field_name'              => '_token',
        ));
    }

    public function getName()
    {
        return "send_request";
    }
}
