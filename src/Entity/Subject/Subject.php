<?php

namespace App\Entity\Subject;

use App\Entity\EntityFieldManager;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class Subject extends EntityFieldManager
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
    private $indent;

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

    public function getIndent(): string
    {
        return $this->indent;
    }

    public function setIndent(string $indent): void
    {
        $this->indent = $indent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

}