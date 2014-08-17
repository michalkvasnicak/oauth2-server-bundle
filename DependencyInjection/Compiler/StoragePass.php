<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class StoragePass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $storage = $container->getParameter('o_auth2_server.storage');

        $validator = [
            'authorization_code' => 'OAuth2\\Storage\\IAuthorizationCodeStorage',
            'access_token' => 'OAuth2\\Storage\\IAccessTokenStorage',
            'client' => 'OAuth2\\Storage\\IClientStorage',
            'refresh_token' => 'OAuth2\\Storage\\IRefreshTokenStorage',
        ];

        // validate if there is some unknown key
        $validatorKeys = array_keys($validator);
        $definedKeys = array_keys($storage);
        $diff = array_diff($definedKeys, $validatorKeys);

        if (!empty($diff)) {
            throw new InvalidConfigurationException(
                "Unrecognized storages '" . join(', ', $diff) . "'."
            );
        }

        foreach ($storage as $key => $value) {
            // all storage keys has to be strings (service names)
            if (!is_string($value)) {
                $type = gettype($value);

                throw new InvalidConfigurationException(
                    "o_auth2_server.storage.$key has to be string, $type given."
                );
            }

            // service has to exist
            if (!$container->hasDefinition($value)) {
                throw new InvalidConfigurationException(
                    "Service '$value' does not exist."
                );
            }

            // service has to implement given interface
            $service = $container->getDefinition($value);

            if (!is_subclass_of($service->getClass(), $validator[$key])) {
                throw new InvalidConfigurationException(
                    "Service '$value' does not implement {$validator[$key]}."
                );
            }

            // if is temporary generator, set lifetime from config
            if (is_subclass_of($service->getClass(), 'OAuth2\\Storage\\ITemporaryGenerator')) {
                $service->addMethodCall(
                    'setLifetime',
                    [
                        $container->getParameter("o_auth2_server.{$key}s.lifetime")
                    ]
                );
            }

            $container->setAlias("o_auth2_server.storage.$key", new Alias($value));
        }
    }
}
 