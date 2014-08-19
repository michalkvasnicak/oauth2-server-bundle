<?php

namespace MichalKvasnicak\Bundle\OAuth2ServerBundle\Storage;

use OAuth2\Storage\IScope;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * @author Michal Kvasničák <michal.kvasnicak@mink.sk>
 * @copyright Michal Kvasničák, 2014
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
abstract class AScope implements IScope, RoleInterface
{

    /**
     * @var string
     */
    protected $id;


    /**
     * Creates scope with associated id
     *
     * @param string $id
     */
    public function __construct($id)
    {
        if (!is_string($id)) {
            $type = gettype($id);

            throw new \InvalidArgumentException(
                "Scope id has to be string, $type given."
            );
        }

        $this->id = (string) $id;
    }


    /**
     * Gets scope identifier (used as identification in requests)
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        return 'ROLE_' . $this->getId();
    }

}
 