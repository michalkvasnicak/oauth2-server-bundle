<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\DependencyInjection\Compiler;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Compiler\GrantTypesPass;
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
class GrantTypeResolverTest extends BaseTestCase
{

    public function testProcessMethod()
    {
        $grantTypeResolverDefinition = m::mock(new Definition('OAuth2\\Resolver\\GrantTypeResolver'));

        $compiler = new GrantTypesPass();
        $container = new ContainerBuilder();

        $container->setDefinition('o_auth2_server.resolver.grant_type', $grantTypeResolverDefinition);

        // invalid no grant types
        $this->assertException(
            function() use ($container, $compiler) {
                $container->setParameter('o_auth2_server.grant_types', []);
                $compiler->process($container);
            },
            'Symfony\\Component\\Config\\Definition\\Exception\\InvalidConfigurationException',
            null,
            'At least one OAuth 2.0 grant type has to be registered.'
        );


        $grantTypeDefinition = new Definition('OAuth2\\GrantType\\ResourceOwnerPasswordCredentials');
        $grantTypeDefinition->addTag('o_auth2_server.grant_type');

        $container->setDefinition('tagged_grant_type', $grantTypeDefinition);

        $compiler->process($container);

        // invalid tagged service class
        $this->assertException(
            function() use ($container, $compiler) {
                $grantTypeDefinition = new Definition('stdClass');
                $grantTypeDefinition->addTag('o_auth2_server.grant_type');

                $container->setDefinition('tagged_grant_type', $grantTypeDefinition);

                $compiler->process($container);
            },
            'Symfony\\Component\\Config\\Definition\\Exception\\InvalidConfigurationException',
            null,
            "Service 'tagged_grant_type' is not an instance of OAuth2\\GrantType\\IGrantType"
        );
    }

    protected function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
 