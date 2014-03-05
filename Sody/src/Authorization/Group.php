<?php

namespace Sody\Authorization;

use Sody\Repository\BaseRepository;
use Sody\Authorization\GroupInterface;

/**
 * Group class
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 */
class Group implements GroupInterface
{
    /**
     * BaseRepository object
     *
     * @var Sody\Repository\BaseRepository
     */
    private $repo = null;
    private $id;
    private $name;
    private $permissions = [];

    /**
     * Constructor
     *
     * @param BaseRepository $repo
     */
    public function __construct(BaseRepository $repo = null)
    {
        $this->repo = $repo;
    }

    /**
     * Sets id for the group
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns id for the group
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets name for the group
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns name for the group
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Creates a new group
     *
     * @param  string $name
     * @return boolean
     */
    public function createNewGroup($name)
    {
        // all lowercase
        $name = strtolower($name);

        return $this->repo->createNewGroup($name);
    }

    /**
     * Adds permission to group
     *
     * @param int $groupId
     * @param int $permId
     * @return boolean
     */
    public function addPermissionToGroup($groupId, $permId)
    {
        return $this->repo->addPermissionToGroup($groupId, $permId);
    }

    /**
     * Deletes permission from group
     *
     * @param  string $name
     * @return boolean
     */
    public function deletePermissionFromGroupByPermissionName($name)
    {
        return $this->repo->deletePermissionFromGroupByPermissionName($name);
    }

    /**
     * Returns all permissions for this group
     *
     * @return array
     */
    public function getPermissions()
    {
        if ($this->permissions) {
            $this->permissions = [];
        }

        $permissions = $this->repo->getPermissionsByGroupId($this->id);

        if ($permissions) {
            foreach ($permissions as $key => $permission) {
                $this->permissions[(int) $permission['id']] = (string) $permission['name'];
            }
        }

        return $this->permissions;
    }

    /**
     * Returns true if group has given permission
     * otherwise false
     *
     * @param  string  $permission
     * @return boolean
     */
    public function hasPermission($permission)
    {
        $this->getPermissions();

        return in_array($permission, $this->permissions);
    }

    /**
     * Returns all permissions
     *
     * @return mixed
     */
    public function getAllPermissions()
    {
        return $this->repo->getAllPermissions();
    }

    /**
     * Returns all groups
     *
     * @return mixed
     */
    public function getAllGroups()
    {
        return $this->repo->getAllGroups();
    }

    /**
     * Returns group name
     *
     * @return mixed
     */
    public function getNameByUserId($id)
    {
        // returns both name and id
        $data = $this->repo->getGroupByUserId($id);

        if ($data) {
            $name = $data['groupName'];

            $this->id = $data['id'];
            return $this->name = $name;
        }
    }

    /**
     * Updates a group for a specific user
     *
     * @param  int $userId
     * @param  int $groupId
     * @return boolean
     */
    public function changeUserGroup($userId, $groupId)
    {
        return $this->repo->changeUserGroup($userId, $groupId);
    }
}
