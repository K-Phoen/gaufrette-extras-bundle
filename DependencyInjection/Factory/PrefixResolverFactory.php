<?php

namespace KPhoen\GaufretteExtrasBundle\DependencyInjection\Factory;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;


class PrefixResolverFactory implements ResolverFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $definition = new Definition('%gaufrette.resolver.prefix.class%', array($config['path']));
        $definition->setPublic(false);

        $container->setDefinition($id, $definition);
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'prefix';
    }

    /**
     * {@inheritDoc}
     */
    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('path')->isRequired()->end()
            ->end()
        ;
    }
}
