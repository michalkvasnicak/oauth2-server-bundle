<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Controller;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\Transformer\RequestTransformer;
use OAuth2\Exception\InvalidClientException;
use OAuth2\Exception\InvalidGrantException;
use OAuth2\Exception\InvalidHttpMethodException;
use OAuth2\Exception\InvalidRequestException;
use OAuth2\Exception\InvalidScopeException;
use OAuth2\Exception\MissingParameterException;
use OAuth2\Exception\InvalidUserCredentialsException;
use OAuth2\Exception\OAuth2Exception;
use OAuth2\Exception\UnauthorizedClientException;
use OAuth2\Exception\UnsupportedGrantTypeException;
use OAuth2\Storage\IScope;
use OAuth2\TokenIssuer\AccessTokenIssuer;
use OAuth2\TokenIssuer\RefreshTokenIssuer;
use OAuth2\TokenType\ITokenType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class TokenController extends Controller
{

    /**
     * @var RequestTransformer
     */
    private $requestTransformer;

    /**
     * @var AccessTokenIssuer
     */
    private $accessTokenIssuer;

    /**
     * @var RefreshTokenIssuer
     */
    private $refreshTokenIssuer;

    /**
     * @var bool
     */
    private $generateRefreshToken;

    /**
     * @var ITokenType
     */
    private $tokenType;


    public function __construct(
        RequestTransformer $requestTransformer,
        AccessTokenIssuer $accessTokenIssuer,
        RefreshTokenIssuer $refreshTokenIssuer,
        ITokenType $tokenType,
        $generateRefreshToken = false
    ) {
        $this->requestTransformer = $requestTransformer;
        $this->accessTokenIssuer = $accessTokenIssuer;
        $this->refreshTokenIssuer = $refreshTokenIssuer;
        $this->generateRefreshToken = $generateRefreshToken;
        $this->tokenType = $tokenType;
    }


    public function tokenAction(Request $request)
    {
        $request = $this->requestTransformer->transform($request);

        try {
            $accessToken = $this->accessTokenIssuer->issueToken($request);

            $scopes = [];

            foreach ($accessToken->getScopes() as $scope) {
                if ($scope instanceof IScope) {
                    $scopes[] = $scope->getId();
                } else {
                    $scopes[] = $scope;
                }
            }

            $expiresAt = $accessToken->getExpiresAt();

            if ($expiresAt instanceof \DateTime) {
                $expiresAt = $expiresAt->getTimestamp();
            }

            $data = [
                'access_token' => $accessToken->getId(),
                'expires_at' => $expiresAt,
                'expires_in' => $expiresAt - time(),
                'scope' => join(' ', $scopes),
                'token_type' => $this->tokenType->getName()
            ];

            // generate refresh token if is enabled
            if ($this->generateRefreshToken) {
                $refreshToken = $this->refreshTokenIssuer->issueToken($accessToken);

                $data['refresh_token'] = $refreshToken->getId();
            }

            return new JsonResponse(
                $data,
                200,
                [
                    'Pragma' => 'no-cache',
                    'Cache-Control' => 'no-store, private',
                    'Expires' => 0
                ]
            );
        } catch (OAuth2Exception $e) {
            $error = 'invalid_request';
            $statusCode = JsonResponse::HTTP_BAD_REQUEST;

            if ($e instanceof UnsupportedGrantTypeException) {
                $error = 'unsupported_grant_type';
            } else if ($e instanceof InvalidClientException) {
                $error = 'invalid_client';
            } else if ($e instanceof InvalidScopeException) {
                $error = 'invalid_scope';
            } else if ($e instanceof UnauthorizedClientException) {
                $error = 'unauthorized_client';
                $statusCode = JsonResponse::HTTP_UNAUTHORIZED;
            } else if ($e instanceof InvalidGrantException) {
                $error = 'invalid_grant';
                $statusCode = JsonResponse::HTTP_FORBIDDEN;
            } else if ($e instanceof InvalidUserCredentialsException) {
                $error = 'invalid_grant';
                $statusCode = JsonResponse::HTTP_UNAUTHORIZED;
            }

            return $this->createErrorResponse($error, $e->getMessage(), $statusCode);
        }
    }


    /**
     * Creates error response
     *
     * @param string $error
     * @param string $description
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    private function createErrorResponse($error, $description, $statusCode = 400)
    {
        return new JsonResponse(
            [
                'error' => $error,
                'error_description' => $description
            ],
            $statusCode,
            [
                'Pragma' => 'no-cache',
                'Cache-Control' => 'no-store, private',
                'Expires' => 0,
            ]
        );
    }

}
