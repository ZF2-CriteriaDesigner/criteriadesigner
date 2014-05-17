<?php

namespace CriteriaDesigner\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory;

class CriteriaDesignerNavigation extends AbstractNavigationFactory
{
    public function getName() {
        return 'cd_navigation';
    }
}
