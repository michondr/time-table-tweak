<?php

namespace App\Entity\Subject;

use App\Entity\EntityFieldManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Subject extends EntityFieldManager
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=10)
     */
    private $indent;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

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

    public function getViewName()
    {
        return $this->getIndent().' '.$this->getName();
    }

    public function __toString()
    {
        return (string)$this->getIndent();
    }
}