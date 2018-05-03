<?php

namespace App\Entity\Location;

use App\Entity\EntityFieldManager;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class Location extends EntityFieldManager
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
    private $building;

    /**
     * @ORM\Column(type="integer")
     */
    private $room;

    public function __toString()
    {
        return (string)$this->id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function setBuilding(?string $building): void
    {
        $this->building = $building;
    }

    public function getRoom(): int
    {
        return $this->room;
    }

    public function setRoom(int $room): void
    {
        $this->room = $room;
    }

    public function getFloor()
    {
        $roomStr = (string)$this->room;

        return $roomStr[0];
    }

    public function getLocation()
    {
        if($this->getRoom() == 0){
            $room = '';
        } else{
            $room = $this->getRoom();
        }

        return $this->building.$room;
    }
}