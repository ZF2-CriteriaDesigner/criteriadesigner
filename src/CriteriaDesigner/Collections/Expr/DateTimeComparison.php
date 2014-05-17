<?php

namespace CriteriaDesigner\Collections\Expr;

use CriteriaDesigner\Collections\Expr\Value;
use CriteriaDesigner\Collections\InterfaceVisitor;

class DateTimeComparison implements Expression
{
    const BETWEEN          = 'between';
    const NOTBETWEEN       = 'notbetween';    
    const TODAY            = 'today';
    const YESTERDAY        = 'yesterday';
    const TOMORROW         = 'tomorrow';    
    const THISWEEK         = 'thisweek';
    const EARLIERTHISWEEK  = 'earlierthisweek';
    const LATERTHISWEEK    = 'laterthisweek';
    const LASTWEEK         = 'lastweek';
    const NEXTWEEK         = 'nextweek';    
    const THISMONTH        = 'thismonth';
    const EARLIERTHISMONTH = 'earlierthismonth';
    const LATERTHISMONTH   = 'laterthismonth';
    const NEXTMONTH        = 'nextmonth';
    const LASTMONTH        = 'lastmonth';    
    const THISYEAR         = 'thisyear';
    const EARLIERTHISYEAR  = 'earlierthisyear';
    const LATERTHISYEAR    = 'laterthisyear';

    /**
     * @var string
     */
    private $field;
    
    /**
     * @var string
     */
    private $op;
    
    /**
     * @var Value
     */
    private $value;
    
    /**
     * @param string $field
     * @param string $operator
     * @param mixed  $value
     */
    public function __construct($field, $operator, $value = null)
    {
        if ( !$value) {
            $value = new Value(new \DateTime('today'));
        }

        if ( ! ($value instanceof Value)) {
            $value = new Value($value);
        }
        
        $this->field = $field;
        $this->op = $operator;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }
    
    /**
     * @return Value
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->op;
    }
    
    /**
     * {@inheritDoc}
     */
    public function toClosure()
    {
        $visitor = new DateTimeExpressionVisitor();
        return $visitor->walkComparison($this);
    }
}