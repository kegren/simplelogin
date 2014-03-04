<?php

namespace Sody\Repository;

use Sody\Database\DatabaseInterface;

/**
 * Base repository class
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 */
abstract class BaseRepository
{
    /**
     * Database connection
     *
     * @var Sody\Database\DatabaseInterface $db
     */
    protected $db = null;

    public function __construct(DatabaseInterface $db = null)
    {
        if ($db) {
            $this->db = $db->connect();
        }
    }
}
