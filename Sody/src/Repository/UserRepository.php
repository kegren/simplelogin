<?php

namespace Sody\Repository;

use Sody\User;
use Sody\Repository\BaseRepository;
use PDO;

/**
 * User repository class
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 */
class UserRepository extends BaseRepository
{
    /**
     * Returns user password and salt
     *
     * @param  string $username
     * @return mixed
     */
    public function getUserInfoByUsername($username)
    {
        try {
            $stmt = $this->db->prepare(
                'SELECT id, email, username, password, first_name as firstName,
                last_name as lastName, created_at FROM users WHERE username = :user'
            );
            $stmt->execute(
                array(
                    'user' => $username
                )
            );

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            d($e->getMessage());
        }
    }

    /**
     * Returns user password
     *
     * @param  int $id
     * @return PDO\Object
     */
    public function getUserRoleByUserId($id)
    {
        try {

            $query = 'SELECT g.name as groupName '
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
     * Returns all users
     *
     * @return PDO\Object
     */
    public function getAllUsers()
    {
        try {

            $query = "SELECT
            id, email, username, first_name as firstName, last_name as lastName FROM users";

            $stmt = $this->db->query($query);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            d($e->getMessage());
        }
    }
}
