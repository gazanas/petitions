<?php
namespace App\Src;

use App\Src\Models\Sets\Sets;
use App\Src\Models\Data\SQL;
use App\Src\Models\Query\GetQuery;
use App\Src\Models\Parameters\GetParameters;
use App\Src\Models\Query\InsertQuery;
use App\Src\Models\Parameters\InsertParameters;
use App\Src\Models\Query\UpdateQuery;
use App\Src\Models\Parameters\UpdateParameters;
use App\Src\Models\Query\DeleteQuery;
use App\Src\Models\Parameters\DeleteParameters;

class Model
{

    private $sql;

    private $sets;

    /**
     * Initializes the data access object, prepares the parameters and constructs the
     * query for execution on the database.
     *
     * @param \PDO $database
     *
     */
    public function __construct(\PDO $database)
    {
        $this->sql = new SQL($database);
        $this->sets = new Sets($database);
    }

    /**
     * Returns all the columns of the table
     *
     * @param string $table
     * @param array $parameters
     *
     * @return array
     */
    public function get(string $table, array $parameters)
    {
        $query = (new GetQuery())->setUpQuery($this->sets, $table, $parameters);
        $preparedParameters = (new GetParameters($this->sets))->prepareParameters($table, $parameters);
        return $this->sql->executeQuery($query, $preparedParameters);
    }

    /**
     * Updates a column of the table
     *
     * @param string $table
     * @param array $parameters
     *
     * @return string
     */
    public function update(string $table, array $parameters)
    {
        $query = (new UpdateQuery())->setUpQuery($this->sets, $table, $parameters);
        $preparedParameters = (new UpdateParameters($this->sets))->prepareParameters($table, $parameters);
        return $this->sql->executeQuery($query, $preparedParameters);
    }

    /**
     * Deletes a row of the table
     * 
     * @param string $table
     * @param array $parameters
     * 
     */
    public function delete(string $table, array $parameters)
    {
        $query = (new DeleteQuery())->setUpQuery($this->sets, $table, $parameters);
        $preparedParameters = (new DeleteParameters($this->sets))->prepareParameters($table, $parameters);
        return $this->sql->executeQuery($query, $preparedParameters);
    }

    /**
     * Inserts a new row on the table
     *
     * @param string $table
     * @param array $parameters
     * 
     */
    public function insert(string $table, array $parameters)
    {
        $query = (new InsertQuery())->setUpQuery($this->sets, $table, $parameters);
        $preparedParameters = (new InsertParameters($this->sets))->prepareParameters($table, $parameters);
        return $this->sql->executeQuery($query, $preparedParameters);
    }
}
