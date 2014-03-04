<?php

namespace Sody\Repository;

use Sody\User;
use Sody\Repository\BaseRepository;
use PDO;

/**
 * Group repository class
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 */
class GroupRepository extends BaseRepository
{
    /**
     * Returns a specific group
     *
     * @param  int $id
     * @return mixed
     */
    public function getGroupByUserId($id)
    {
        try {
            $query = 'SELECT g.name as groupName, g.id '
                   . 'FROM groups g '
                   . 'JOIN user_group ug '
                   . 'ON ug.user_id = :id '
                   . 'WHERE g.id = ug.group_id ';

            $stmt = $this->db->prepare($query);
            $stmt->execute(array('id' => $id));

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            d($e->getMessage());
        }
    }

    /**
     * Deletes permission
     *
     * @param  string $name
     * @return mixed
     */
    public function deletePermissionFromGroupByPermissionName($name)
    {
        try {
            $query = 'DELETE gp FROM group_permission AS gp '
                   . 'JOIN permissions p '
                   . 'ON p.name = :name '
                   . 'WHERE gp.permission_id = p.id';

            $stmt = $this->db->prepare($query);
            $stmt->execute(array('name' => $name));

            return $stmt->rowCount();
        } catch (Exception $e) {
            d($e->getMessage());
        }
    }

    /**
     * Adds a new group
     *
     * @param  string $name
     * @return mixed
     */
    public function createNewGroup($name)
    {
        try {
            $query = 'INSERT INTO groups (name) VALUES(:name)';

            $stmt = $this->db->prepare($query);
            $stmt->execute(array('name' => $name));

            return $stmt->rowCount();
        } catch (Exception $e) {
            d($e->getMessage());
        }
    }

    /**
     * Updates the user group.
     *
     * @param  int $userId
     * @param  int $groupId
     * @return boolean
     */
    public function changeUserGroup($userId, $groupId)
    {
        try {
            $query = 'UPDATE user_group SET user_id = :userId, group_id = :groupId WHERE user_id = :userId';

            $stmt = $this->db->prepare($query);
            $stmt->execute(
                array(
                    'userId' => $userId,
                    'groupId' => $groupId
                )
            );

            return $stmt->rowCount();
        } catch (Exception $e) {
            d($e->getMessage());
        }
    }

    /**
     * Adds a new permission
     *
     * @param  int $groupId
     * @param  int $permId
     * @return mixed
     */
    public function addPermissionToGroup($groupId, $permId)
    {
        try {
            $query = 'INSERT INTO group_permission VALUES(:groupId, :permId)';

            $stmt = $this->db->prepare($query);
            $stmt->execute(
                array(
                    'groupId' => $groupId,
                    'permId' => $permId
                )
            );

            return $stmt->rowCount();
        } catch (Exception $e) {
            d($e->getMessage());
        }
    }

    /**
     * Returns permissions for this group
     *
     * @param  int $id
     * @return mixed
     */
    public function getPermissionsByGroupId($id)
    {
        try {
            $query = 'SELECT p.name, p.id '
                   . 'FROM permissions p '
                   . 'JOIN group_permission gp '
                   . 'ON gp.group_id = :id '
                   . 'WHERE p.id = gp.permission_id ';

            $stmt = $this->db->prepare($query);
            $stmt->execute(array('id' => $id));

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            d($e->getMessage());
        }
    }

    /**
     * Returns all permissions
     *
     * @return PDO\Object
     */
    public function getAllPermissions()
    {
        try {
            $query = "SELECT id, name FROM permissions";

            $stmt = $this->db->query($query);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            d($e->getMessage());
        }
    }

    /**
     * Returns all groups
     *
     * @return PDO\Object
     */
    public function getAllGroups()
    {
        try {
            $query = "SELECT id, name FROM groups";

            $stmt = $this->db->query($query);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            d($e->getMessage());
        }
    }
}
