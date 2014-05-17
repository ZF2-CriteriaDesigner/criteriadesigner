<?php

namespace CriteriaDesigner\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ClassRoom\Entity\ClassRoomEntity;

class ExamplesController extends AbstractActionController
{
    public function allclassroomsAction()
    {
        /* @var $em \Doctrine\ORM\EntityManager  */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $rep = $em->getRepository('CriteriaDesigner\Entity\ClassRoomEntity');
        $classrooms = $rep->findAll();
        
        $vm = new ViewModel(array("classrooms" => $classrooms));
        $vm->setTemplate('criteriadesigner/examples/allclassrooms');
        $this->layout('layout/criteriadesigner');  
        return $vm;
    }
    
    public function allstudentsAction()
    {
        /* @var $em \Doctrine\ORM\EntityManager  */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $rep = $em->getRepository('CriteriaDesigner\Entity\StudentEntity');
        $classrooms = $rep->findAll();
        
        $vm = new ViewModel(array("students" => $classrooms));
        $vm->setTemplate('criteriadesigner/examples/allstudents');
        $this->layout('layout/criteriadesigner');  
        return $vm;
    }
}
