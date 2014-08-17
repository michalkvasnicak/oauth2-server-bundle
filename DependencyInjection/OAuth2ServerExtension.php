<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class OAuth2ServerExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $config An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('o_auth2_server.access_tokens.lifetime', $config['access_tokens']['lifetime']);
        $container->setParameter('o_auth2_server.refresh_tokens.lifetime', $config['refresh_tokens']['lifetime']);
        $container->setParameter('o_auth2_server.refresh_tokens.generate', $config['refresh_tokens']['generate']);
        $container->setParameter('o_auth2_server.www_realm', $config['www_realm']);
        $container->setParameter('o_auth2_server.grant_types', $config['grant_types']);

        foreach ($config['classes'] as $key => $class) {
            $container->setParameter("o_auth2_server.classes.$key", $class);
        }
    }


    public function getAlias()
    {
        return 'o_auth2_server';
    }
}
