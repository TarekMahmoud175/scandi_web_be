<?php

abstract class DataModel
{
    protected $conn;

    protected $tableName;

    protected $pkColumn;

    protected $defaultValues = [];

    protected $reflectionObject;

    protected $data = [];

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/database.php';

        $this->createConnection(
            $this->config['host'],
            $this->config['username'],
            $this->config['password'],
            $this->config['database']
        );
    }

    public function createConnection($host, $username, $password, $database, array $options = [], $port = 3306)
    {
        $dsn = "mysql:dbname={$database};host={$host};port={$port}";
        $this->conn = new PDO($dsn, $username, $password, $options);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getTableName()
    {
        return @$this->tableName ? $this->tableName : strtolower(basename(str_replace("\\", DIRECTORY_SEPARATOR, get_called_class())));
    }

    public function getTablePk()
    {
        return @$this->pkColumn ? $this->pkColumn : 'id';
    }

    public function insert()
    {
        $array = $this->data;

        // remove data not relevant
        $array = array_intersect_key($array, array_flip($this->getColumnNames()));

        // compile statement
        $fieldNames = $fieldMarkers = $values = [];
        foreach ($array as $key => $value) {
            $fieldNames[] = sprintf('`%s`', $key);
            $fieldMarkers[] = '?';
            $values[] = $value;
        }

        // build sql statement
        $sql = sprintf("INSERT INTO `%s` (%s) VALUES (%s)", $this->getTableName(), implode(', ', $fieldNames), implode(', ', $fieldMarkers));

        // prepare, bind & execute
        $this->sql($sql, array_values($values));

        $lastId = $this->conn->lastInsertId();

        // set our PK (if exists)
        if ($lastId) {
            $this->pkValue = $lastId;
            $this->data[$this->getTablePk()] = $lastId;
        }
    }

    public function update()
    {
        $pk = $this->getTablePk();
        $id = $this->data[$this->getTablePk()];

        $array = $this->data;

        // remove data not relevant
        $array = array_intersect_key($array, array_flip($this->getColumnNames()));
        unset($array[$pk]);

        // compile statement
        $fields = $values = [];
        foreach ($array as $key => $value) {
            $fields[] = sprintf('`%s` = ?', $key);
            $values[] = $value;
        }

        // build sql statement
        $sql = sprintf("UPDATE `%s` SET %s WHERE `%s` = ?", $this->getTableName(), implode(', ', $fields), $pk);
        $values[] = $id;

        // prepare, bind & execute
        $this->sql($sql, $values);
    }

    public function bulkDeleteByIDs($ids)
    {
        $sql = sprintf("DELETE FROM `%s` WHERE `id` IN (%s)", $this->getTableName(), implode(',', $ids));

        $this->sql($sql);
    }

    public function getColumnNames()
    {
        $conn = $this->conn;
        $result = $conn->query(sprintf("DESCRIBE %s;", $this->getTableName()));

        if ($result === false) {
            throw new Exception(sprintf('Unable to fetch the column names. %s.', $conn->errorCode()));
        }

        $ret = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $ret[] = $row['Field'];
        }

        $result->closeCursor();

        return $ret;
    }

    public function sql($sql, array $params = null, $results = false)
    {
        $sql = str_replace([':table', ':pk'], [$this->getTableName(), $this->getTablePk()], $sql);
        $stmt = $this->conn->prepare($sql);

        if (!$stmt || !$stmt->execute($params)) {
            throw new Exception(sprintf('Unable to execute SQL statement. %s', $this->conn->errorCode()));
        }

        if ($results) {
            $ret = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ret[] = $row;
            }

            $stmt->closeCursor();

            return $ret;
        } else
            return true;
    }

    public function all()
    {
        return $this->sql("SELECT * FROM :table", null, true);
    }

    public function retrieveByField($field, $value, $limit = 1, $skip = 0)
    {
        if (!is_string($field))
            throw new InvalidArgumentException('The field name must be a string.');

        $operator = (strpos($value, '%') === false) ? '=' : 'LIKE';

        $sql = sprintf("SELECT * FROM :table WHERE %s %s '%s'", $field, $operator, $value);

        $sql .= sprintf(" LIMIT %d,%d", $skip, $limit);

        // fetch our records
        return $this->sql($sql);
    }

    public function __get($name)
    {
        return $this->data[$name] ?? ($this->{$name} ?? null);
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
}
