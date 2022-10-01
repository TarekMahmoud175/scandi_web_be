<?php

class DB
{
    private $config = [];

    private $connection;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/database.php';

        try {
            $this->connection = new PDO(
                "mysql:host={$this->config['host']};dbname={$this->config['database']}",
                $this->config['username'],
                $this->config['password']
            );
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function checkExisitance($table, $col, $col_value)
    {
        $sql = "SELECT * FROM $table WHERE $col = \"$col_value\" ";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            // set the resulting array to associative
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function insert($table, $data)
    {
        $columns = implode(',', array_keys($data));
        $values = implode('","', array_values($data));

        $sql = "INSERT INTO $table ($columns) VALUES (\"$values\")";

        try {
            $this->connection->exec($sql);
            return true;
        } catch (PDOException $e) {
            die($sql . "<br>" . $e->getMessage());
        }
    }

    public function select($table, $columns = ['*'])
    {
        $columns_string = implode(',', $columns);

        try {
            $stmt = $this->connection->prepare("SELECT $columns_string FROM $table");
            $stmt->execute();

            // set the resulting array to associative
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function delete($table, $ids)
    {
        $ids_string = implode(',', $ids);

        // sql to delete a record
        $sql = "DELETE FROM $table WHERE id IN ($ids_string)";

        try {
            // use exec() because no results are returned
            $this->connection->exec($sql);
            return true;
        } catch (PDOException $e) {
            die($sql . "<br>" . $e->getMessage());
        }
    }
}


$db = new DB();


