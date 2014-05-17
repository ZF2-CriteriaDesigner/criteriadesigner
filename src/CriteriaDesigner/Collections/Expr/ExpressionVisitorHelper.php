<?php

namespace CriteriaDesigner\Collections\Expr;

use CriteriaDesigner\Collections\InterfaceVisitor;
use CriteriaDesigner\Collections\Expr\Value;
use CriteriaDesigner\Collections\Expr\CompositeExpression;

class ExpressionVisitorHelper
{    
    /**
     * Accesses the field of a given object. This field has to be public
     * directly or indirectly (through an accessor get*, is*, or a magic
     * method, __get, __call).
     *
     * @param object $object
     * @param string $field
     *
     * @return mixed
     */
    private static function getObjectFieldValueEx($object, $field)
    {
        if (is_array($object)) {
            return $object[$field];
        }

        $accessors = array('get', 'is');

        foreach ($accessors as $accessor) {
            $accessor .= $field;

            if ( ! method_exists($object, $accessor)) {
                continue;
            }

            return $object->$accessor();
        }

        // __call should be triggered for get.
        $accessor = $accessors[0] . $field;

        if (method_exists($object, '__call')) {
            return $object->$accessor();
        }

        if ($object instanceof \ArrayAccess) {
            return $object[$field];
        }

        return $object->$field;
    }

    /**
     * Accesses the field of a given object. This field has to be public
     * directly or indirectly (through an accessor get*, is*, or a magic
     * method, __get, __call).
     * Nested fields are separated by '::'
     *
     * @param object $object
     * @param string $field
     *
     * @return mixed
     */
    public static function getObjectFieldValue($object, $field)
    {
        $elements = explode('::', $field);
        $firstField = array_shift($elements);
        
        if (count($elements) == 0)
            return ExpressionVisitorHelper::getObjectFieldValueEx($object, $firstField);
        else {
            $subElements = implode('::', $elements);
            $subObject = ExpressionVisitorHelper::getObjectFieldValueEx($object, $firstField);
            if ($subObject === null)
                return null;
            return ExpressionVisitorHelper::getObjectFieldValue($subObject, $subElements);
        }
    }
    
    /**
     * Helper for sorting arrays of objects based on multiple fields + orientations.
     *
     * @param string   $name
     * @param int      $orientation
     * @param \Closure $next
     *
     * @return \Closure
     */
    public static function sortByField($name, $orientation = 1, \Closure $next = null)
    {
        if (!$next) {
            $next = function() {
                return 0;
            };
        }

        return function ($a, $b) use ($name, $next, $orientation) {
            $aValue = ExpressionVisitorHelper::getObjectFieldValue($a, $name);
            $bValue = ExpressionVisitorHelper::getObjectFieldValue($b, $name);

            if ($aValue === $bValue) {
                return $next($a, $b);
            }

            return (($aValue > $bValue) ? 1 : -1) * $orientation;
        };
    }
}
