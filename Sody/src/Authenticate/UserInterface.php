<?php

namespace Sody\Authenticate;

/**
 * User interface
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 */
interface UserInterface
{
    public function getId();
    public function getEmail();
    public function getUsername();
    public function getFirstName();
    public function getLastName();
    public function getFullName();
}
