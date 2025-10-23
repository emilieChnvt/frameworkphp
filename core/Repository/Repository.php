<?php

namespace Core\Repository;



use App\Entity\Pizza;
use Attributes\TargetEntity;
use Attributes\TargetRepository;
use Core\Attributes\Column;
use Core\Attributes\Table;
use Core\Database\Database;
use ReflectionClass;

abstract class Repository
{
    protected \PDO $pdo;


    protected string $targetEntity;
    protected string $tableName;

    protected array $columnList = [];




    public function __construct()
    {
        $this->pdo =  Database::getPdo();

        $this->targetEntity = $this->resolveTargetEntity();

        $this->tableName = $this->resolveTableName();
    }

    protected function resolveTableName()
    {
        $reflection = new ReflectionClass($this->targetEntity);
        $attributes = $reflection->getAttributes(Table::class);
        $arguments = $attributes[0]->getArguments();
        $tableName = $arguments["name"];
        return $tableName;
    }

    protected function resolveTargetEntity()
    {
        $reflection = new ReflectionClass($this);
        $attributes = $reflection->getAttributes(TargetEntity::class);
        $arguments = $attributes[0]->getArguments();
        $targetEntity = $arguments["entityName"];
        return $targetEntity;
    }



    protected function resolveColumnList()
    {
        $reflection = new ReflectionClass($this->targetEntity);
        $attributes = $reflection->getAttributes(Column::class);
        foreach ($attributes as $attribute) {
            $column = $attribute->getName();

        }
        return $column;

    }




    public function findAll() : array
    {

        $query = $this->pdo->prepare("SELECT * FROM $this->tableName");
        $query->execute();
        $items = $query->fetchAll(\PDO::FETCH_CLASS, $this->targetEntity);
        return $items;
    }
    public function find(int $id) : object | bool
    {
        $query = $this->pdo->prepare("SELECT * FROM $this->tableName WHERE id = :id");
        $query->execute([
            "id"=> $id
        ]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->targetEntity);
        $item = $query->fetch();
        return $item;
    }

    public function findBy(array $criteria): array
    {
        $sql = "SELECT * FROM {$this->tableName}";
        $params = [];

        if ($criteria) {
            $conditions = [];
            foreach ($criteria as $column => $value) {
                $conditions[] = "$column = :$column";
                $params[$column] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, $this->targetEntity);
    }
    public function findOneBy(array $criteria): object|null
    {
        $results = $this->findBy($criteria);
        return $results[0] ?? null;
    }

    public function delete(object $item) : void
    {
        $deleteQuery = $this->pdo->prepare("DELETE FROM $this->tableName WHERE id = :id");
        $deleteQuery->execute([
            "id"=> $item->getId()
        ]);

    }



}