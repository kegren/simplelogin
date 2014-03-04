<?php

namespace Sody\Authorization;

use Sody\Authenticate\UserInterface;

/**
 * Protector class
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 */
class Protector
{
    /**
     * @var Sody\Authenticate\UserInterface
     */
    private $user = null;

    /**
     * Constructor
     *
     * @param Sody\Authenticate\UserInterface $user
     */
    public function __construct(UserInterface $user = null)
    {
        $this->user = $user;
    }

    /**
     * Returns true if user has permission otherwise
     * false
     *
     * @param  string  $permission
     * @return boolean
     */
    public function isGranted($permission)
    {
        return $this->user->hasPermission($permission);
    }

    /**
     * Returns true if user is in group otherwise
     * false
     *
     * @param  string  $group
     * @return boolean
     */
    public function inGroup($group)
    {
        if (is_array($group)) {
            $found = false;

            foreach ($group as $iGroup) {
                if ($this->user->getGroup() == $iGroup) {
                    $found = true;
                }
            }

            return $found;
        }

        return $this->user->getGroup() == $group;
    }
}
