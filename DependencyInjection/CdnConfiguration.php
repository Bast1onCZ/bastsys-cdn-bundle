<?php
declare(strict_types=1);

namespace BastSys\CdnBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package BastSys\CdnBundle\DependencyInjection
 * @author mirkl
 */
class CdnConfiguration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('cdn');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('storage')->isRequired()
                    ->children()
                        ->scalarNode('path')->isRequired()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

}
