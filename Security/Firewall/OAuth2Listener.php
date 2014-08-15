<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Security\Firewall;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\Security\Authentication\Token\OAuth2Token;
use MichalKvasnicak\Bundle\OAuth2ServerBundle\Transformer\RequestTransformer;
use OAuth2\Exception\UnsupportedTokenTypeException;
use OAuth2\Resolver\ITokenTypeResolver;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class OAuth2Listener implements ListenerInterface
{


    /**
     * @var ITokenTypeResolver
     */
    private $tokenTypeResolver;

    /**
     * @var RequestTransformer
     */
    private $requestTransformer;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * @var AuthenticationEntryPointInterface
     */
    private $authenticationEntryPoint;


    public function __construct(
        ITokenTypeResolver $tokenTypeResolver,
        RequestTransformer $requestTransformer,
        SecurityContextInterface $securityContext,
        AuthenticationManagerInterface $authenticationManager,
        AuthenticationEntryPointInterface $authenticationEntryPoint
    )
    {
        $this->tokenTypeResolver = $tokenTypeResolver;
        $this->requestTransformer = $requestTransformer;
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->authenticationEntryPoint = $authenticationEntryPoint;
    }


    /**
     * This interface must be implemented by firewall listeners.
     *
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        $symfonyRequest = $event->getRequest();
        $request = $this->requestTransformer->transform($symfonyRequest);

        try {
            $tokenType = $this->tokenTypeResolver->resolve($request);
        } catch(UnsupportedTokenTypeException $e) {
            // this is not an OAuth2 request or is using not supported token type
            return;
        }

        $token = new OAuth2Token();
        $token->setRequest($request);

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->securityContext->setToken($authToken);
            return;
        } catch (AuthenticationException $e) {
            $event->setResponse(
                $this->authenticationEntryPoint->start(
                    $symfonyRequest, $e
                )
            );
        }
    }
}
 