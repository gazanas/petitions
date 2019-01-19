<?php

namespace App\Src\Models\Query;

use App\Src\Models\Sets\Sets;

class DeleteQuery extends GenericQuery {
	
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
        $query = 'DELETE FROM '.$table; 

        return $query;
    }
}