<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\DependencyInjection\Compiler;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\TokenTypePass;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\BaseTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class TokenTypePassTest extends BaseTestCase
{

    public function testProcessMethod()
    {
        $compiler = new TokenTypePass();
        $container = new ContainerBuilder();

        $container->setDefinition('oauth2_server.token_type', new Definition());

        // valid class
        $container->setParameter('oauth2_server.classes.token_type', 'OAuth2\\TokenType\\Bearer');
        $compiler->process($container);

        // not existing class
        $this->assertException(
            function() use ($compiler, $container) {
                $container->setParameter('oauth2_server.classes.token_type', 'PomPom');
                $compiler->process($container);
            },
            'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException',
            null,
            'Class PomPom does not exist.'
        );


        // invalid class
        $this->assertException(
            function() use ($compiler, $container) {
                $container->setParameter('oauth2_server.classes.token_type', 'StdClass');
                $compiler->process($container);
            },
            'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException',
            null,
            'Class StdClass does not implement OAuth2\\TokenType\\ITokenType.'
        );
    }

}
 