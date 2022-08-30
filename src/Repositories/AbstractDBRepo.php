<?php
declare(strict_types=1);

namespace Jam\PhpProject\Repositories;

use Jam\PhpProject\Exceptions\NotFoundException;
use PDOStatement;
use Psr\Log\LoggerInterface;

abstract class AbstractDBRepo
{
    public function __construct(protected readonly \PDO $connection,
                                protected string $tableName,
                                protected LoggerInterface $logger
    )
    {
    }
    
    protected function insert(array $insert): void
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
        $statement->execute($insert);
    }

    /**
     * @throws NotFoundException
     */
    protected function selectOne(array $where, string $select = '*'): array
    {
        $whereString = $this->prepareWhere($where);
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
        $whereString = $this->prepareWhere($where);
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

    /**
     * @throws NotFoundException
     */
    protected function update(array $updated, array $where): void
    {
        $set = '';
        foreach ($updated as $key => $value) {
            $set .= "$key = :$key, ";
    }
        $set = rtrim($set, ', ');
        $whereString = '';
        $whereExecute = [];
        foreach ($where as $key => $value) {
            $whereKey = ":where$key";
            $whereString .= "$key = $whereKey AND ";
            $whereExecute[$whereKey] = $value;
        }
        $whereString = rtrim($whereString, ' AND ');
        $execute = $whereExecute + $updated;
        $statement = $this->connection->prepare("UPDATE $this->tableName
        SET $set WHERE $whereString");
        $statement->execute($execute);
        if($statement->rowCount() === 0) {
            $columns = implode(', ', array_keys($where));
            $values = implode(', ', $where);
            throw new NotFoundException("rows in table $this->tableName where $columns = $values dont was updated because don't found");
        }
    }

    /**
     * @throws NotFoundException
     */
    function destroy(array $where): void
    {
        $whereString = $this->prepareWhere($where);
        $statement = $this->connection->prepare("DELETE FROM $this->tableName WHERE $whereString");
        $statement->execute($where);
        if($statement->rowCount() === 0) {
            $columns = implode(', ', array_keys($where));
            $values = implode(', ', $where);
            throw new NotFoundException("rows in table $this->tableName where $columns = $values dont was deleted because don't found");
        }

    }

    private function prepareWhere(array $where): string
    {
        $whereString = '';
        foreach ($where as $key => $value) {
            $whereString .= "$key = :$key AND ";
        }
        return rtrim($whereString, ' AND ');
    }


}