<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Security;

use OAuth2\Security\IUserAuthenticator;
use OAuth2\Storage\IUser;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class UserAuthenticator implements IUserAuthenticator
{

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;


    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        UserProviderInterface $userProvider
    )
    {
        $this->encoderFactory = $encoderFactory;
        $this->userProvider = $userProvider;
    }

    /**
     * @return EncoderFactoryInterface
     */
    public function getEncoderFactory()
    {
        return $this->encoderFactory;
    }

    /**
     * @return UserProviderInterface
     */
    public function getUserProvider()
    {
        return $this->userProvider;
    }

    /**
     * Authenticates user and returns
     *
     * @param string $username
     * @param string $password
     *
     * @return IUser|null
     */
    public function authenticate($username, $password)
    {
        try {
            $user = $this->userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            return null;
        }

        $valid = $this->encoderFactory->getEncoder($user)->isPasswordValid(
            $user->getPassword(),
            $password,
            $user->getSalt()
        );

        if ($valid) {
            return $user;
        }

        return null;
    }

}
