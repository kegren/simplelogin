<?php

namespace Sody\Database;

use PDO;
use Sody\Database\DatabaseInterface;

/**
 * Simple PDO wrapper
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 */
class SimplePdo implements DatabaseInterface
{
    private $host;
    private $user;
    private $pass;
    private $name;
    private $pdo = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        // set configuration in isolation
        $configuration = function ($db) {
            $file = ROOT_PATH . 'config/database.php';

            if (file_exists($file)) {
                require $file;
            }
        };

        $configuration($this);
    }

    /**
     * Returns pdo formatted string for connection
     *
     * @return string
     */
    private function getPdoStr()
    {
        return "mysql:host={$this->host};dbname={$this->name}";
    }

    /**
     * Returns a new PDO object
     *
     * @return PDO
     */
    public function connect()
    {
        $this->pdo = new PDO($this->getPdoStr(), $this->user, $this->pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->pdo;
    }

    /**
     * Sets host
     *
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Sets name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets username
     *
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Sets password
     *
     * @param string $user
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }
}
