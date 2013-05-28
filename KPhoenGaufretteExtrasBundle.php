<?php

namespace KPhoen\GaufretteExtrasBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use KPhoen\GaufretteExtrasBundle\DependencyInjection\Compiler\TwigFormCompilerPass;
use KPhoen\GaufretteExtrasBundle\DependencyInjection\Compiler\ResolversCompilerPass;


class KPhoenGaufretteExtrasBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TwigFormCompilerPass());
        $container->addCompilerPass(new ResolversCompilerPass());
    }
}
