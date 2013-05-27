<?php

namespace KPhoen\GaufretteExtrasBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class KPhoenGaufretteExtrasExtension extends Extension
{
    protected $factories;

    /**
     * Loads the extension
     *
     * @param  array            $configs
     * @param  ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // first assemble the adapter factories
        $factoryConfig = new FactoryConfiguration();
        $config        = $processor->processConfiguration($factoryConfig, $configs);
        $factories     = $this->createResolverFactories($config, $container);

        // then normalize the configs
        $configuration = new Configuration($factories);
        $config = $this->processConfiguration($configuration, $configs);

        // create the resolvers
        $resolvers = array();
        foreach ($config['resolvers'] as $fs_name => $resolver) {
            $resolvers[$fs_name] = $this->createResolver($fs_name, $resolver, $container, $factories);
        }
    }

    protected function createResolver($name, array $config, ContainerBuilder $container, array $factories)
    {
        $adapter = null;
        foreach ($config as $key => $resolver) {
            if (array_key_exists($key, $factories)) {
                $id = sprintf('gaufrette.%s_resolver', $name);
                $factories[$key]->create($container, $id, $resolver);

                return $id;
            }
        }

        throw new \LogicException(sprintf('The resolver \'%s\' is not configured.', $name));
    }

    /**
     * Creates the resolver factories
     *
     * @param  array            $config
     * @param  ContainerBuilder $container
     */
    private function createResolverFactories($config, ContainerBuilder $container)
    {
        if (null !== $this->factories) {
            return $this->factories;
        }

        // load bundled adapter factories
        $tempContainer = new ContainerBuilder();
        $parameterBag  = $container->getParameterBag();
        $loader        = new XmlFileLoader($tempContainer, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('resolver_factories.xml');

        // load user-created adapter factories
        foreach ($config['factories'] as $factory) {
            $loader->load($parameterBag->resolveValue($factory));
        }

        $services  = $tempContainer->findTaggedServiceIds('gaufrette.resolver.factory');
        $factories = array();
        foreach (array_keys($services) as $id) {
            $factory = $tempContainer->get($id);
            $factories[str_replace('-', '_', $factory->getKey())] = $factory;
        }

        return $this->factories = $factories;
    }
}
