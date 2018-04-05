<?php

namespace App\Entity;

class EntityFieldManager
{
    public function hasEmptyFields()
    {
        $nullCount = 0;

        foreach ((array)($this) as $property => $value) {
            if (!is_null($value)) {
                continue;
            }
            $property = preg_match('/^\x00(?:.*?)\x00(.+)/', $property, $matches) ? $matches[1] : $property;
            if ($property != 'id') {
                $nullCount++;
            }
        }

        return $nullCount !== 0;
    }
}
