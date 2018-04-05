<?php

namespace App\Entity\Teacher;

use App\Entity\EntityFieldManager;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class Teacher extends EntityFieldManager
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    public function __toString()
    {
        return (string)$this->id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName():? string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }
}