<?php

namespace MultipleApp\KernelBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MultipleAppKernelExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->setParameter('kernel.commons_dir', '%kernel.root_dir%/../commons');
        $container->setDefinition('file_locator', new Definition(
            'MultipleApp\KernelBundle\FileLocator\FileLocator',
            array(new Reference('kernel'), array(
                '%kernel.root_dir%/Resources',
                '%kernel.commons_dir%/Resources',
            ))
        ));
    }
}
