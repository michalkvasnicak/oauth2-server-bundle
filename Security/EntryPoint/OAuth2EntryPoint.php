<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Security\EntryPoint;

use OAuth2\TokenType\ITokenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class OAuth2EntryPoint implements AuthenticationEntryPointInterface
{

    /**
     * @var ITokenType
     */
    private $tokenType;

    /**
     * @var string
     */
    private $realm;


    public function __construct(ITokenType $tokenType, $realm)
    {
        $this->tokenType = $tokenType;
        $this->realm = $realm;
    }

    /**
     * Starts the authentication scheme.
     *
     * @param Request $request The request that resulted in an AuthenticationException
     * @param AuthenticationException $authException The exception that started the authentication process
     *
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response(
            null,
            Response::HTTP_UNAUTHORIZED,
            [
                'Pragma' => 'no-cache',
                'Cache-Control' => 'no-store, private',
                'Expires' => 0,
                'WWW-Authenticate' => sprintf(
                    '%s realm="%s", error="%", error_description="%s"',
                    $this->tokenType->getName(),
                    $this->realm,
                    'invalid_token',
                    $authException->getMessage()
                )
            ]
        );
    }
}
 