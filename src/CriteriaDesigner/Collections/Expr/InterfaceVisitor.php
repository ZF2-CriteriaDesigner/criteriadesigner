<?php

namespace CriteriaDesigner\Collections;

use CriteriaDesigner\Collections\Expr\Expression;

interface InterfaceVisitor
{
    /**
     * Converts a comparison expression into the target query language output.
     *
     * @param Expression $expression
     *
     * @return bool
     */
    public function walkComparison(Expression $expression);
}