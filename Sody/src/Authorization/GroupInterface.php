<?php

namespace Sody\Authorization;

/**
 * Group interface
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 */
interface GroupInterface
{
    public function getId();
    public function setId($id);
    public function getName();
    public function setName($name);
}
