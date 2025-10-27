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
        $reflection = new ReflectionClass($this->targetEntity); // => pour avoir liste propriétés, méthodes .... d'une classe , mais peur pas lire valeur
        $attributes = $reflection->getAttributes(Table::class); // Table, Column ...
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



    protected function resolveColumnList(): array
    {
        $columnName = [];
        $reflection = new \ReflectionClass($this->targetEntity);
        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(Column::class);
            foreach ($attributes as $attribute) {
                $args = $attribute->getArguments();
                $columnName[] = $args['name'] ?? $property->getName();
            }
        }
        return $columnName;
    }


    public function getColumnList(): string
    {
        $columns = $this->resolveColumnList();
        $string = implode(",", $columns);
        return $string;

    }

    public function getColumnPlaceholderList(): string
    {
        $columns = $this->resolveColumnList();
        $string = ':' . implode(',:', $columns);
        return $string;
    }


    public function findAll() : array
    {
        $string = $this->getColumnList();

        $stmt = $this->pdo->prepare("SELECT $string FROM $this->tableName");
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->targetEntity);
        $items = $stmt->fetchAll();

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
    public function save(object $entity): int
    {
        $allColumns = $this->resolveColumnList();
        $columnsArray=[];
        foreach ($allColumns as $column) {
            if($column !=='id'){
                $columnsArray[]=$column;
            }
        }
        // Créer INSERT INTO $this->tableName (content, post_id)
        $columns = implode(',', $columnsArray);

        // Créer VALUES (:content, :post_id)
        $placeholders = ':' . implode(',:', $columnsArray);


        $query = $this->pdo->prepare("INSERT INTO $this->tableName ($columns) VALUES ($placeholders)");

        // 3️⃣ Récupérer dynamiquement les valeurs  : >execute([
        //            "content"=>$comment->getContent(),
        //            "post_id"=>$comment->getPostId()
        //        ]);

        $values = [];
        $reflection = new \ReflectionClass($entity);
        foreach ($reflection->getProperties() as $property) {
            $attrs = $property->getAttributes(Column::class); // recupe arr column de chaque property : title, content
            $columnName = $attrs[0]->getArguments()['name'] ?? $property->getName(); // récup name de ces colonnes
            if (!in_array($columnName, $columnsArray)) continue;


            $property->setAccessible(true); // meme pour private/protected
            $value = $property->getValue($entity); //recup value

            if (is_array($value)) {
                $value = json_encode($value);
            }

            $values[$columnName] = $value;
        }

        $query->execute($values);

        // 5️⃣ Récupérer l'id auto-incrémenté et l’assigner à l’objet
        $lastId = (int)$this->pdo->lastInsertId();
        if (property_exists($entity, 'id')) { // si y'a bien une propriét id =>
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($entity, $lastId);
        }

        return $lastId;
    }


    public function update(object $entity): object
    {

        $columnList = $this->resolveColumnList();
        $columnsArray=[];
        foreach ($columnList as $column) {
            if($column !=='id'){
                $columnsArray[]=$column;
            }
        }

        $set = implode(', ', array_map(fn($col) => "$col = :$col", $columnsArray));

        $stmt = $this->pdo->prepare("UPDATE $this->tableName SET $set WHERE id = :id");

        $values = [];
        $reflection = new \ReflectionClass($entity);
        foreach ($reflection->getProperties() as $property) {
            $attrs = $property->getAttributes(Column::class);

            $columnName = $attrs[0]->getArguments()['name'] ?? $property->getName();
            if (!in_array($columnName, $columnsArray)) continue;


            $property->setAccessible(true);
            $value = $property->getValue($entity);

            if (is_array($value)) {
                $value = json_encode($value);
            }

            $values[$columnName] = $value;
        }

        // Ajouter l'id pour le WHERE
        $idProp = $reflection->getProperty('id');
        $idProp->setAccessible(true);
        $values['id'] = $idProp->getValue($entity);

        $stmt->execute($values);

        return $this->find($values['id']);
    }




    public function findBy(array $criteria): array
    {
        $string = $this->getColumnList();
        $sql = "SELECT $string FROM {$this->tableName}";
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



}