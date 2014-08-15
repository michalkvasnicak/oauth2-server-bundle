<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\DependencyInjection\Compiler\GrantType;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\GrantType\AuthorizationCodePass;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\BaseTestCase;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class AuthorizationCodePassTest extends BaseTestCase
{

    public function testProcessMethod()
    {
        $container = new ContainerBuilder();
        $compiler = new AuthorizationCodePass();

        // missing storage
        $this->assertException(
            function() use ($compiler, $container) {
                $compiler->process($container);
            },
            'Symfony\Component\DependencyInjection\Exception\InvalidArgumentException'
        );

        // valid
        $container->setAlias('oauth2_server.storage.authorization_code', new Alias('test'));

        $compiler->process($container);
    }

}
 