<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Tests\DependencyInjection\Compiler\Fixtures;

use OAuth2\Storage\IAuthorizationCode;
use OAuth2\Storage\IAuthorizationCodeStorage;
use OAuth2\Storage\IClient;
use OAuth2\Storage\IUser;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class AuthorizationCodeStorage implements IAuthorizationCodeStorage
{

    /**
     * Gets authorization code
     *
     * @param string $code
     *
     * @return IAuthorizationCode|null
     */
    public function get($code)
    {
        // TODO: Implement get() method.
    }

    /**
     * Generates unique authorization code
     *
     * @param IUser $user
     * @param IClient $client
     * @param array $scopes
     * @param string $redirectUri
     * @param string|null $state state provided to authorization
     *
     * @return IAuthorizationCode
     */
    public function generate(IUser $user, IClient $client, array $scopes = [], $redirectUri, $state = null)
    {
        // TODO: Implement generate() method.
    }

    /**
     * Sets lifetime for generator
     *
     * @param int $lifetime
     */
    public function setLifetime($lifetime)
    {
        // TODO: Implement setLifetime() method.
    }
}
 