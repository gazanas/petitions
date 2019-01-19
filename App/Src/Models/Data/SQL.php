<?php

namespace App\Src\Models\Data;

class SQL
{

    /**
     * The database connection object
     *
     * @var \PDO $_db
     */
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }


    /**
     * Executes a query
     *
     * @param string $query         
     * @param array  $params        
     * @param int $successString    
     *
     * @return array|string         
     * 
     */
    public function executeQuery(string $query, array $params)
    {        
        // Initialize results array.
        $data = array();
        
        unset($params['options']);

        // Prepare statement and execute it.
        $stmt = $this->db->prepare($query);

        $stmt->execute($params);

        // Fetch results as associative arrays and save them in a new array.
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($results as $result) {
            array_push($data, $result);
        }

        $stmt = null;

        return $data;
    }
}
