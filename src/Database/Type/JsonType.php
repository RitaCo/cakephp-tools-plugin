<?php
namespace RitaTools\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;

class JsonType extends Type
{

    public function toPHP($value, Driver $driver)
    {
        if ($value === null) {
            return [];
        }
        return json_decode($value, true);
    }

    public function toDatabase($value, Driver $driver)
    {
        return json_encode($value);
    }
}
