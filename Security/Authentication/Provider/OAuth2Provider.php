<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Security\Authentication\Provider;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\Security\Authentication\Token\OAuth2Token;
use OAuth2\Exception\NotAuthenticatedException;
use OAuth2\Security\Authenticator;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class OAuth2Provider implements AuthenticationProviderInterface
{


    /**
     * @var Authenticator
     */
    private $authenticator;


    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }


    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param TokenInterface $token The TokenInterface instance to authenticate
     *
     * @return TokenInterface An authenticated TokenInterface instance, never null
     *
     * @throws AuthenticationException if the authentication fails
     */
    public function authenticate(TokenInterface $token)
    {
        /** @var OAuth2Token $token  */
        try {
            $session = $this->authenticator->authenticate($token->getRequest());

            $token = new OAuth2Token($session);

            return $token;
        } catch (NotAuthenticatedException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return bool    true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuth2Token;
    }
}
 