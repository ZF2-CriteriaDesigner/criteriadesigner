<?php

namespace CriteriaDesigner\Collections\Expr;

use CriteriaDesigner\Collections\Expr\Expression;
use CriteriaDesigner\Collections\Criteria;
use CriteriaDesigner\Collections\Expr\AdvancedExpressionVisitor;
use CriteriaDesigner\Collections\InterfaceVisitor;

class DateTimeExpressionVisitor extends ExpressionVisitorHelper implements InterfaceVisitor
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
                    $objectValue = $objectValue->setTime(0, 0, 0);
                    $value = $value->setTime(0, 0, 0); 
                    return $objectValue == $value;
                };
            
            case StandardComparison::NEQ:
                return function ($object) use ($field, $value) {
                    $objectValue = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $objectValue = $objectValue->setTime(0, 0, 0);
                    $value = $value->setTime(0, 0, 0);
                    return $objectValue != $value;
                };
            
            case DateTimeComparison::BETWEEN:
                return function ($object) use ($field, $value) {
                    $value = DateTimeExpressionVisitor::checkDateBetween($value);
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);                    
                    return ($dateField >= $value[0] && $dateField <= $value[1]);
                };

            case DateTimeComparison::NOTBETWEEN:
                return function ($object) use ($field, $value) {
                    $value = DateTimeExpressionVisitor::checkDateBetween($value);
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    return !($dateField >= $value[0] && $dateField <= $value[1]);
                };

            case DateTimeComparison::YESTERDAY:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField == new \DateTime('yesterday');
                };
            
            case DateTimeComparison::TODAY:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField == $value;
                };

            case DateTimeComparison::TOMORROW:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField == new \DateTime('tomorrow');
                };
                
            // WEEK Comparison

            case DateTimeComparison::LASTWEEK:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField >= new \DateTime('monday last week') && $dateField < new \DateTime('monday this week');
                };

            case DateTimeComparison::EARLIERTHISWEEK:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField >= new \DateTime('monday this week') && $dateField < new \DateTime('today');
                };

            case DateTimeComparison::THISWEEK:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField >= new \DateTime('monday this week') && $dateField < new \DateTime('monday next week');
                };
            
            case DateTimeComparison::LATERTHISWEEK:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField > new \DateTime('today') && $dateField < new \DateTime('monday next week');
                };

            case DateTimeComparison::NEXTWEEK:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField >= new \DateTime('monday next week') && $dateField <= new \DateTime('sunday next week 23:59');
                };

            // MONTH Comparison
            
            case DateTimeComparison::LASTMONTH:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField >= new \DateTime('first day of last month 00:00') && $dateField < new \DateTime('first day of this month 00:00');
                };
            
            case DateTimeComparison::EARLIERTHISMONTH:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField >= new \DateTime('first day of this month 00:00') && $dateField < new \DateTime('today');
                };
            
            case DateTimeComparison::THISMONTH:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField >= new \DateTime('first day of this month 00:00') && $dateField < new \DateTime('first day of next month 00:00');
                };
            
            case DateTimeComparison::LATERTHISMONTH:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField > new \DateTime('today') && $dateField < new \DateTime('first day of next month 00:00');
                };
            
            case DateTimeComparison::NEXTMONTH:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField >= new \DateTime('first day of next month 00:00') && $dateField <= new \DateTime('last day of next month 23:59');
                };
            
                // YEAR Comparison

            case DateTimeComparison::THISYEAR:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField >= new \DateTime('first day of this year 00:00') && $dateField < new \DateTime('first day of next year 00:00');
                };
            
            case DateTimeComparison::LATERTHISYEAR:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField > new \DateTime('today') && $dateField < new \DateTime('first day of next year 00:00');
                };
            
            case DateTimeComparison::EARLIERTHISYEAR:
                return function ($object) use ($field, $value) {
                    /* @var $dateField \DateTime */
                    $dateField = ExpressionVisitorHelper::getObjectFieldValue($object, $field);
                    $dateField->setTime(0, 0, 0);
                    return $dateField >= new \DateTime('first day of this year 00:00') && $dateField < new \DateTime('now');
                };
            
                
            default:
                throw new \RuntimeException("Unknown comparison operator: " . $comparison->getOperator());
        }
    }

    public static function checkDateBetween($array)
    {
        if (count($array) != 2)
            throw new \RuntimeException("Between array must contains exactly two \DateTime objects error 1");
        if (!($array[0] instanceof \DateTime) || !($array[1] instanceof \DateTime))
            throw new \RuntimeException("Between array must contains exactly two \DateTime objects error 2");
        if ($array[0] > $array[1])
            return array($array[1], $array[0]);
        
        return $array;
    }

}