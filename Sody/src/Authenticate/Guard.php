<?php

namespace Sody\Authenticate;

/**
 * Guard class
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 */
class Guard
{
    /**
     * @var $_SESSION
     */
    private $session;

    /**
     * Constructor with PHP session
     *
     * @param $_SESSION $session
     */
    public function __construct($session = null)
    {
        if ($session) {
            $this->session = $session;
        }
    }

    /**
     * Returns true if user is logged in otherwise
     * false
     *
     * @return boolean
     */
    public function isAuthed()
    {
        return isset($this->session['loggedin']) and
            true === $this->session['loggedin'];
    }
}
