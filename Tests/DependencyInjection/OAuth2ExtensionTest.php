<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\DependencyInjection;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\OAuth2Extension;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\OAuth2ServerBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class OAuth2ExtensionTest extends AExtensionTest
{


    public function testGetAliasMethod()
    {
        $extension = new OAuth2Extension();

        $this->assertEquals('oauth2_server', $extension->getAlias());
    }


    public function testLoadMethod()
    {
        $extension = new OAuth2Extension();
        $container = new ContainerBuilder();

        $extension->load(
            [
                [
                    'user_provider' => 'test',
                    'storage' => [
                        'access_token' => 'access_token_storage_service_name',
                        'client' => 'client_storage_service_name'
                    ]
                ]
            ],
            $container
        );
    }

}
