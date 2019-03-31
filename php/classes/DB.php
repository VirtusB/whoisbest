<?php

class DB {
    private static $_instance = null;
    private $_pdo, 
            $_query, 
            $_error = false, 
            $_results, 
            $_count = 0;


    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . CONFIG_MYSQL_HOST .';dbname=' . CONFIG_DB_NAME .';charset=utf8mb4', CONFIG_DB_USERNAME, CONFIG_DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance() {
        if(self::$_instance === NULL) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    public function query($sql, array $params = array()) {
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if(count($params)) {
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }

    
    public function action($action, $table, array $where = array()) {
        if(count($where) === 3) { //
            $operators = array('=', '>', '<', '>=', '<=');

            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if(in_array($operator, $operators, false)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ";

                if(!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function get($table, $where) {
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where) {
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, array $fields = array()): bool {
            $keys = array_keys($fields);
            $values = '';
            $x = 1;

            foreach($fields as $field) {
                $values .= '?';
                if($x < count($fields)) {
                    $values .= ', ';
                }
                $x++;
            }

            $sql = "INSERT INTO {$table}(" . implode(',', $keys) . ") VALUES ({$values})";

            if(!$this->query($sql, $fields)->error()) {
                return true;
            }

        return false;
    }

    public function update($table, $id, $fields): bool {
        $set = '';
        $x = 1;

        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id} ";

        if(!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function results() {
        return $this->_results;
    }

    public function first() {
        return $this->results()[0];
    }

    public function error(): bool {
        return $this->_error;
    }

    public function count(): int {
        return $this->_count;
    }
}