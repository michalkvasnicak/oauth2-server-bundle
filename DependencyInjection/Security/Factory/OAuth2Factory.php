<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class OAuth2Factory implements SecurityFactoryInterface
{

    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authenticator.provider.oauth2_server.' . $id;

        $container
            ->setDefinition(
                $providerId,
                new DefinitionDecorator(
                    'oauth2_server.authentication.provider'
                )
            );

        $listenerId = 'security.authentication.listener.oauth2_server' . $id;
        $container
            ->setDefinition(
                $listenerId,
                new DefinitionDecorator('oauth2_server.authentication.listener')
            );

        return [$providerId, $listenerId, 'oauth2_server.authentication.entry_point'];
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'oauth2';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
    }

}
 