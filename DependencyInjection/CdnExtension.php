<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class CdnExtension
 * @package BastSys\CdnBundle\DependencyInjection
 * @author mirkl
 */
class CdnExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // load config
        $cdnConfig = $this->processConfiguration(new CdnConfiguration(), $configs);
        $storagePath = $cdnConfig['storage']['path'];

        $container->setParameter('cdn.storage.path', $storagePath);

        // load services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        $loader->load('command.yaml');
        $loader->load('controller.yaml');
        $loader->load('repository.yaml');
        $loader->load('listeners.yaml');
    }
}
