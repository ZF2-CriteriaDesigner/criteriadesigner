<?php

namespace CriteriaDesigner\Collections\Expr;

use CriteriaDesigner\Collections\Expr\StandardComparison;
use CriteriaDesigner\Collections\Expr\Value;
use CriteriaDesigner\Collections\CriteriaEx;

class AdvancedExpressionBuilder
{    
    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return StandardComparison
     */
    public function eq($field, $value)
    {
        return new StandardComparison($field, StandardComparison::EQ, new Value($value));
    }
    
    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return StandardComparison
     */
    public function gt($field, $value)
    {
        return new StandardComparison($field, StandardComparison::GT, new Value($value));
    }
    
    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return StandardComparison
     */
    public function lt($field, $value)
    {
        return new StandardComparison($field, StandardComparison::LT, new Value($value));
    }
    
    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return StandardComparison
     */
    public function gte($field, $value)
    {
        return new StandardComparison($field, StandardComparison::GTE, new Value($value));
    }
    
    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return StandardComparison
     */
    public function lte($field, $value)
    {
        return new StandardComparison($field, StandardComparison::LTE, new Value($value));
    }
    
    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return StandardComparison
     */
    public function neq($field, $value)
    {
        return new StandardComparison($field, StandardComparison::NEQ, new Value($value));
    }

    /**
     * @param string $field
     *
     * @return StandardComparison
     */
    public function isNull($field)
    {
        return new StandardComparison($field, StandardComparison::EQ, new Value(null));
    }

    /**
     * @param string $field
     *
     * @return StandardComparison
     */
    public function isNotNull($field)
    {
        return new StandardComparison($field, StandardComparison::NEQ, new Value(null));
    }
    
    /**
     * @param string $field
     * @param mixed  $values
     *
     * @return StandardComparison
     */
    public function in($field, $values)
    {
        if (!is_array($values))
            $values = array($values);
        return new StandardComparison($field, StandardComparison::IN, new Value($values));
    }
    
    /**
     * @param string $field
     * @param mixed  $values
     *
     * @return StandardComparison
     */
    public function notIn($field, $values)
    {
        if (!is_array($values))
            $values = array($values);
        return new StandardComparison($field, StandardComparison::NIN, new Value($values));
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return StandardComparison
     */
    public function contains($field, $value)
    {
        return new StandardComparison($field, StandardComparison::CONTAINS, new Value($value));
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return StandardComparison
     */
    public function notcontains($field, $value)
    {
        return new StandardComparison($field, StandardComparison::NOTCONTAINS, new Value($value));
    }

    /**
     * @param string $field
     * @param array $array
     * @return StandardComparison
     */
    public function between($field, $array)
    {
        return new StandardComparison($field, StandardComparison::BETWEEN, $array);
    }

    /**
     * @param string $field
     * @param array $array
     * @return StandardComparison
     */
    public function notbetween($field, $array)
    {
        return new StandardComparison($field, StandardComparison::NOTBETWEEN, $array);
    }

    /**
     * @param string $field
     * @param string $value
     * @return StandardComparison
     */
    public function startWith($field, $value)
    {
        return new StandardComparison($field, StandardComparison::STARTWITH, $value);
    }

    /**
     * @param string $field
     * @param string $value
     * @return StandardComparison
     */
    public function notStartWith($field, $value)
    {
        return new StandardComparison($field, StandardComparison::NOTSTARTWITH, $value);
    }

    /**
     * @param string $field
     * @param string $value
     * @return StandardComparison
     */
    public function endWith($field, $value)
    {
        return new StandardComparison($field, StandardComparison::ENDWITH, $value);
    }

    /**
     * @param string $field
     * @param string $value
     * @return StandardComparison
     */
    public function notEndWith($field, $value)
    {
        return new StandardComparison($field, StandardComparison::NOTENDWITH, $value);
    }

    /**
     * @param string $field
     * @return StandardComparison
     */
    public function connected($field)
    {
        return new StandardComparison($field, StandardComparison::EQ, CriteriaEx::getIdConnected());
    }

    /**
     * @param string $field
     * @param array $array
     * @return StandardComparison
     */
    public function notConnected($field)
    {
        return new StandardComparison($field, StandardComparison::NEQ, CriteriaEx::getIdConnected());
    }

    /**
     * @param string $field
     * @param \DateTime $value
     * @return DateTimeComparison
     */
    public function dateEq($field, $value)
    {
        return new DateTimeComparison($field, StandardComparison::EQ, $value);
    }

    /**
     * @param string $field
     * @param \DateTime $value
     * @return DateTimeComparison
     */
    public function dateNeq($field, $value)
    {
        return new DateTimeComparison($field, StandardComparison::NEQ, $value);
    }
    
    /**
     * @param string $field
     * @param array $array
     * @return DateTimeComparison
     */
    public function betweenDate($field, $array)
    {
        return new DateTimeComparison($field, DateTimeComparison::BETWEEN, $array);
    }
    
    /**
     * @param string $field
     * @param array $array
     * @return StandardComparison
     */
    public function notbetweenDate($field, $array)
    {
        return new DateTimeComparison($field, DateTimeComparison::NOTBETWEEN, $array);
    }
    
    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function today($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::TODAY);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function yesterday($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::YESTERDAY);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function tomorrow($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::TOMORROW);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function thisweek($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::THISWEEK);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function laterthisweek($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::LATERTHISWEEK);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function earlierthisweek($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::EARLIERTHISWEEK);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function lastweek($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::LASTWEEK);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function nextweek($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::NEXTWEEK);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function thismonth($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::THISMONTH);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function earlierthismonth($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::EARLIERTHISMONTH);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function laterthismonth($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::LATERTHISMONTH);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function lastmonth($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::LASTMONTH);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function nextmonth($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::NEXTMONTH);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function thisyear($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::THISYEAR);
    }
    
    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function earlierthisyear($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::EARLIERTHISYEAR);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function laterthisyear($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::LATERTHISYEAR);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function beyondthisyear($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::BEYONDTHISYEAR);
    }

    /**
     * @param string $field
     * @return DateTimeComparison
     */
    public function priorthisyear($field)
    {
        return new DateTimeComparison($field, DateTimeComparison::PRIORTHISYEAR);
    }

    /**
     * @param string $field
     * @param \CriteriaDesigner\Collections\CriteriaEx $criteria
     *
     * @return CollectionComparison
     */
    public function countNOTEXIST($field, $criteria, $value = null)
    {
        return new CollectionComparison($field, CollectionComparison::NOTEXIST, $criteria);
    }
    
    /**
     * @param string $field
     * @param CriteriaDesigner\Collections\Criteria $criteria
     *
     * @return CollectionComparison
     */
    public function countEXIST($field, $criteria, $value = null)
    {
        return new CollectionComparison($field, CollectionComparison::EXIST, $criteria);
    }
    
    /**
     * @param string $field
     * @param \CriteriaDesigner\Collections\CriteriaEx $criteria
     * @param int
     *
     * @return CollectionComparison
     */
    public function countLT($field, $criteria, $value)
    {
        return new CollectionComparison($field, CollectionComparison::LT, array($criteria, $value));
    }
    
    /**
     * @param string $field
     * @param \CriteriaDesigner\Collections\CriteriaEx $criteria
     * @param int
     *
     * @return CollectionComparison
     */
    public function countLTE($field, $criteria, $value)
    {
        return new CollectionComparison($field, CollectionComparison::LTE, array($criteria, $value));
    }
    
    /**
     * @param string $field
     * @param \CriteriaDesigner\Collections\CriteriaEx $criteria
     * @param int
     *
     * @return CollectionComparison
     */
    public function countEQ($field, $criteria, $value)
    {
        return new CollectionComparison($field, CollectionComparison::EQ, array($criteria, $value));
    }
    
    /**
     * @param string $field
     * @param \CriteriaDesigner\Collections\CriteriaEx $criteria
     * @param int
     *
     * @return CollectionComparison
     */
    public function countNEQ($field, $criteria, $value)
    {
        return new CollectionComparison($field, CollectionComparison::NEQ, array($criteria, $value));
    }
    
    /**
     * @param string $field
     * @param \CriteriaDesigner\Collections\CriteriaExEx $criteria
     * @param int
     *
     * @return CollectionComparison
     */
    public function countGT($field, $criteria, $value)
    {
        return new CollectionComparison($field, CollectionComparison::GT, array($criteria, $value));
    }
    
    /**
     * @param string $field
     * @param \CriteriaDesigner\Collections\CriteriaEx $criteria
     * @param int
     *
     * @return CollectionComparison
     */
    public function countGTE($field, $criteria, $value)
    {
        return new CollectionComparison($field, CollectionComparison::GTE, array($criteria, $value));
    }
    
    /**
     * @param string $field
     * @param \CriteriaDesigner\Collections\CriteriaEx $criteria
     *
     * @return CollectionComparison
     */
    public function countALL($field, $criteria, $value = null)
    {
        return new CollectionComparison($field, CollectionComparison::ALL, $criteria);
    }
    

    private static $conditions = array(
                "bool" => array("eq", "neq"),
                "id"   => array("connected", "notConnected", "eq", "neq"),
                "int"  => array("eq", "neq", "gt", "gte", "lt", "lte", "between",
                    "notbetween", "startWith", "notStartWith", "contains", "notcontains",
                    "endWith", "notEndWith", "in", "notIn", "isNull", "isNotNul"
                ),
                "string" => array("eq", "neq", "startWith", "notStartWith", "contains",
                    "notcontains", "endWith", "notEndWith", "in", "notIn", "isNull", "isNotNul"
                ),
                "list" => array("eq", "neq", "startWith", "notStartWith", "contains",
                    "notcontains", "endWith", "notEndWith", "in", "notIn", "isNull", "isNotNul"
                ),
                "collection" => array("countEXIST", "countNOTEXIST", "countALL", "countEQ", "countNEQ", "countGT",
                    "countGTE", "countLT", "countLTE", "countBETWEEN", "countNOTBETWEEN"
                ),
                "date" => array("dateEq", "dateNeq", "gt", "gte", "lt", "lte",
                    "betweenDate", "notbetweenDate", "isNull", "isNotNul",
                    "today", "yesterday", "tomorrow",
                    "lastweek", "earlierthisweek", "thisweek", "laterthisweek", "nextweek",
                    "lastmonth", "earlierthismonth", "thismonth", "laterthismonth", "nextmonth",
                    "earlierthisyear", "thisyear", "laterthisyear"
                )
            );
    
    public static function createComparison($type = "string", $pos = 0, $field, $parameter = null, $criteria = null)
    {
        $method = self::$conditions[$type][$pos];
        $exprBulider = new static();       
        if ($type == 'date') {
            $parameter = array_map(function($ndate) { return new \DateTime($ndate); } , $parameter);
        }

        
        if (method_exists($exprBulider, $method)) {
            $r = new \ReflectionMethod('\CriteriaDesigner\Collections\Expr\AdvancedExpressionBuilder', $method);
            if (is_array($parameter) && count($parameter) == 1) {
                $parameter = $parameter[0];
            }
                        
            switch ($r->getNumberOfParameters()) {
                case 1: return $exprBulider->{$method}($field);
                case 2: return $exprBulider->{$method}($field, $parameter);
                case 3: return $exprBulider->{$method}($field, $criteria, $parameter);
            }
        };
    }
    
}