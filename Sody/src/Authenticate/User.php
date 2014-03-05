<?php

namespace Sody\Authenticate;

use Sody\Authenticate\UserInterface;
use Sody\Authorization\GroupInterface;
use Sody\Repository\BaseRepository;

/**
 * User class
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 */
class User implements UserInterface
{
    /**
     * BaseRepository object
     *
     * @var Sody\Repository\BaseRepository
     */
    private $repo = null;

    /**
     * Group object
     *
     * @var Sody\Authorization\GroupInterface
     */
    private $group = null;

    public $id;
    public $username;
    public $email;
    public $firstName;
    public $lastName;

    /**
     * Constructor
     *
     * @param Sody\Repository\BaseRepository $repo
     * @param Sody\Authorization\GroupInterface $group
     * @param array $userData
     */
    public function __construct(
        BaseRepository $repo = null,
        GroupInterface $group = null,
        $userData = []
    ) {
        $this->repo = $repo;
        $this->group = $group;

        if ($userData) {
            foreach ($userData as $name => $val) {
                $this->{$name} = $val;
            }
        }
    }

    /**
     * Updates object with new properties
     *
     * @param  array $userData
     * @return null
     */
    public function refresh($userData = [])
    {
        if ($userData) {
            foreach ($userData as $name => $val) {
                $this->{$name} = $val;
            }
        }
    }

    /**
     * Returns user id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Returns last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Returns full name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * Returns user group
     *
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group->getNameByUserId($this->id);
    }

    /**
     * Returns user group permissions
     *
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->group->getPermissions();
    }

    /**
     * Returns true if user and its group has permission
     * otherwise false
     *
     * @return mixed
     */
    public function hasPermission($permission)
    {
        return $this->group->hasPermission($permission);
    }
}
