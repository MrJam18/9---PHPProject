<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Jam\PhpProject\Exceptions\NotFoundException;
use Psr\Log\LoggerInterface;

abstract class AbstractDBRepo
{
    public function __construct(protected readonly \PDO $connection,
                                protected string $tableName,
                                protected LoggerInterface $logger
    )
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
            $columns = implode(', ', array_keys($where));
            $values = implode(', ', $where);
            throw new NotFoundException(
                "Cannot found row in table $this->tableName where $columns = $values");
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
            $columns = implode(', ', array_keys($where));
            $values = implode(', ', $where);
            throw new NotFoundException(
                "Cannot found rows in table $this->tableName where $columns = $values");
        }
        return $result;
    }

}