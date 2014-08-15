<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\DependencyInjection;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection\Configuration;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\BaseTestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class ConfigurationTest extends BaseTestCase
{


    public function testDefaults()
    {
        $config = $this->processConfiguration(
            [
                'user_provider' => 'test',
                'storage' => [
                    'access_token' => 'pom',
                    'client' => 'pom',
                ]
            ]
        );

        $this->assertEquals(
            [
                'access_tokens' => [
                    'lifetime' => 1209600
                ],
                'authorization_codes' => [
                    'lifetime' => 60
                ],
                'refresh_tokens' => [
                    'generate' => true,
                    'lifetime' => 2678400
                ],
                'www_realm' => 'OAuth2Server',
                'grant_types' => [
                    'authorization_code' => false,
                    'client_credentials' => false,
                    'implicit' => false,
                    'refresh_token' => false,
                    'resource_owner_password_credentials' => false
                ],
                'classes' => [
                    'token_type' => 'OAuth2\\TokenType\\Bearer'
                ],
                'user_provider' => 'test',
                'storage' => [
                    'access_token' => 'pom',
                    'client' => 'pom',
                ]
            ],
            $config
        );
    }


    public function testAccessTokensValidation()
    {
        $config = $this->processConfiguration(
            [
                'access_tokens' => ['lifetime' => 0],
                'user_provider' => 'test',
                'storage' => [
                    'access_token' => 'pom',
                    'client' => 'pom',
                ]
            ]
        );

        $this->assertEquals(
            0,
            $config['access_tokens']['lifetime']
        );

        // test invalid arguments
        foreach (['a', null, true, []] as $value) {
            $this->assertException(
                function() use ($value) {
                    $this->processConfiguration(
                        [
                            'access_tokens' => ['lifetime' => $value],
                            'user_provider' => 'test',
                            'storage' => [
                                'access_token' => 'pom',
                                'client' => 'pom',
                            ]
                        ]
                    );
                },
                'Symfony\Component\Config\Definition\Exception\InvalidTypeException'
            );
        }
    }


    public function testRefreshTokensValidation()
    {
        $config = $this->processConfiguration(
            [
                'user_provider' => 'test',
                'refresh_tokens' => ['generate' => false],
                'storage' => [
                    'access_token' => 'pom',
                    'client' => 'pom',
                ]
            ]
        );

        $this->assertFalse($config['refresh_tokens']['generate']);

        $this->assertException(
            function() {
                $this->processConfiguration(
                    [
                        'user_provider' => 'test',
                        'refresh_tokens' => ['generate' => 'a'],
                        'storage' => [
                            'access_token' => 'pom',
                            'client' => 'pom',
                        ]
                    ]
                );
            },
            'Symfony\Component\Config\Definition\Exception\InvalidTypeException'
        );
    }


    public function testWwwRealmValidation()
    {
        foreach ([10, []] as $value) {
            $this->assertException(
                function() use ($value) {
                    $this->processConfiguration(
                        [
                            'user_provider' => 'test',
                            'www_realm' => $value,
                            'storage' => [
                                'access_token' => 'pom',
                                'client' => 'pom',
                            ]
                        ]
                    );
                },
                'Symfony\Component\Config\Definition\Exception\InvalidTypeException'
            );
        }

        // cannot contain empty value
        foreach ([false, null] as $value) {
            $this->assertException(
                function() use ($value) {
                    $this->processConfiguration(
                        [
                            'user_provider' => 'test',
                            'www_realm' => $value,
                            'storage' => [
                                'access_token' => 'pom',
                                'client' => 'pom',
                            ]
                        ]
                    );
                },
                'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException'
            );
        }
    }


    public function testGrantTypesValidation()
    {
        $this->assertEquals(
            [
                'authorization_code' => false,
                'client_credentials' => false,
                'implicit' => false,
                'refresh_token' => false,
                'resource_owner_password_credentials' => true
            ],
            $this->processConfiguration(
                [
                    'user_provider' => 'test',
                    'grant_types' => [
                        'resource_owner_password_credentials' => true
                    ],
                    'storage' => [
                        'access_token' => 'pom',
                        'client' => 'pom',
                    ]
                ]
            )['grant_types']
        );


        // cannot be unknown
        $this->assertException(
            function() {
                $this->processConfiguration(
                    [
                        'user_provider' => 'test',
                        'grant_types' => [
                            'test' => true
                        ],
                        'storage' => [
                            'access_token' => 'pom',
                            'client' => 'pom',
                        ]
                    ]
                );
            },
            'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException'
        );
    }


    private function processConfiguration(array $config)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        return $processor->processConfiguration($configuration, [$config]);
    }

}
