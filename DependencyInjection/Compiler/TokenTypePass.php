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
class TokenTypePass implements CompilerPassInterface
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
        $tokenClass = $container->getParameter('oauth2_server.classes.token_type');

        if (!class_exists($tokenClass)) {
            throw new InvalidConfigurationException(
                "Class $tokenClass does not exist."
            );
        }

        if (!is_subclass_of($tokenClass, 'OAuth2\TokenType\ITokenType')) {
            throw new InvalidConfigurationException(
                "Class $tokenClass does not implement OAuth2\\TokenType\\ITokenType."
            );
        }

        $serviceDefinition = $container->getDefinition('oauth2_server.token_type');
        $serviceDefinition->setClass($tokenClass);
    }
}
 