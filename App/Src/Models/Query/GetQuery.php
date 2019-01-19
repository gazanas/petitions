<?php

namespace App\Src\Models\Query;

use App\Src\Models\Sets\Sets;

class GetQuery extends GenericQuery {
	
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
        $tableFields = $sets->getColumns($table);
        
        if(isset($params['return'])) {
            $returnFields = $this->returnFields($tableFields, $params['return']);
            $query = 'SELECT '.$returnFields.' FROM '.$table;
        } else {
            $query = 'SELECT * FROM '.$table;
        }

        return $query;
    }

    /**
    * Extracts the return values clause for the value action query.
    *
    * @param array $tableFields             
    * @param array|string $returnFields     
    *
    * @return string $fields                
    */
    private function returnFields($tableFields, $returnFields) {
        if(is_array($returnFields)) {
            $fields = '';
            foreach($returnFields as $field) {
                $fields .= $field.', ';
            }
        } else {
            $fields = $returnFields;
        }

        $fields = preg_replace('/, $/', '', $fields);

        return $fields;
    }
}