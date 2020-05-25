<?php

require_once __DIR__."/../app/config.php";
require_once __DIR__."/../app/utils.php";
require_once __DIR__."/Base.php";
require_once __DIR__."/User.php";
require_once __DIR__."/Image.php";
require_once __DIR__."/Tag.php";

class PostManager extends BaseModelManager {
    protected const TABLE_NAME = 'posts';
    protected const CLASS_NAME = 'Post';
    protected const COLUMNS = array(
        'id',
        'slug',
        'title',
        'body',
        'published_at',
        'is_open',
        'user_id'
    );

    public static function create($user_id, $title, $body) {
        global $DB;

        $sql = sprintf(
            "INSERT INTO %s (user_id, title, body) VALUES (:user_id, :title, :body)",
            static::TABLE_NAME
        );

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':title', static::getFormattedTitle($title));
            $stmt->bindValue(':body', static::getFormattedBody($body));
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Post Error: ';
            echo $e->getMessage();
            exit;
        }

        return static::getLastInsert();
    }

    public static function update($id, $title, $body) {
        global $DB;

        $sql = sprintf(
            "UPDATE %s SET title = :title, body = :body WHERE id = :id",
            static::TABLE_NAME
        );
        
        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':title', static::getFormattedTitle($title));
            $stmt->bindValue(':body', static::getFormattedBody($body));
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return static::get('id', $id);
        } catch (PDOExeption $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function delete($slug) {
        global $DB;
        
        $sql = sprintf(
            "DELETE FROM %s WHERE slug = :slug",
            static::TABLE_NAME
        );
        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':slug', $slug);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function validate($title, $body) {
        $error = array(
            'title' => static::validateTitle($title),
            'body' => static::validateBody($body),
        );

        return array_filter($error);
    }

    protected static function validateTitle($title) {
        if (!$title) {
            return 'タイトルを入力してください。';
        }

        return null;
    }
    
    protected static function validateBody($body) {
        if (!$body) {
            return "本文を入力してください。";
        }

        return null;
    }

    protected static function getFormattedTitle($title) {
        return trim(h($title));
    }

    protected static function getFormattedBody($body) {
        return trim(h($body));
    }

}

class TagPostsManager extends PostManager {
    protected const TABLE_NAME = 'posts JOIN post_tags ON posts.id = post_tags.post_id';
}

class Post {}