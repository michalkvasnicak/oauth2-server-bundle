<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\DependencyInjection\Compiler;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\StoragePass;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\BaseTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Mockery as m;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class StoragePassTest extends BaseTestCase
{

    public function testProcessMethod()
    {
        $compiler = new StoragePass();
        $container = new ContainerBuilder();

        // unknown storages
        $this->assertException(
            function() use ($compiler, $container) {
                $container->setParameter(
                    'oauth2_server.storage', [
                        'unknown1' => true,
                        'unknown2' => false
                    ]
                );

                $compiler->process($container);
            },
            'Symfony\\Component\\Config\\Definition\\Exception\\InvalidConfigurationException',
            null,
            "Unrecognized storages 'unknown1, unknown2'."
        );

        // invalid storage definition
        $this->assertException(
            function() use ($compiler, $container) {
                $container->setParameter(
                    'oauth2_server.storage', [
                        'authorization_code' => false
                    ]
                );

                $compiler->process($container);
            },
            'Symfony\\Component\\Config\\Definition\\Exception\\InvalidConfigurationException',
            null,
            "oauth2_server.storage.authorization_code has to be string, boolean given."
        );

        // not existing service
        $this->assertException(
            function() use ($compiler, $container) {
                $container->setParameter(
                    'oauth2_server.storage', [
                        'authorization_code' => 'test'
                    ]
                );

                $compiler->process($container);
            },
            'Symfony\\Component\\Config\\Definition\\Exception\\InvalidConfigurationException',
            null,
            "Service 'test' does not exist."
        );

        // invalid service
        $this->assertException(
            function() use ($compiler, $container) {
                $service = new Definition('stdClass');

                $container->setParameter(
                    'oauth2_server.storage', [
                        'authorization_code' => 'test'
                    ]
                );

                $container->setDefinition('test', $service);

                $compiler->process($container);
            },
            'Symfony\\Component\\Config\\Definition\\Exception\\InvalidConfigurationException',
            null,
            "Service 'test' does not implement OAuth2\\Storage\\IAuthorizationCodeStorage."
        );

        // valid and is instance of OAuth2\Storage\ITemporaryGenerator so lifetime has to be set
        $service = new Definition('MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\DependencyInjection\Compiler\Fixtures\AuthorizationCodeStorage');
        $service = m::mock($service);

        $service
            ->shouldReceive('addMethodCall')
            ->with('setLifetime', m::type('array'))
            ->once();

        $container->setParameter(
            'oauth2_server.storage', [
                'authorization_code' => 'test'
            ]
        );
        $container->setParameter('oauth2_server.authorization_codes', ['lifetime' => 60]);

        $container->setDefinition('test', $service);

        $compiler->process($container);
    }

    protected function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
 