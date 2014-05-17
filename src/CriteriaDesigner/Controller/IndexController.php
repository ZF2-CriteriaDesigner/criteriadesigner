<?php

namespace CriteriaDesigner\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{    
    public function indexAction()
    {
        $vm = new ViewModel();
        $vm->setTemplate('criteriadesigner/index/index');
        $this->layout('layout/criteriadesigner');  
        return $vm;
    }
    
    public function criteriadesignerAction()
    {
        $criteriaArray = null;
        $data = null;
        $entites = null;
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $data = $post['formCriteria']['data'];
            $criteriaArray = \Zend\Json\JSON::decode($data, 1);

            $repName = "CriteriaDesigner\Entity\StudentEntity";
            if ($nomEntite = $criteriaArray["entity"] == "classEntity") 
                $repName = "CriteriaDesigner\Entity\ClassRoomEntity";
            
            /* @var $em \Doctrine\ORM\EntityManager  */
            $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $rep = $em->getRepository($repName);
            $entites = $rep->findAll();            
        }
        
        $vm = new ViewModel(array('criteriaArray' => $criteriaArray, 'data' => $data, 'entites' => $entites));
        $vm->setTemplate('criteriadesigner/index/criteriadesigner');
        $this->layout('layout/criteriadesigner');  
        return $vm;
    }
    
    public function schemabuilderAction()
    {
        $vm = new ViewModel();
        $vm->setTemplate('criteriadesigner/index/schemabuilder');
        $this->layout('layout/criteriadesigner');  
        return $vm;
    }
}
