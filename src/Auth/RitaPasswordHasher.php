<?php

namespace Rita\Tools\Auth;

use Cake\Auth\AbstractPasswordHasher;

class RitaPasswordHasher extends AbstractPasswordHasher
{

    public function hash($password)
    {
        return md5($password);
    }

    public function check($password, $hashedPassword)
    {
        return md5($password) === $hashedPassword;
    }
}
