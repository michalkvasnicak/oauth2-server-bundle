<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('oauth2_server');

        $nodes = $rootNode->children();

        $authorizationCodesNode = $nodes->arrayNode('authorization_codes');
        $accessTokensNode = $nodes->arrayNode('access_tokens');
        $refreshTokensNode = $nodes->arrayNode('refresh_tokens');
        $wwwRealmNode = $nodes->scalarNode('www_realm');
        $userProviderNode = $nodes->scalarNode('user_provider');
        $grantTypesNode = $nodes->arrayNode('grant_types');
        $classesNode = $nodes->arrayNode('classes');
        $storageNode = $nodes->arrayNode('storage');

        $authorizationCodesNode
            ->addDefaultsIfNotSet()
            ->children()
                ->integerNode('lifetime')
                    ->defaultValue(60);

        $classesNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('token_type')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->defaultValue('OAuth2\\TokenType\\Bearer')
                ->end()
            ->end();

        $wwwRealmNode
            ->cannotBeEmpty()
            ->defaultValue('OAuth2Server')
            ->validate()
                ->always(
                    function($value) {
                        $type = gettype($value);

                        if (!is_string($value)) {
                            throw new InvalidTypeException(
                                "oauth2_server.www_realm has to be string, '$type'' given."
                            );
                        }

                        return $value;
                    }
                );

        $userProviderNode
            ->isRequired()
            ->cannotBeEmpty()
            ->validate()
                ->always(
                    function($value) {
                        $type = gettype($value);

                        if (!is_string($value)) {
                            throw new InvalidTypeException(
                                "oauth2_server.user_provider has to be string, '$type' given."
                            );
                        }

                        return $value;
                    }
                );

        $accessTokensNode
            ->addDefaultsIfNotSet()
            ->children()
                ->integerNode('lifetime')
                    ->isRequired()
                    ->defaultValue(1209600)
                ->end()
            ->end();

        $refreshTokensNode
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('generate')
                    ->isRequired()
                    ->defaultTrue()
                ->end()
                ->integerNode('lifetime')
                    ->defaultValue(2678400)
                ->end()
            ->end();

        $grantTypesNode
            ->addDefaultsIfNotSet()
            ->cannotBeEmpty()
            ->children()
                ->booleanNode('authorization_code')
                    ->cannotBeEmpty()
                    ->defaultFalse()
                ->end()
                ->booleanNode('client_credentials')
                    ->cannotBeEmpty()
                    ->defaultFalse()
                ->end()
                ->booleanNode('implicit')
                    ->cannotBeEmpty()
                    ->defaultFalse()
                ->end()
                ->booleanNode('refresh_token')
                    ->cannotBeEmpty()
                    ->defaultFalse()
                ->end()
                ->booleanNode('resource_owner_password_credentials')
                    ->cannotBeEmpty()
                    ->defaultFalse()
                ->end()
            ->end();

        $storageNode
            ->isRequired()
            ->ignoreExtraKeys()
            ->children()
                ->scalarNode('access_token')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('client')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
