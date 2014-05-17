<?php

namespace CriteriaDesigner\Collections\Expr;

use CriteriaDesigner\Collections\InterfaceVisitor;
use CriteriaDesigner\Collections\Expr\Value;

interface Expression
{
    /**
     * @return mixed
     */
    public function toClosure();
}