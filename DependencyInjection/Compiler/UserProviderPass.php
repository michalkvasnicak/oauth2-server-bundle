<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
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
class UserProviderPass implements CompilerPassInterface
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
        $serviceName = $container->getParameter('oauth2_server.user_provider');

        if (!$container->hasDefinition($serviceName)) {
            throw new InvalidConfigurationException("Service '$serviceName' does not exist.");
        }

        $service = $container->getDefinition($serviceName);

        if (!is_subclass_of($service->getClass(), 'Symfony\\Component\\Security\\Core\\User\\UserProviderInterface')) {
            throw new InvalidConfigurationException(
                "Service '$serviceName' has to implement Symfony\\Component\\Security\\Core\\User\\UserProviderInterface."
            );
        }

        $container->setAlias('auth2_server.user_provider', $serviceName);
    }
}
 