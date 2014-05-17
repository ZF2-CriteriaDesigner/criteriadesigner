<?php

namespace CriteriaDesigner\Collections;

use CriteriaDesigner\Collections\Criteria;
use CriteriaDesigner\Collections\Expr\ExpressionVisitorHelper;
use Doctrine\Common\Collections\ArrayCollection;

class ArrayCollectionEx extends ArrayCollection
{    
    /**
     * Selects all elements from a selectable that match the expression and
     * returns a new collection containing these elements.
     *
     * @param CriteriaEx $criteria
     *
     * @return Collection
     */
    public function matchingEx(CriteriaEx $criteria)
    {
        $expr     = $criteria->getWhereExpression();
        $filtered = $this->toArray();
    
        if ($expr) {
            $filter   = $expr->toClosure();
            $filtered = array_filter($filtered, $filter);
        }
        
        if ($orderings = $criteria->getOrderings()) {
            $next = null;
            foreach (array_reverse($orderings) as $field => $ordering) {
                $next = ExpressionVisitorHelper::sortByField($field, $ordering == 'DESC' ? -1 : 1, $next);
            }
    
            usort($filtered, $next);
        }
    
        $offset = $criteria->getFirstResult();
        $length = $criteria->getMaxResults();
    
        if ($offset || $length) {
            $filtered = array_slice($filtered, (int)$offset, $length);
        }
    
        return new static($filtered);
    }
}