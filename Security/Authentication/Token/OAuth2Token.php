<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Security\Authentication\Token;

use MichalKvasnicak\Bundle\OAuth2ServerBundle\Http\Request;
use OAuth2\Security\Session;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class OAuth2Token extends AbstractToken
{

    /** @var Request */
    private $request;

    /** @var Session  */
    private $session;


    public function __construct(Session $session = null)
    {
        if ($session) {
            parent::__construct($session->getUser()->getRoles()->toArray());
            $this->session = $session;
            $this->setAuthenticated(true);
            $this->setUser($session->getUser());
        }
    }


    /**
     * Gets request used to authenticate this token
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }


    /**
     * Sets request used to authenticate this token
     *
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Gets auth session
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }


    /**
     * Returns the user credentials.
     *
     * @return mixed The user credentials
     */
    public function getCredentials()
    {
        // TODO: Implement getCredentials() method.
    }
}
 