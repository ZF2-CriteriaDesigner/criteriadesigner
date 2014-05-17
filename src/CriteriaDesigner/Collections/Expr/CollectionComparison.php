<?php

namespace CriteriaDesigner\Collections\Expr;

use CriteriaDesigner\Collections\InterfaceVisitor;
class CollectionComparison implements Expression
{
    const NOTEXIST  = 'NOTEXIST';
    const EXIST     = 'EXIST';
    const LT        = '<';
    const LTE       = '<=';
    const EQ        = '=';
    const NEQ       = '<>';
    const GT        = '>';
    const GTE       = '>=';
    const ALL       = 'ALL';
    const BETWEEN   = 'BETWEEN';
    const NOTBETWEEN = 'NOTBETWEEN';

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
    public function __construct($field, $operator, $value = 0)
    {
        if ( ! ($value instanceof Value)) {
            if (is_array($value)) {
                if (isset($value[1])) {
                    $value[1] = (int) $value[1];
                }
                if (isset($value[2])) {
                    $value[2] = (int) $value[2];
                }
            }
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
        $visitor = new CollectionExpressionVisitor();
        return $visitor->walkComparison($this);
    }
}