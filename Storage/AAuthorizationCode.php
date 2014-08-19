<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Storage;

use OAuth2\Storage\IAuthorizationCode;
use OAuth2\Storage\IClient;
use OAuth2\Storage\IScope;
use OAuth2\Storage\IUser;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
abstract class AAuthorizationCode implements IAuthorizationCode
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var int
     */
    protected $expiresAt;

    /**
     * @var string
     */
    protected $redirectUri;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var AScope[]|IScope[]
     */
    protected $scopes;

    /**
     * @var IUser|AUser
     */
    protected $user;

    /**
     * @var IClient|AClient
     */
    protected $client;

    public function __construct($id)
    {
        $this->id = (string) $id;
        $this->createdAt = new \DateTime();
        $this->scopes = [];
    }


    /**
     * Gets authorization code id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets expiration date in unix timestamp
     *
     * @return int
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Sets expiration date in unix timestamp
     *
     * @param $time
     */
    public function setExpiresAt($time)
    {
        $this->expiresAt = (int) $time;
    }

    /**
     * Gets redirect uri associated with this code
     *
     * @return string|null
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * Sets redirect uri
     *
     * @param string $uri
     */
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }

    /**
     * Gets scopes associated with this code
     *
     * @return AScope[]|IScope[]
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Adds scope to code
     *
     * @param AScope $scope
     */
    public function addScope(AScope $scope)
    {
        throw new \RuntimeException('This method have to be overridden.');
    }

    /**
     * Gets user associated with this code
     *
     * @return IUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets user associated with this code
     *
     * @param AUser $user
     */
    public function setUser(AUser $user)
    {
        $this->user = $user;
    }

    /**
     * Gets client associated with this code
     *
     * @return IClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets client used to obtain this code
     *
     * @param AClient $client
     */
    public function setClient(AClient $client)
    {
        $this->client = $client;
    }

    /**
     * Gets state if was provided
     *
     * @return string|null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets state used by client
     *
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = (string) $state;
    }

}
 