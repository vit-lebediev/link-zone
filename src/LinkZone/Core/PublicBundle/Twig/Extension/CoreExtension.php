<?php

namespace LinkZone\Core\PublicBundle\Twig\Extension;

use LinkZone\Core\PublicBundle\Entity\Request;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CoreExtension extends \Twig_Extension implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Sets the Container associated with this Controller.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'senderLinkHtmlCode' => new \Twig_Function_Method($this, "orderSenderLinkHtmlCode"),
            'receiverLinkHtmlCode' => new \Twig_Function_Method($this, "orderReceiverLinkHtmlCode"),
        );
    }

    /**
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Request $order
     */
    public function orderSenderLinkHtmlCode(Request $order)
    {
        return sprintf($this->container->getParameter("default_link_html"), $order->getSenderLink(), $order->getSenderLinkText());
    }

    /**
     *
     * @param \LinkZone\Core\PublicBundle\Entity\Request $order
     */
    public function orderReceiverLinkHtmlCode(Request $order)
    {
        return sprintf($this->container->getParameter("default_link_html"), $order->getReceiverLink(), $order->getReceiverLinkText());
    }

    public function getName()
    {
        return "core_extension";
    }
}
