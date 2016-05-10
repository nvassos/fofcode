<?php
/**
 * PDO Class
 *
 * @author Andreas Teufel
 * @version 1.3
 */
class Database {
    private $host = DBHOST;
    private $user = DBUSER;
    private $pass = DBPASSWORD;
    private $dbname = DBNAME;

    private $dbh;
    private $error;
    private $stmt;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            $this->dbh->exec('set names utf8');
        }
        catch(PDOException $e){
            $this->error = $e->getMessage();
        }
    }

    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultset() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction() {
        return $this->dbh->commit();
    }

    public function cancelTransaction() {
        return $this->dbh->rollBack();
    }

    public function debugDumpParams() {
        return $this->stmt->debugDumpParams();
    }

    /*
     * Insert an Array (key = field name, value = field value)
     */
    public function insert($array, $table) {
        $fields = array();
        $fieldBinds = array();
        foreach($array as $key => $value) {
            $fields[] = $key;
            $fieldBinds[] = ':' . $key;
        }
        $this->query('INSERT INTO ' . $table . ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $fieldBinds) . ')');

        foreach($fields as $key) {
            $this->bind(':' . $key, $array[$key]);
        }

        $this->execute();
        return $this->lastInsertId();
    }

    /*
     * Update an Array (key = field name, value = field value)
     */
    public function update($array, $table, $id, $idkey = 'id') {
        $fields = array();
        foreach($array as $key => $value) {
            $fields[] = $key . ' = "' . $value . '"';
        }
        $this->query('UPDATE ' . $table . ' SET ' . implode(', ', $fields) . ' WHERE ' . $idkey . ' = \'' . $id . '\'');

        $this->execute();
    }

    /*
     * Select
     */
    public function select($array, $table, $where = array()) {
        $fields = array();
        $whereFields = array();
        foreach($array as $key => $value) {
            $fields[] = $key . ' = "' . $value . '"';
        }
        if (count($where)) {
            foreach($where as $key => $value) {
                $whereFields[] = $key . ' = "' . $value . '"';
            }
            $this->query('SELECT ' . implode(', ', $fields) . ' FROM ' . $table . ' WHERE ' . implode(' AND ', $whereFields));
        } else {
            $this->query('SELECT ' . implode(', ', $fields) . ' FROM ' . $table);
        }
        return $this->resultset();
    }

    /*
     * Select one row
     */
    public function getSingle($table, $id = 0, $idkey = 'id') {
        if ($id > 0) {
            $this->query('SELECT * FROM ' . $table . ' WHERE ' . $idkey . ' = \'' . $id . '\'');
        } else {
            $this->query('SELECT * FROM ' . $table . ' LIMIT 1');
        }
        return $this->single();
    }

    /*
     * Select all entries
     */
    public function getAll($table, $order = '', $limit = '') {
        if (strlen($order)) {
            $orderStr = ' ORDER BY ' . $order;
        } else {
            $orderStr = '';
        }
        if (strlen($limit)) {
            $limitStr = ' LIMIT ' . $limit;
        } else {
            $limitStr = '';
        }
        $this->query('SELECT * FROM ' . $table . $orderStr . $limitStr);
        return $this->resultset();
    }

    /*
     * Get Next Item
     */
    public function getNextItem($table, $id, $idkey = 'id') {
        $this->query('SELECT id FROM ' . $table . ' WHERE ' . $idkey . ' > \'' . $id . '\' ORDER BY ' . $idkey . ' ASC LIMIT 1');
        $result = $this->single();
        if ($result) {
            return $result;
        } else {
            $this->query('SELECT MIN(' . $idkey . ') AS ' . $idkey . ' FROM ' . $table . '');
            return $this->single();
        }
    }

    /*
     * Get Previous Item
     */
    public function getPreviousItem($table, $id, $idkey = 'id') {
        $this->query('SELECT id FROM ' . $table . ' WHERE ' . $idkey . ' < \'' . $id . '\' ORDER BY ' . $idkey . ' DESC LIMIT 1');
        $result = $this->single();
        if ($result) {
            return $result;
        } else {
            $this->query('SELECT MAX(' . $idkey . ') AS ' . $idkey . ' FROM ' . $table . '');
            return $this->single();
        }
    }

    /*
	 * Delete Item
	 */
    public function delete($table, $id, $idkey = 'id') {
        $this->query('DELETE FROM ' . $table . ' WHERE ' . $idkey . ' = \'' . $id . '\'');
        $this->execute();
    }
}
