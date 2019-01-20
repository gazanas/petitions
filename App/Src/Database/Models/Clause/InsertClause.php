<?php

namespace App\Src\Models\Clause;

class InsertClause implements ClauseInterface {
	
    protected $autoColumns;

    /**
    * Initializes the insert clause object.
    *
    * @param array $autoColumns
    */
	public function __construct(array $autoColumns) {
       $this->autoColumns = $autoColumns;
    }

    /**
    * Returns the prepared insert clause for the query.
    *
    * @param array $params              
    *
    * @return string $preparedClause
    */
	public function prepareClause(array $params) {
        $query = '';

        foreach ($params as $parameter) {
            if ($parameter === null) {
                $query .= 'NULL,';
            } else {
                $query .= '?,';
            }
        }

        $query = preg_replace('/\,$/', ')', $query);

        return $query;
	}
}