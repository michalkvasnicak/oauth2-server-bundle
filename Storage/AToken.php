<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Storage;

use OAuth2\Storage\IClient;
use OAuth2\Storage\IScope;
use OAuth2\Storage\IToken;
use OAuth2\Storage\IUser;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
abstract class AToken implements IToken
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
     * @var IUser
     */
    protected $user;


    /**
     * @var IClient
     */
    protected $client;


    /**
     * @var IScope[]
     */
    protected $scopes;

    public function __construct($id)
    {
        $this->id = (string) $id;
        $this->createdAt = new \DateTime();
        $this->scopes = [];
    }


    /**
     * Gets token identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets token creation date
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Gets user associated with this access token
     *
     * @return IUser
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Sets user asocciated with this access token
     *
     * User was using client to obtain this access token
     *
     * @param AUser $user
     */
    public function setUser(AUser $user)
    {
        $this->user = $user;
    }


    /**
     * Gets client associated with this access token
     *
     * @return IClient
     */
    public function getClient()
    {
        return $this->client;
    }


    /**
     * Sets client associated with this access token
     *
     * Client was used to obtain this access token
     *
     * @param AClient $client
     */
    public function setClient(AClient $client)
    {
        $this->client = $client;
    }


    /**
     * Gets associated scopes
     *
     * @return AScope[]|IScope[]
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Adds scopes to token
     *
     * @param AScope $scope
     */
    public function addScope(AScope $scope)
    {
        throw new \RuntimeException('This method have to be overridden.');
    }


    /**
     * Has access token associated scope?
     *
     * @param mixed $scope
     *
     * @return bool
     */
    public function hasScope($scope)
    {
        throw new \RuntimeException('This method have to be overridden.');
    }


    /**
     * Gets expiration time (timestamp)
     *
     * @return int
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }


    /**
     * Sets expiration time
     *
     * @param int $time
     */
    public function setExpiresAt($time)
    {
        $this->expiresAt = (int) $time;
    }

}
 