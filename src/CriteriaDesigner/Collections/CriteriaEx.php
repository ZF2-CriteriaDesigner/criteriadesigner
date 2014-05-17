<?php

namespace CriteriaDesigner\Collections;

use CriteriaDesigner\Collections\Expr\AdvancedExpressionBuilder;
use CriteriaDesigner\Collections\Expr\Expression;
use CriteriaDesigner\Collections\Expr\CompositeExpression;

class CriteriaEx
{
    const MONDAY = 'MONDAY';
    const TUESDAY = 'TUESDAY';
    const WEDNESDAY = 'WEDNESDAY';
    const THURSDAY = 'THURSDAY';
    const FRIDAY = 'FRIDAY';
    const SATURDAY = 'SATURDAY';
    const SUNDAY = 'SUNDAY';

    /**
     * @var string
     */
    const ASC  = 'ASC';
    
    /**
     * @var string
     */
    const DESC = 'DESC';
        
    /**
     * @var \CriteriaDesigner\Collections\Expr\Expression
     */
    private $expression;
    
    /**
     * @var array|null
     */
    private $orderings;
    
    /**
     * @var int|null
     */
    private $firstResult;
    
    /**
     * @var int|null
     */
    private $maxResults;
    
    /**
     * @var \CriteriaDesigner\Collections\Expr\AdvancedExpressionBuilder|null
     */
    private static $advancedExpressionBuilder;
    
    /**
     * @return \CriteriaDesigner\Collections\Expr\AdvancedExpressionBuilder
     */
    public static function advancedExpr()
    {
        if (self::$advancedExpressionBuilder === null) {
            self::$advancedExpressionBuilder = new AdvancedExpressionBuilder();
        }
        return self::$advancedExpressionBuilder;
    }
        
    /**
     * Creates an instance of the class.
     *
     * @return CriteriaEx
     */
    public static function create()
    {
        return new static();
    }
    
    /**
     * Creates an instance of the class.
     *
     * @return CriteriaEx
     */
    public static function createFromJSON($json)
    {
        $criteria = new static();
        $criteriaArray = \Zend\Json\Json::decode($json, 1);
        $criteria->fromArray($criteriaArray);
        return $criteria;
    }
    
    public function toClosure()
    {
        return $this->getWhereExpression()->toClosure();
    }
    
    /**
     * Construct a new CriteriaEx.
     *
     * @param Expression $expression
     * @param array|null $orderings
     * @param int|null   $firstResult
     * @param int|null   $maxResults
     */
    public function __construct(Expression $expression = null, array $orderings = null, $firstResult = null, $maxResults = null)
    {
        $this->expression  = $expression;
        $this->orderings   = $orderings;
        $this->firstResult = $firstResult;
        $this->maxResults  = $maxResults;
    }
    
    /**
     * Sets the where expression to evaluate when this Criteria is searched for.
     *
     * @param Expression $expression
     *
     * @return Criteria
     */
    public function where(Expression $expression)
    {
        $this->expression = $expression;
        return $this;
    }
    
    /**
     * Appends the where expression to evaluate when this Criteria is searched for
     * using an AND with previous expression.
     *
     * @param Expression $expression
     *
     * @return CriteriaEx
     */
    public function andWhere(Expression $expression)
    {
        if ($this->expression === null) {
            return $this->where($expression);
        }
    
        $this->expression = new CompositeExpression(CompositeExpression::TYPE_AND, array(
            $this->expression, $expression
        ));
    
        return $this;
    }
    
    /**
     * Appends the where expression to evaluate when this Criteria is searched for
     * using an NAND with previous expression.
     *
     * @param Expression $expression
     *
     * @return CriteriaEx
     */
    public function nandWhere(Expression $expression)
    {
        if ($this->expression === null) {
            return $this->where($expression);
        }
    
        $this->expression = new CompositeExpression(CompositeExpression::TYPE_NAND, array(
            $this->expression, $expression
        ));
    
        return $this;
    }
    
    /**
     * Appends the where expression to evaluate when this Criteria is searched for
     * using an OR with previous expression.
     *
     * @param Expression $expression
     *
     * @return CriteriaEx
     */
    public function orWhere(Expression $expression)
    {
        if ($this->expression === null) {
            return $this->where($expression);
        }
    
        $this->expression = new CompositeExpression(CompositeExpression::TYPE_OR, array(
            $this->expression, $expression
        ));
    
        return $this;
    }

    /**
     * Appends the where expression to evaluate when this Criteria is searched for
     * using an NOR with previous expression.
     *
     * @param Expression $expression
     *
     * @return CriteriaEx
     */
    public function norWhere(Expression $expression)
    {
        if ($this->expression === null) {
            return $this->where($expression);
        }
    
        $this->expression = new CompositeExpression(CompositeExpression::TYPE_NOR, array(
            $this->expression, $expression
        ));
    
        return $this;
    }
    
    /**
     * Gets the expression attached to this Criteria.
     *
     * @return CompositeExpression|null
     */
    public function getWhereExpression()
    {
        return $this->expression;
    }
    
    /**
     * Gets the current orderings of this Criteria.
     *
     * @return array
     */
    public function getOrderings()
    {
        return $this->orderings;
    }
    
    /**
     * Sets the ordering of the result of this Criteria.
     *
     * Keys are field and values are the order, being either ASC or DESC.
     *
     * @see CriteriaEx::ASC
     * @see CriteriaEx::DESC
     *
     * @param array $orderings
     *
     * @return Criteria
     */
    public function orderBy(array $orderings)
    {
        $this->orderings = $orderings;
        return $this;
    }
    
    /**
     * Gets the current first result option of this Criteria.
     *
     * @return int|null
     */
    public function getFirstResult()
    {
        return $this->firstResult;
    }
    
    /**
     * Set the number of first result that this Criteria should return.
     *
     * @param int|null $firstResult The value to set.
     *
     * @return CriteriaEx
     */
    public function setFirstResult($firstResult)
    {
        $this->firstResult = $firstResult;
        return $this;
    }
    
    /**
     * Gets maxResults.
     *
     * @return int|null
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }
    
    /**
     * Sets maxResults.
     *
     * @param int|null $maxResults The value to set.
     *
     * @return CriteriaEx
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
        return $this;
    }
    
    private static $idConnected;
    
    /**
     * Change the commented line for supporting IdConnect comparison
     * @return int
     */
    public static function getIdConnected()
    {
//        if (isset($_SESSION['My_Session']) && isset($_SESSION['My_Session']['current_user'])) {
//            CriteriaEx::$idConnected = $_SESSION['My_Session']['current_user']['user_id'];
//        }
        return CriteriaEx::$idConnected;
    }
    
    
    /**
     * @param array $criteriaArray
     * @return \CriteriaDesigner\Collections\CriteriaEx
     */
    public function fromArray($criteriaArray)
    {
        $selectOperator = $criteriaArray["selectOperator"];
        $comparisons = $criteriaArray["comparisons"];
        $exprBuilder = $this->advancedExpr();
        
        $expressionArray = array();
        
        foreach ($comparisons as $comparison) {
            $content = $comparison["content"];
            if ($comparison["type"] == "simple") {
                $field = isset($content["field"]["complexName"]) ? $content["field"]["complexName"] : $content["field"]["name"];
                $conditionType = $content["condition"]["type"];
                $conditionPos = $content["condition"]["pos"];
                $parameter = $content["parameter"];
                
                if (isset($content["subCriteria"])) {
                    $subCriteriaCount = new CriteriaEx();
                    $subCriteriaCount->fromArray($content["subCriteria"]);
                    $expressionArray[] = $exprBuilder->createComparison($conditionType, $conditionPos, $field, $parameter, $subCriteriaCount);
                }
                else {
                    $expressionArray[] = $exprBuilder->createComparison($conditionType, $conditionPos, $field, $parameter);
                }   
            }
            else {
                $subCriteria = new static();
                $expressionArray[] = $subCriteria->fromArray($content)->getWhereExpression();
            }
        };
        
        $compositeExpression = new CompositeExpression($selectOperator, $expressionArray);
        $this->where($compositeExpression);
        
        return $this;
    }
}
