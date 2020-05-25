<?php

require_once __DIR__."/Base.php";
require_once __DIR__."/Post.php";

class UserManager extends BaseModelManager
{
    protected const TABLE_NAME = 'users';
    protected const CLASS_NAME = 'User';
    protected const COLUMNS = array(
        'id',
        'screen_name',
        'name'
    );

    public static function create($screen_name, $name, $password) {
        global $DB;

        $sql = sprintf(
            "INSERT INTO %s (screen_name, name, password_hash) VALUES (:screen_name, :name, :password_hash)",
            static::TABLE_NAME
        );

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':screen_name', $screen_name);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':password_hash', password_hash($password, PASSWORD_DEFAULT));
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
        
        return static::getLastInsert();
    }

    public static function login($screen_name, $password) {
        global $DB;

        $sql = sprintf(
            "SELECT id, password_hash FROM %s WHERE screen_name = :screen_name",
            static::TABLE_NAME
        );
        
        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':screen_name', $screen_name);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $record = $stmt->fetch();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }

        if (password_verify($password, $record['password_hash'])) {
            return static::get('id', $record['id']);
        } else {
            return null;
        }
    }

    public static function validate($screen_name, $name, $password) {
        $error = array(
            'screen_name' => static::validateScreenName($screen_name),
            'name' => static::validateName($name),
            'password' => static::validatePassword($password),
        );

        return array_filter($error);
    }

    public static function validateScreenName($screen_name) {
        if (!$screen_name) {
            return "IDを入力してください。";
        }
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $screen_name)) {
            return "記号は _ (アンダースコア)のみ使えます。";
        }
        if (strlen($screen_name) > 20) {
            return "20文字以下にしてください。";
        }

        global $DB;
        $stmt = $DB->prepare(
            "SELECT COUNT(*) AS cnt
             FROM users
             WHERE LOWER(users.screen_name)=LOWER(:screen_name)"
        );
        $stmt->bindValue(':screen_name', $screen_name);
        $stmt->execute();
        $record = $stmt->fetch();

        if ($record['cnt'] > 0) {
            return 'このIDはすでに存在するため利用できません。';
        }

        return null;
    }

    public static function validateName($name) {
        if (!$name) {
            return "表示名を入力してください。";
        }
        if ($name && strlen($name) > 50) {
            return "50文字以下にしてください。";
        } 
        
        return null;
    }

    public static function validatePassword($password) {
        if (!$password) {
            return "パスワードを入力してください。";
        }
        if (strlen($password) < 8) {
            return "8文字以上にしてください。";
        } 
        if (!preg_match("/^[!-~]+$/", $password)) {
            return "半角英数字と記号のみ利用できます。";
        }

        return null;
    }
}

class User {}