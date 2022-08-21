<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Jam\PhpProject\Exceptions\NotFoundException;

abstract class AbstractDBRepo
{
    public function __construct(protected readonly \PDO $connection,
                                protected string $tableName)
    {
    }
    
    protected function insert(array $insert):bool
    {
        $columns = '';
        $prepares = '';
        foreach ($insert as $key => $value) {
            $columns .= "$key, ";
            $prepares .= ":$key, ";
        }
        $columns = rtrim($columns, ', ');
        $prepares = rtrim($prepares, ', ');
        $query = "INSERT INTO $this->tableName ($columns) VALUES ($prepares)";
        $statement = $this->connection->prepare($query);
        return $statement->execute($insert);
    }

    /**
     * @throws NotFoundException
     */
    protected function selectOne(array $where, string $select = '*'):array
    {
        $whereString = '';
        foreach ($where as $key => $value) {
            $whereString .= "$key = :$key, ";
        }
        $whereString = rtrim($whereString, ', ');
        $statement = $this->connection->prepare("SELECT $select FROM $this->tableName WHERE $whereString LIMIT 1");
        $statement->execute($where);
        $result = $statement->fetch();
        if ($result === false) {
            throw new NotFoundException(
                "Cannot found row in table $this->tableName where $whereString");
        }
        return $result;
    }

    /**
     * @throws NotFoundException
     */
    protected function select(array $where, string $select = '*'):array
    {
        $whereString = '';
        foreach ($where as $key => $value) {
            $whereString .= "$key = :$key, ";
        }
        $whereString = rtrim($whereString, ', ');
        $statement =  $this->connection->prepare("SELECT $select FROM $this->tableName WHERE $whereString");
        $statement->execute($where);
        $result = [];
        while($row = $statement->fetch()) {
            $result[] = $row;
        }
        if (count($result) === 0) {
            throw new NotFoundException(
                "Cannot found rows in table $this->tableName where $whereString");
        }
        return $result;
    }

}