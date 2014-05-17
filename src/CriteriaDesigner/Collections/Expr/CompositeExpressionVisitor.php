<?php

namespace CriteriaDesigner\Collections\Expr;

use CriteriaDesigner\Collections\InterfaceVisitor;

class CompositeExpressionVisitor extends ExpressionVisitorHelper implements InterfaceVisitor
{
    /**
     * {@inheritDoc}
     */
    public function walkComparison(Expression $expr)
    {
        $expressionList = array();
    
        foreach ($expr->getExpressionList() as $child) {
            $childFilter = $child->toClosure();
            $expressionList[] = $childFilter;
        }
    
        switch($expr->getType()) {
            case CompositeExpression::TYPE_AND:
                return $this->andExpressions($expressionList);
    
            case CompositeExpression::TYPE_OR:
                return $this->orExpressions($expressionList);

            case CompositeExpression::TYPE_NAND:
                return $this->nandExpressions($expressionList);
            
            case CompositeExpression::TYPE_NOR:
                return $this->norExpressions($expressionList);
            
            default:
                throw new \RuntimeException("Unknown composite " . $expr->getType());
        }
    }
    
    /**
     * @param array $expressions
     *
     * @return callable
     */
    private function andExpressions($expressions)
    {
        return function ($object) use ($expressions) {
            foreach ($expressions as $expression) {
                if ( ! $expression($object)) {
                    return false;
                }
            }
            return true;
        };
    }
    
    /**
     * @param array $expressions
     *
     * @return callable
     */
    private function orExpressions($expressions)
    {
        return function ($object) use ($expressions) {
            foreach ($expressions as $expression) {
                if ($expression($object)) {
                    return true;
                }
            }
            return false;
        };
    }

    /**
     * @param array $expressions
     *
     * @return callable
     */
    private function nandExpressions($expressions)
    {
        return function ($object) use ($expressions) {
            foreach ($expressions as $expression) {
                if ( ! $expression($object)) {
                    return true;
                }
            }
            return false;
        };
    }
    
    /**
     * @param array $expressions
     *
     * @return callable
     */
    private function norExpressions($expressions)
    {
        return function ($object) use ($expressions) {
            foreach ($expressions as $expression) {
                if ($expression($object)) {
                    return false;
                }
            }
            return true;
        };
    }
}