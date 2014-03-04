<?php

namespace Sody\Authenticate;

use Sody\User;
use Sody\Repository\UserRepository;

/**
 * Authenticate class
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 */
class Auth
{
    const SALT = 'SokeAKjdaw12AmSjDEolM';

    /**
     * UserRepository object
     *
     * @var Sody\Repository\UserRepository
     */
    private $repo = null;

    /**
     * PHP session
     *
     * @var $_SESSION
     */
    private $session = null;

    /**
     * Whitelisted column names for later use
     *
     * @var array
     */
    private $whitelist = ['id', 'email', 'username', 'firstName', 'lastName'];

    public function __construct(UserRepository $repo = null)
    {
        $this->repo = $repo;
        $this->session = &$_SESSION;
    }

    /**
     * Checks if provided password match stored password
     *
     * @param  hash  $formPassword
     * @param  hash  $dbPassword
     * @return boolean
     */
    private function isCorrectPwdProvided($formPassword, $storedPassword)
    {
        return $formPassword == $storedPassword;
    }

    /**
     * Generates password based on provided password
     *
     * @param  string $pwd
     * @param  string $salt
     * @return string
     */
    public function generatePwd($pwd)
    {
        // blowfish
        $salt = '$2y$12$' . self::SALT . '$';

        return crypt($pwd, $salt);
    }

    /**
     * Attempts to log an user in.
     *
     * @param  string $username
     * @param  string $password
     * @return mixed
     */
    public function attempt($username = '', $password = '')
    {
        if ($username and $password) {
            $userData = $this->repo->getUserInfoByUsername($username);

            // if userData is returned, we know the user exists based
            // on username
            if ($userData) {
                $storedPwd = $userData['password'];

                $formPwd = $this->generatePwd($password);

                if ($this->isCorrectPwdProvided($formPwd, $storedPwd)) {
                    $this->session['loggedin'] = true;
                    $whitelisted = array_intersect_key($userData, array_flip($this->whitelist));

                    $this->session['user'] = serialize($whitelisted);

                    return true;
                }
            }
        }
    }

    /**
     * Logs an user out. Destroys all session data
     *
     * @return null
     */
    public function logout()
    {
        session_unset();
        // deletes session file
        session_destroy();
    }
}
