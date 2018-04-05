<?php

namespace App\Command\DataFiller;

class DataRow
{
    private $row;

    public function __construct(array $row)
    {
        $this->row = $row;
    }

    public function getIndent():? string
    {
        return $this->row[0];
    }

    public function getName():? string
    {
        return $this->row[1];
    }

    public function getTimeLocation():? string
    {
        return $this->row[2];
    }

    public function getAction():? string
    {
        return $this->row[3];
    }

    public function getTeacher():? string
    {
        return $this->row[4];
    }

    public function getCapacity():? int
    {
        return $this->row[5];
    }

    public function getCapacityClass1():? int
    {
        return $this->row[6];
    }

    public function getCapacityClass2():? int
    {
        return $this->row[7];
    }

    public function getCapacityClass3():? int
    {
        return $this->row[8];
    }

}