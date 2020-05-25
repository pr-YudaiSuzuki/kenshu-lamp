<?php

require_once __DIR__.'/../app/config.php';

abstract class BaseModelManager {
    protected $TABLE_NAME;
    protected $CLASS_NAME;
    protected $COLUMNS;
    
    public function get($key, $value, $options=null) {
        global $DB;

        $key = $this->getFormattedKey($key);
        $sql = sprintf(
            "SELECT %s FROM %s WHERE %s = :value ",
            implode($this->COLUMNS, ', '),
            $this->TABLE_NAME,
            $key
        );
        $sql .= $options ? $options : '';

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':value', $value, $this->setBindType($value));
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, $this->CLASS_NAME);
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function getOr404($key, $value, $options=null) {
        $object = $this->get($key, $value, $options=null);
        if($object) {
            return $object;
        } else {
            include(__DIR__."/../views/404.php");
            exit;
        }
    }
    
    public function filter($key=null, $value=null, $options=null) {
        global $DB;

        $key = $this->getFormattedKey($key);
        $sql = sprintf(
            "SELECT %s FROM %s",
            implode($this->COLUMNS, ', '),
            $this->TABLE_NAME
        );
        $sql .= $key && $value ? " WHERE $key = :value " : '';
        $sql .= $options ? $options : '';

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':value', $value, $this->setBindType($value));
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, $this->CLASS_NAME);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function getLastInsert($id="id") {
        global $DB;

        try {
            $last_insert_id = $DB->lastInsertId();
            return $this->get($id, $last_insert_id);
        } catch (PDOException $e) {
            $e-getMessage();
            exit;
        }
    }

    public function getFormattedKey($key) {
        return $key;
    }

    private function setBindType($value)
    {
        if (is_numeric($value)) {
            return PDO::PARAM_INT;
        } else if (is_string($value)) {
            return PDO::PARAM_STR;
        } else if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        } else if (is_null($value)) {
            return PDO::PARAM_NULL;
        } else {
            return PDO::PARAM_STR;
        }
    }
}