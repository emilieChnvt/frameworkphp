<?php

namespace Core\Repository;



use App\Entity\Pizza;
use Attributes\TargetEntity;
use Core\Attributes\Table;
use Core\Database\Database;
use ReflectionClass;

abstract class Repository
{
    protected \PDO $pdo;


    protected string $targetEntity;
    protected string $tableName;

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

    public function delete(object $item) : void
    {
        $deleteQuery = $this->pdo->prepare("DELETE FROM $this->tableName WHERE id = :id");
        $deleteQuery->execute([
            "id"=> $item->getId()
        ]);

    }

}