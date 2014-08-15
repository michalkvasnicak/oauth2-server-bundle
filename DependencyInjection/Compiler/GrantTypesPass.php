<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class GrantTypesPass implements CompilerPassInterface
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
        $grantTypeResolver = $container->getDefinition('oauth2_server.resolver.grant_type');

        $grantTypes = $container->getParameter('oauth2_server.grant_types');
        $taggedServices = $container->findTaggedServiceIds('oauth2_server.grant_type');
        $registeredGrantTypes = 0;

        $defaultGrantTypesPassess = [
            'authorization_code' => 'MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\GrantType\AuthorizationCodePass',
            'client_credentials' => 'MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\GrantType\ClientCredentialsPass',
            'implicit' => 'MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\GrantType\ImplicitPass',
            'refresh_token' => 'MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\GrantType\RefreshTokenPass',
            'resource_owner_password_credentials' => 'MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\GrantType\ResourceOwnerPasswordCredentialsPass'
        ];

        foreach ($grantTypes as $grantTypeName => $enabled) {
            if (!$enabled) {
                continue; // skip disabled
            }

            /** @var CompilerPassInterface $grantTypeCompilerPass */
            $grantTypeCompilerPass = new $defaultGrantTypesPassess[$grantTypeName];
            $grantTypeCompilerPass->process($container);

            $registeredGrantTypes++;
        }

        foreach ($taggedServices as $serviceName => $tags) {
            $service = $container->getDefinition($serviceName);

            if (!is_subclass_of($service->getClass(), 'OAuth2\\GrantType\\IGrantType')) {
                throw new InvalidConfigurationException(
                    "Service '$serviceName' is not an instance of OAuth2\\GrantType\\IGrantType"
                );
            }

            $grantTypeResolver->addMethodCall('accept', [new Reference($serviceName)]);
            $registeredGrantTypes++;
        }

        if (!$registeredGrantTypes) {
            throw new InvalidConfigurationException(
                "At least one OAuth 2.0 grant type has to be registered."
            );
        }
    }
}
 