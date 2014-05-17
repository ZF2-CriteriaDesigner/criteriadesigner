<?php

namespace CriteriaDesigner\Collections\Expr;

use CriteriaDesigner\Collections\InterfaceVisitor;
use CriteriaDesigner\Collections\CriteriaEx;

class StandardExpressionVisitor extends ExpressionVisitorHelper implements InterfaceVisitor
{
    /**
     * {@inheritDoc}
     */
    public function walkComparison(Expression $comparison)
    {
        $field = $comparison->getField();
        $value = $comparison->getValue()->getValue(); // shortcut for walkValue()

        switch ($comparison->getOperator()) {
            case StandardComparison::EQ:
                return function ($object) use ($field, $value) {
                    $objectValue = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $objectValue = StandardExpressionVisitor::tolowerCase($objectValue);
                    $value = StandardExpressionVisitor::tolowerCase($value);
                    return $objectValue === $value;
                };
            
            case StandardComparison::NEQ:
                return function ($object) use ($field, $value) {
                    $objectValue = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $objectValue = StandardExpressionVisitor::tolowerCase($objectValue);
                    $value = StandardExpressionVisitor::tolowerCase($value);
                    return $objectValue !== $value;
                };

            case StandardComparison::LT:
                return function ($object) use ($field, $value) {
                    return ExpressionVisitorHelper::getObjectFieldValue($object, $field) < $value;
                };

            case StandardComparison::LTE:
                return function ($object) use ($field, $value) {
                    return ExpressionVisitorHelper::getObjectFieldValue($object, $field) <= $value;
                };

            case StandardComparison::GT:
                return function ($object) use ($field, $value) {
                    return ExpressionVisitorHelper::getObjectFieldValue($object, $field) > $value;
                };

            case StandardComparison::GTE:
                return function ($object) use ($field, $value) {
                    return ExpressionVisitorHelper::getObjectFieldValue($object, $field) >= $value;
                };

            case StandardComparison::IN:
                return function ($object) use ($field, $value) {
                    $objectValue = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    return in_array($objectValue, $value);
                };

            case StandardComparison::NIN:
                return function ($object) use ($field, $value) {
                    $objectValue = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    return !in_array($objectValue, $value);
                };

            case StandardComparison::CONTAINS:
                return function ($object) use ($field, $value) {
                    $objectValue = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $objectValue = StandardExpressionVisitor::tolowerCase($objectValue);
                    $value = StandardExpressionVisitor::tolowerCase($value);                    
                    return false !== stripos($objectValue, $value);
                };

            case StandardComparison::NOTCONTAINS:
                return function ($object) use ($field, $value) {
                    $objectValue = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $objectValue = StandardExpressionVisitor::tolowerCase($objectValue);
                    $value = StandardExpressionVisitor::tolowerCase($value);                    
                    return false === stripos($objectValue, $value);
                };

            case StandardComparison::STARTWITH:
                return function ($object) use ($field, $value) {
                    $objectValue = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $objectValue = StandardExpressionVisitor::tolowerCase($objectValue);
                    $value = StandardExpressionVisitor::tolowerCase($value);
                    return preg_match("/^($value)/i", $objectValue);
                };

            case StandardComparison::NOTSTARTWITH:
                return function ($object) use ($field, $value) {
                    $objectValue = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $objectValue = StandardExpressionVisitor::tolowerCase($objectValue);
                    $value = StandardExpressionVisitor::tolowerCase($value);
                    return !preg_match("/^($value)/i", $objectValue);
                };

            case StandardComparison::ENDWITH:
                return function ($object) use ($field, $value) {
                    $objectValue = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $objectValue = StandardExpressionVisitor::tolowerCase($objectValue);
                    $value = StandardExpressionVisitor::tolowerCase($value);
                    return preg_match("/($value)$/", $objectValue);
                };

            case StandardComparison::NOTENDWITH:
                return function ($object) use ($field, $value) {
                    return !preg_match("/($value)$/", ExpressionVisitorHelper::getObjectFieldValue($object, $field));
                };

            case StandardComparison::BETWEEN:
                return function ($object) use ($field, $value) {
                    $result = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
            
                    return ($result >= $value[0] && $result <= $value[1]);
                };

            case StandardComparison::NOTBETWEEN:
                return function ($object) use ($field, $value) {
                    $result = ClosureExpressionVisitor::getObjectFieldValue($object, $field);
                    return !($result >= $value[0] && $result <= $value[1]);
                };
            
            default:
                throw new \RuntimeException("Unknown comparison operator: " . $comparison->getOperator());
        }
    }
    
    public static function tolowerCase($value)
    {
        if (is_string($value))
            return trim(strtolower($value));
        
        return $value;
    }

}
