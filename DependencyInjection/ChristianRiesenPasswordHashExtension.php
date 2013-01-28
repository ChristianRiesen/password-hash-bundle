<?php

namespace ChristianRiesen\PasswordHashBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ChristianRiesenPasswordHashExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        if (!isset($config['cost'])) {
            throw new \InvalidArgumentException('The "cost" option must be set');
        }
        
        if (!isset($config['saltlength'])) {
            throw new \InvalidArgumentException('The "saltlength" option must be set');
        }
        
        $container->setParameter('cr_passwordhash.cost', $config['cost']);
        $container->setParameter('cr_passwordhash.saltlength', $config['saltlength']);
    }
}
