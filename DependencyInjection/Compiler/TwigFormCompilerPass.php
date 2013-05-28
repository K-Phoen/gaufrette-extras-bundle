<?php

namespace KPhoen\GaufretteExtrasBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;


/**
 * Auto adds the Twig form template to the list of resources
 */
class TwigFormCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('twig.form.resources')) {
            return;
        }

        $container->setParameter('twig.form.resources', array_merge(
            array('KPhoenGaufretteExtrasBundle:Form:fields.html.twig'),
            $container->getParameter('twig.form.resources')
        ));
    }
}
