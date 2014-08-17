<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\DependencyInjection\Compiler;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\UserProviderPass;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\BaseTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use \Mockery as m;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class UserProviderPassTest extends BaseTestCase
{

    public function testProcessMethod()
    {
        $compiler = new UserProviderPass();
        $container = new ContainerBuilder();

        // valid service definition
        $service = new Definition('Symfony\\Component\\Security\\Core\\User\\InMemoryUserProvider');

        $container->setParameter('o_auth2_server.user_provider', 'name_of_service');
        $container->setDefinition('name_of_service', $service);

        $compiler->process($container);

        // not existing service
        $this->assertException(
            function() use ($container, $compiler) {
                $container->setParameter('o_auth2_server.user_provider', 'pompom');

                $compiler->process($container);
            },
            'Symfony\\Component\\Config\\Definition\\Exception\\InvalidConfigurationException',
            null,
            "Service or alias 'pompom' does not exist."
        );

        // invalid service
        $this->assertException(
            function() use ($container, $compiler) {
                $serviceMock = m::mock('Symfony\\Component\\DependencyInjection\\Definition');
                $serviceMock
                    ->shouldReceive('getClass')
                    ->andReturn('stdClass');

                $container->setParameter('o_auth2_server.user_provider', 'invalid');
                $container->setDefinition('invalid', $serviceMock);

                $compiler->process($container);
            },
            'Symfony\\Component\\Config\\Definition\\Exception\\InvalidConfigurationException',
            null,
            "Service 'invalid' has to implement Symfony\\Component\\Security\\Core\\User\\UserProviderInterface."
        );
    }

    protected function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
 