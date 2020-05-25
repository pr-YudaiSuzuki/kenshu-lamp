<?php

require_once __DIR__.'/../app/config.php';

abstract class BaseModelManager {
    protected const TABLE_NAME = null;
    protected const CLASS_NAME = null;
    protected const COLUMNS = null;
    
    public static function get($key, $value, $options=null) {
        global $DB;

        $key = static::getFormattedKey($key);
        $sql = sprintf(
            "SELECT %s FROM %s WHERE %s = :value ",
            implode(static::COLUMNS, ', '),
            static::TABLE_NAME,
            $key
        );
        $sql .= $options ? $options : '';

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':value', $value, static::setBindType($value));
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, static::CLASS_NAME);
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function getOr404($key, $value, $options=null) {
        $object = static::get($key, $value, $options=null);
        if($object) {
            return $object;
        } else {
            include(__DIR__."/../views/404.php");
            exit;
        }
    }
    
    public static function filter($key=null, $value=null, $options=null) {
        global $DB;

        $key = static::getFormattedKey($key);
        $sql = sprintf(
            "SELECT %s FROM %s",
            implode(static::COLUMNS, ', '),
            static::TABLE_NAME
        );
        $sql .= $key && $value ? " WHERE $key = :value " : '';
        $sql .= $options ? $options : '';

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':value', $value, static::setBindType($value));
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, static::CLASS_NAME);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    protected static function getLastInsert($id="id") {
        global $DB;

        try {
            $last_insert_id = $DB->lastInsertId();
            return static::get($id, $last_insert_id);
        } catch (PDOException $e) {
            $e-getMessage();
            exit;
        }
    }

    protected static function getFormattedKey($key) {
        return $key;
    }

    private static function setBindType($value)
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