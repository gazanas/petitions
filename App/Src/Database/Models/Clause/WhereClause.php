<?php

namespace App\Src\Models\Clause;

use App\Src\Models\Sets\Sets;

class WhereClause implements ClauseInterface {

    protected $sets;
    private $columns;

    public function __construct(string $table, Sets $sets) {
        $this->sets = $sets;
        $this->columns = $this->sets->getColumns($table);
    }

	/**
     * Simple Where Clause formats an array that consists of parameters
     * in a where clause where all conditions are equalities and are
     * connected with an AND expression.
     *
     * @param array $params     
     * 
     * @return string|null      
     */
    public function prepareClause(array $params) {

        unset($params['options']);
        
        if (empty($params) === true)
            return null;

        $query = ' WHERE ';

        // Traverse the parameter array and set up the where clause for each one.
        $i = 0;
        if (isset($params['condition']) === true) {
            $size = (count($params) - 2);
        } else {
            $size = (count($params) - 1);
        }
        
        foreach (array_keys($params) as $field) {
            $query = $this->setUpWhereClause($query, $i, $size, $params, $field);
            $i++;
        }

        return $query;
    }

    /**
     * Set up the where clause of the query  for each parameter passed concatenate it with the query string
     * and return the finished query to be executed
     *
     * @param string  $query    
     * @param integer $i       
     * @param int $size         
     * @param array  $params    
     * @param string $key       
     *
     * @return $string $query   
     */
    private function setUpWhereClause(string $query, int $i, int $size, array $params, string $key) {
        $condition = $this->setUpCondition($params, $i);

        if (is_array($params[$key]) === true && is_numeric($key) === true) {
            foreach($params[$key] as $field => $param) {
                if ($field === 'operator') {
                    if(!is_string($param)) {
                        throw new \Exception('Can not use '.gettype($param).' as operator.');
                    } else if(!in_array($param, $this->sets->getOperatorSet(), true))
                        throw new \Exception('Operator '.$param.' is not valid.');
                    $op = $param;
                    continue;
                } else if (!isset($op)) {
                    $op = '=';
                }

                $query .= $this->whereQuery($i, $size, $field, $op, $condition);
            }
        } else {
            if ($params[$key] && $key !== 'condition')
                $query .= $this->whereQuery($i, $size, $key, '=', $condition);
        }
        return $query;
    }

    /**
    * Set up the where condition as a string to concatenate with the query
    *
    * @param integer $i         
    * @param integer $size      
    * @param string  $field     
    * @param string $op         
    * @param string $condition  
    *
    * @return $string $query     
    */
    private function whereQuery(int $i, int $size, string $field, string $op, string $condition) {
        if ($i < $size) {
            $query = $field.' '.$op.' ? '.$condition.' ';
        } else {
            $query = $field.' '.$op.' ?';
        }

        return $query;
    }


    /**
     * If the condition array is passed, setup the condition between
     * each statement of the where clause.
     *
     * @param array   $params       
     * @param integer $i            
     *
     * @return string $condition    
     */
    private function setUpCondition(array $params, int $i) {
        if (empty($params['condition'][$i]) === false) {
            $this->checkCondition($params['condition'][$i]);
            $condition = $params['condition'][$i];
        } else {
            $condition = 'AND';
        }

        return $condition;
    }

    private function checkCondition(string $condition) {
        if(!in_array($condition, $this->sets->getConditionSet(), true))
            throw new \Exception('Condition '.$condition.' is not valid.');
    }
}