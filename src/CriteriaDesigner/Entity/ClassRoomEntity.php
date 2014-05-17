<?php

namespace CriteriaDesigner\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ilyas Abdourahim
 *
 * @ORM\Table(name="cd_classroom")
 * @ORM\Entity
 */
class ClassRoomEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_classroom", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idClassRoom;

    /**
     * @var string
     *
     * @ORM\Column(name="label_class", type="string", length=30, nullable=true)
     */
    private $labelClass;

    /**
     * @var string
     *
     * @ORM\Column(name="level_class", type="string", length=30, nullable=true)
     */
    private $levelClass;

    /**
     * @var string
     *
     * @ORM\Column(name="section_class", type="string", length=30, nullable=true)
     */
    private $sectionClass;
    
    /**
     * @ORM\OneToMany(targetEntity="CriteriaDesigner\Entity\StudentEntity", mappedBy="classRoomStudent", fetch="EAGER")
     * @var ArrayCollection
     */
    private $studentList;
        
    /**
     * Default constructor, initializes collections
     */
    public function __construct()
    {
        $this->studentList = new ArrayCollection();
    }
    
    /**
    * Set idClassRoom
    *
    * @param int $idClassRoom
    * @return ClassRoomEntity
    */
    public function setIdClassRoom($idClassRoom)
    {
        $this->idClassRoom = $idClassRoom;
        return $this;
    }
     
    /**
    * Get idClassRoom
    *
    * @return int
    */
    public function getIdClassRoom()
    {
        return $this->idClassRoom;
    }
        
    /**
    * Set labelClass
    *
    * @param string $labelClass
    * @return ClassRoomEntity
    */
    public function setLabelClass($labelClass)
    {
        $this->labelClass = $labelClass;
        return $this;
    }
     
    /**
    * Get labelClass
    *
    * @return string
    */
    public function getLabelClass()
    {
        return $this->labelClass;
    }
    
    /**
    * Set levelClass
    *
    * @param string $levelClass
    * @return ClassRoomEntity
    */
    public function setLevelClass($levelClass)
    {
        $this->levelClass = $levelClass;
        return $this;
    }
     
    /**
    * Get levelClass
    *
    * @return string
    */
    public function getLevelClass()
    {
        return $this->levelClass;
    }
    
    /**
    * Set sectionClass
    *
    * @param string $sectionClass
    * @return ClassRoomEntity
    */
    public function setSectionClass($sectionClass)
    {
        $this->sectionClass = $sectionClass;
        return $this;
    }
     
    /**
    * Get sectionClass
    *
    * @return string
    */
    public function getSectionClass()
    {
        return $this->sectionClass;
    }

    /**
     * Set studentList
     *
     * @param ArrayCollection $studentList
     * @return ClassRoomEntity
     */
    public function setStudentList($studentList)
    {
        $this->studentList->clear();
        foreach ($studentList as $student) {
            if ($student instanceof StudentEntity) {
                $this->studentList->add($student);
            }
        }
    
        return $this;
    }
    
    /**
     * Get studentList
     *
     * @return ArrayCollection
     */
    public function getStudentList()
    {
        return $this->studentList;
    }

    public function __toString()
    {
        return $this->getSectionClass() . " " . $this->getLevelClass() . " " . $this->getLabelClass();
    }
    
}
