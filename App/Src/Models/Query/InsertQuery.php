<?php

namespace App\Src\Models\Query;

use App\Src\Models\Sets\Sets;
use App\Src\Models\Clause\InsertClause;

class InsertQuery implements Query {
	
	/**
    * Checks the type of the query and initializes the static start of the query
    * before the parameters of the query are required
    *
    * @param Sets $sets    
    * @param string $table      
    * @param array $params      
    *
    * @return string $query    
    * 
    */
    public function initializeQuery(Sets $sets, string $table, array $params) {

        $query = 'INSERT INTO '.$table.'('.$this->prepareFieldsOfQuery($sets, $table, $params).') VALUES(';
        return $query;
    }

    /**
    * Sets the parameters (column names) that were not passed by the user.
    *
    * @param Sets $sets    
    * @param string $table      
    * @param array $params      
    *
    * @return array $params     
    */
    private function setNotPassedParameters(Sets $sets, string $table, array $params) {
        foreach($sets->getColumns($table) as $index => $field) {
            /**
            * If field was passed in the parameters or field was not passed and has default value
            * then don't include it in the parameters for the insert fields clause.
            */
            if(array_key_exists($field, $params) || $this->checkDefaultValue($sets, $table, $field))
                continue;

            $params = array_slice($params, 0, $index, true) + array($field => NULL) + array_slice($params, $index, count($params), true);
        }

        return $params;
    }

    /**
    * If a column has a default value return true
    *
    * @param Sets $sets   
    * @param string $table      
    * @param string $field      
    *
    * @return boolean
    */
    private function checkDefaultValue(Sets $sets, string $table, string $field) {
        if(in_array($field, $sets->getDefaultNames($table), true))
            return true;

        return false;
    }

    /**
    * Prepare the fields clause of the insert query e.g. INSERT INTO table(column_name1, column_name2, etc)
    * the fields are the column_name1, column_name2 etc.
    *
    * @param Sets $sets    
    * @param string $table     
    * @param array $params      
    *
    * @return string $fields    
    */
    private function prepareFieldsOfQuery(Sets $sets, string $table, array $params) {
        $fields = '';

        $params = $this->setNotPassedParameters($sets, $table, $params);

        foreach(array_keys($params) as $field)
            $fields .= $field.', ';

        $fields = preg_replace('/\, $/', '', $fields);

        return $fields;
    }
    
    /**
     * Set up the SQL query
     *
     * @param Sets $sets    
     * @param string $table      
     * @param array $params      
     *
     * @return string $query    
     */
    public function setUpQuery(Sets $sets, string $table, array $params) {
        
        $query = $this->initializeQuery($sets, $table, $params);

        $query = $this->insertQuery($query, $sets, $table, $this->setNotPassedParameters($sets, $table, $params));
        
        return $query;
    }

    /**
    * Setup an insert sql query, this differs from generic queries
    * because it can not contain a where clause or an options clause.
    *
    * @param string $query      
    * @param Sets $sets    
    * @param string $table      
    * @param array $params      
    *
    * @return string            
    */
    private function insertQuery(string $query, Sets $sets, string $table, array $params) {
        $params = $this->setNotPassedParameters($sets, $table, $params);
        
        $insertClauseObject = new InsertClause($sets->getAutoCompleted($table));
        $query .= $insertClauseObject->prepareClause($params);
    
        return $query;
    }
}