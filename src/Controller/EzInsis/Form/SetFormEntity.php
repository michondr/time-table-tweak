<?php

namespace App\Controller\EzInsis\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SetFormEntity
{
    private $name;
    private $study_period;
    private $department;
    private $study_form;
    private $beginning;
    private $end;
    private $exact_match;

    public function __construct()
    {
        $this->exact_match = '1';
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStudyPeriod()
    {
        return $this->study_period;
    }

    /**
     * @param mixed $study_period
     */
    public function setStudyPeriod($study_period): void
    {
        $this->study_period = $study_period;
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department): void
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getStudyForm()
    {
        return $this->study_form;
    }

    /**
     * @param mixed $study_form
     */
    public function setStudyForm($study_form): void
    {
        $this->study_form = $study_form;
    }

    /**
     * @return mixed
     */
    public function getBeginning()
    {
        return $this->beginning;
    }

    /**
     * @param mixed $beginning
     */
    public function setBeginning($beginning): void
    {
        $this->beginning = $beginning;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end): void
    {
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getExactMatch()
    {
        return $this->exact_match;
    }

    /**
     * @param mixed $exact_match
     */
    public function setExactMatch($exact_match): void
    {
        $this->exact_match = $exact_match;
    }

    public function toUrl(string $apiKey)
    {
        $url = '?apikey='.$apiKey;

        foreach (get_object_vars($this) as $name => $value) {
            if (is_null($value)) {
                continue;
            }
            $url .= '&'.$name.'='.$value;
        }

        return $url;
    }
}
