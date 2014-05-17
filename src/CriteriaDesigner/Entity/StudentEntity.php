<?php

namespace CriteriaDesigner\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Ilyas Abdourahim
 *
 * @ORM\Table(name="cd_student")
 * @ORM\Entity
 */
class StudentEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_student", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStudent;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname_student", type="string", length=30, nullable=true)
     */
    private $firstnameStudent;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname_student", type="string", length=30, nullable=true)
     */
    private $lastnameStudent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday_student", type="datetime", nullable=false)
     */
    private $birthdayStudent;

    /**
     * @var string
     *
     * @ORM\Column(name="gender_student", type="string", length=30, nullable=true)
     */
    private $genderStudent;

    /**
     * @ORM\ManyToOne(targetEntity="CriteriaDesigner\Entity\ClassRoomEntity", inversedBy="studentList")
     * @ORM\JoinColumn(name="id_classroom", referencedColumnName="id_classroom")
     * @var ClassRoomEntity
     */
    private $classRoomStudent;
        
    /**
    * Set idStudent
    *
    * @param int $idStudent
    * @return StudentEntity
    */
    public function setIdStudent($idStudent)
    {
        $this->idStudent = $idStudent;
        return $this;
    }
     
    /**
    * Get idStudent
    *
    * @return int
    */
    public function getIdStudent()
    {
        return $this->idStudent;
    }
    
    /**
    * Set firstnameStudent
    *
    * @param string $firstnameStudent
    * @return StudentEntity
    */
    public function setFirstnameStudent($firstnameStudent)
    {
        $this->firstnameStudent = $firstnameStudent;
        return $this;
    }
     
    /**
    * Get firstnameStudent
    *
    * @return string
    */
    public function getFirstnameStudent()
    {
        return $this->firstnameStudent;
    }
    
    /**
    * Set lastnameStudent
    *
    * @param string $lastnameStudent
    * @return StudentEntity
    */
    public function setLastnameStudent($lastnameStudent)
    {
        $this->lastnameStudent = $lastnameStudent;
        return $this;
    }
     
    /**
    * Get lastnameStudent
    *
    * @return string
    */
    public function getLastnameStudent()
    {
        return $this->lastnameStudent;
    }
    
    /**
    * Set birthdayStudent
    *
    * @param \DateTime $birthdayStudent
    * @return StudentEntity
    */
    public function setBirthdayStudent($birthdayStudent)
    {
        $this->birthdayStudent = $birthdayStudent;
        return $this;
    }
     
    /**
    * Get birthdayStudent
    *
    * @return \DateTime
    */
    public function getBirthdayStudent()
    {
        return $this->birthdayStudent;
    }
    
    /**
    * Set genderStudent
    *
    * @param string $genderStudent
    * @return StudentEntity
    */
    public function setGenderStudent($genderStudent)
    {
        $this->genderStudent = $genderStudent;
        return $this;
    }
     
    /**
    * Get genderStudent
    *
    * @return string
    */
    public function getGenderStudent()
    {
        return $this->genderStudent;
    }
    
    /**
    * Set classRoomStudent
    *
    * @param \CriteriaDesigner\Entity\ClassRoomEntity $classRoomStudent
    * @return StudentEntity
    */
    public function setClassRoomStudent($classRoomStudent)
    {
        $this->classRoomStudent = $classRoomStudent;
        return $this;
    }
     
    /**
    * Get classRoomStudent
    *
    * @return \CriteriaDesigner\Entity\ClassRoomEntity
    */
    public function getClassRoomStudent()
    {
        return $this->classRoomStudent;
    }

    public function __toString()
    {
        return $this->getLastnameStudent() . " " . $this->firstnameStudent() . " " . $this->getGenderStudent();
    }
    
}
