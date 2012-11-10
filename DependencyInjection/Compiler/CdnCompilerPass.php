<?php

namespace Oryzone\Bundle\MediaStorageBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class CdnCompilerPass implements CompilerPassInterface
{

    const CDN_FACTORY_SERVICE = 'oryzone_media_storage.cdn_factory';
    const CDN_SERVICES_TAG = 'oryzone_media_storage_cdn';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(self::CDN_FACTORY_SERVICE)) {
            return;
        }

        $definition = $container->getDefinition(self::CDN_FACTORY_SERVICE);

        foreach ($container->findTaggedServiceIds(self::CDN_SERVICES_TAG) as $id => $attributes) {
            if(!isset($attributes[0]['alias']))
                throw new InvalidConfigurationException(sprintf('Service "%s" lacks of mandatory "alias" attribute for service tagged as "%s"', $id, self::CDN_SERVICES_TAG));

            $definition->addMethodCall('addAlias', array($id, $attributes[0]['alias']));
        }
    }
}