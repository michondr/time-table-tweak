<?php

namespace App\Controller\EzInsis\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ItemFormEntity
{
    private $day;
    private $time_from;
    private $time_until;
    private $entry;
    private $teacher_id;
    private $teacher_name;
    private $subject_id;
    private $subject_name;
    private $exact_match;

    public function __construct()
    {
        $this->exact_match = '1';
    }

    public function getDay()
    {
        return $this->day;
    }

    public function setDay($day): void
    {
        $this->day = $day;
    }

    public function getTimeFrom()
    {
        return $this->time_from;
    }

    public function setTimeFrom($time_from): void
    {
        $this->time_from = $time_from;
    }

    public function getTimeUntil()
    {
        return $this->time_until;
    }

    public function setTimeUntil($time_until): void
    {
        $this->time_until = $time_until;
    }

    public function getEntry()
    {
        return $this->entry;
    }

    public function setEntry($entry): void
    {
        $this->entry = $entry;
    }

    public function getTeacherId()
    {
        return $this->teacher_id;
    }

    public function setTeacherId($teacher_id): void
    {
        $this->teacher_id = $teacher_id;
    }

    public function getTeacherName()
    {
        return $this->teacher_name;
    }

    public function setTeacherName($teacher_name): void
    {
        $this->teacher_name = $teacher_name;
    }

    public function getSubjectId()
    {
        return $this->subject_id;
    }

    public function setSubjectId($subject_id): void
    {
        $this->subject_id = $subject_id;
    }

    public function getSubjectName()
    {
        return $this->subject_name;
    }

    public function setSubjectName($subject_name): void
    {
        $this->subject_name = $subject_name;
    }

    public function getExactMatch()
    {
        return $this->exact_match;
    }

    public function setExactMatch($exact_match): void
    {
        $this->exact_match = $exact_match;
    }

    public function toUrl(int $id, string $apiKey): string
    {
        $url = '/'.$id.'?apikey='.$apiKey;

        foreach (get_object_vars($this) as $name => $value) {
            if (is_null($value)) {
                continue;
            }
            $url .= '&'.$name.'='.$value;
        }

        return $url;
    }
}
