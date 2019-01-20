<?php

namespace App\Src\Models\Query;

use App\Src\Models\Sets\Sets;
use App\Src\Models\Clause\WhereClause;
use App\Src\Models\Clause\QueryOptions;

abstract class GenericQuery implements Query {
    
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

        $query = $this->genericQuery($table, $query, $sets, $params);
      
        return $query;
    }

    /**
    * Setup a generic sql query, generic queries include select,
    * update, delete queries. That means queries that can contain a where
    * clause and options.
    *
    * @param string $table
    * @param string $query     
    * @param Sets $sets    
    * @param array $params      
    *
    * @return string            
    */
    private function genericQuery($table, $query, $sets, $params) {
        $params = array_diff_key($params, array_flip($sets->getActionParameters()));

        $whereClause = new WhereClause($table, $sets);

        $query .= $whereClause->prepareClause($params);        

        $queryOptions = new QueryOptions($sets);
        
        $query = $queryOptions->queryOptions($query, $params);

        return $query;
    }

}
