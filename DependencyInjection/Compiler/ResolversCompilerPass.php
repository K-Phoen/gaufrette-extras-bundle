<?php

namespace KPhoen\GaufretteExtrasBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;


class ResolversCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('knp_gaufrette.filesystem_map')) {
            return;
        }

        $fsMapDefinition = $container->getDefinition('knp_gaufrette.filesystem_map');
        foreach ($fsMapDefinition->getArgument(0) as $fs) {
            $resolver_id = sprintf('gaufrette.%s_resolver', $this->getFsName((string) $fs));
            $fsDefinition = $container->getDefinition((string) $fs);

            $newAdapter = new Definition(
                '%gaufrette.resolvable_adapter.class%',
                array($fsDefinition->getArgument(0), $container->getDefinition($resolver_id))
            );

            $fsDefinition->replaceArgument(0, $newAdapter);
        }
    }

    protected function getFsName($fs)
    {
        $dot = strpos($fs, '.');
        $underscore = strrpos($fs, '_');

        return substr($fs, $dot + 1, $underscore - $dot - 1);
    }
}
