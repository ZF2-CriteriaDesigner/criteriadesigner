<?php

namespace CriteriaDesigner\Collections\Expr;

use CriteriaDesigner\Collections\Expr\Expression;
use CriteriaDesigner\Collections\Criteria;
use CriteriaDesigner\Collections\InterfaceVisitor;
use CriteriaDesigner\Collections\ArrayCollection;
use CriteriaDesigner\Collections\CriteriaEx;
use CriteriaDesigner\Collections\ArrayCollectionEx;

class CollectionExpressionVisitor extends ExpressionVisitorHelper implements InterfaceVisitor
{    
    /**
     * {@inheritDoc}
     */
    public function walkComparison(Expression $comparison)
    {
        $field = $comparison->getField();
        $value = $comparison->getValue()->getValue(); // shortcut for walkValue()
        
        switch ($comparison->getOperator()) {
            case CollectionComparison::EXIST:
                return function ($object) use ($field, $value) {
                    $criteria = CollectionExpressionVisitor::checkValue($value);
                    /* @var $collection \CriteriaDesigner\Collections\ArrayCollectionEx */
                    $collection = new ArrayCollectionEx(ExpressionVisitorHelper::getObjectFieldValue($object, $field)->toArray());
                    $result = $collection->matchingEx($criteria);
                    
                    return ($result->count() != 0);
                };
            
            case CollectionComparison::ALL:
                return function ($object) use ($field, $value) {
                    $criteria = CollectionExpressionVisitor::checkValue($value);
                    /* @var $collection \CriteriaDesigner\Collections\ArrayCollectionEx */
                    $collection = new ArrayCollectionEx(ExpressionVisitorHelper::getObjectFieldValue($object, $field)->toArray());
                    $result = $collection->matchingEx($criteria);
            
                    return ($result->count() > 0 && $result->count() == $collection->count());
                };
            
            case CollectionComparison::NOTEXIST:
                return function ($object) use ($field, $value) {
                    $criteria = CollectionExpressionVisitor::checkValue($value);
                    /* @var $collection \CriteriaDesigner\Collections\ArrayCollectionEx */
                    $collection = new ArrayCollectionEx(ExpressionVisitorHelper::getObjectFieldValue($object, $field)->toArray());
                    $result = $collection->matchingEx($criteria);
            
                    return ($result->count() == 0);
                };
            
            case CollectionComparison::EQ:
                return function ($object) use ($field, $value) {
                    $criteria = CollectionExpressionVisitor::checkValue($value);
                    /* @var $collection \CriteriaDesigner\Collections\ArrayCollectionEx */
                    $collection = new ArrayCollectionEx(ExpressionVisitorHelper::getObjectFieldValue($object, $field)->toArray());
                    $result = $collection->matchingEx($criteria[0]);
            
                    return ($result->count() == $criteria[1]);
                };
            
            case CollectionComparison::NEQ:
                return function ($object) use ($field, $value) {
                    $criteria = CollectionExpressionVisitor::checkValue($value);
                    /* @var $collection \CriteriaDesigner\Collections\ArrayCollectionEx */
                    $collection = new ArrayCollectionEx(ExpressionVisitorHelper::getObjectFieldValue($object, $field)->toArray());
                    $result = $collection->matchingEx($criteria[0]);
            
                    return ($result->count() != $criteria[1]);
                };
            
            case CollectionComparison::GT:
                return function ($object) use ($field, $value) {
                    $criteria = CollectionExpressionVisitor::checkValue($value);
                    /* @var $collection \CriteriaDesigner\Collections\ArrayCollectionEx */
                    $collection = new ArrayCollectionEx(ExpressionVisitorHelper::getObjectFieldValue($object, $field)->toArray());
                    $result = $collection->matchingEx($criteria[0]);
            
                    return ($result->count() > $criteria[1]);
                };
            
            case CollectionComparison::GTE:
                return function ($object) use ($field, $value) {
                    $criteria = CollectionExpressionVisitor::checkValue($value);
                    /* @var $collection \CriteriaDesigner\Collections\ArrayCollectionEx */
                    $collection = new ArrayCollectionEx(ExpressionVisitorHelper::getObjectFieldValue($object, $field)->toArray());
                    $result = $collection->matchingEx($criteria[0]);
            
                    return ($result->count() >= $criteria[1]);
                };
            
            case CollectionComparison::LT:
                return function ($object) use ($field, $value) {
                    $criteria = CollectionExpressionVisitor::checkValue($value);
                    /* @var $collection \CriteriaDesigner\Collections\ArrayCollectionEx */
                    $collection = new ArrayCollectionEx(ExpressionVisitorHelper::getObjectFieldValue($object, $field)->toArray());
                    $result = $collection->matchingEx($criteria[0]);
            
                    return ($result->count() < $criteria[1]);
                };
            
            case CollectionComparison::LTE:
                return function ($object) use ($field, $value) {
                    $criteria = CollectionExpressionVisitor::checkValue($value);
                    /* @var $collection \CriteriaDesigner\Collections\ArrayCollectionEx */
                    $collection = new ArrayCollectionEx(ExpressionVisitorHelper::getObjectFieldValue($object, $field)->toArray());
                    $result = $collection->matchingEx($criteria[0]);
            
                    return ($result->count() <= $criteria[1]);
                };
            
            case CollectionComparison::BETWEEN:
                return function ($object) use ($field, $value) {
                    $criteria = CollectionExpressionVisitor::checkValue($value);
                    /* @var $collection \CriteriaDesigner\Collections\ArrayCollectionEx */
                    $collection = new ArrayCollectionEx(ExpressionVisitorHelper::getObjectFieldValue($object, $field)->toArray());
                    $result = $collection->matchingEx($criteria[0]);
            
                    return ($result->count() >= $criteria[1] && $result->count() <= $criteria[2]);
                };
            
            case CollectionComparison::NOTBETWEEN:
                return function ($object) use ($field, $value) {
                    $criteria = CollectionExpressionVisitor::checkValue($value);
                    /* @var $collection \CriteriaDesigner\Collections\ArrayCollectionEx */
                    $collection = new ArrayCollectionEx(ExpressionVisitorHelper::getObjectFieldValue($object, $field)->toArray());
                    $result = $collection->matchingEx($criteria[0]);
            
                    return !($result->count() >= $criteria[1] && $result->count() <= $criteria[2]);
                };
            
            default:
                throw new \RuntimeException("Unknown comparison operator: " . $comparison->getOperator());
        }
    }
    
    public static function checkValue($value)
    {
        if (($value instanceof CriteriaEx))
            return $value;
        if (is_array($value) && count($value) == 2 && ($value[0] instanceof CriteriaEx) && (is_int($value[1])))
            return $value;
        if (is_array($value) && count($value) == 3 && ($value[0] instanceof CriteriaEx) && is_int($value[1]) && is_int($value[2]))
            return $value;
                
        throw new \RuntimeException("Value must be an object of class CriteriaDesigner\Collections\CriteriaEx or an array with the first element of class CriteriaDesigner\Collections\Criteria and one or two of type integer");
    }
}