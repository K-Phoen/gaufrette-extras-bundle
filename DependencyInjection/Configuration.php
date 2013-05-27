<?php

namespace KPhoen\GaufretteExtrasBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
    protected $resolvers_factories;


    public function __construct(array $resolvers_factories)
    {
        $this->resolvers_factories = $resolvers_factories;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('k_phoen_gaufrette_extras');

        $this->addResolversSection($rootNode);

        $rootNode
            // add a faux-entry for factories, so that no validation error is thrown
            ->fixXmlConfig('factory', 'factories')
            ->children()
                ->arrayNode('factories')->ignoreExtraKeys()->end()
            ->end()
        ;

        return $treeBuilder;
    }

    protected function addResolversSection(ArrayNodeDefinition $node)
    {
        $resolverNodeBuilder = $node
            ->fixXmlConfig('resolver')
            ->children()
                ->arrayNode('resolvers')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->performNoDeepMerging()
                    ->children()
        ;

        foreach ($this->resolvers_factories as $name => $factory) {
            $factoryNode = $resolverNodeBuilder->arrayNode($name)->canBeUnset();
            $factory->addConfiguration($factoryNode);
        }
    }
}
