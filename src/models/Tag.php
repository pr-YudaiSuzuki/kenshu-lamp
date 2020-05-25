<?php

require_once __DIR__."/Base.php";
require_once __DIR__."/User.php";
require_once __DIR__."/Post.php";

class TagManager extends BaseModelManager {
    protected const TABLE_NAME = 'tags';
    protected const CLASS_NAME = 'Tag';
    protected const COLUMNS = array(
        'id',
        'name'
    );

    public static function create($name) {
        global $DB;

        $sql = sprintf(
            "INSERT INTO %s (name) VALUES (:name)",
            static::TABLE_NAME
        );
        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->execute();
            return static::getLastInsert();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function getOrCreate($name) {
        global $DB;
        
        $sql = sprintf(
            "SELECT COUNT(*) AS cnt FROM %s WHERE LOWER(name) = LOWER(:name)",
            static::TABLE_NAME
        );
        
        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->execute();
            $record = $stmt->fetch();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }

        if ($record['cnt'] > 0) {
            return static::get('name', $name);
        } else {
            return static::create($name);
        }
    }

    public static function validate($name) {
        if (!preg_match("/^[ぁ-んァ-ヶーa-zA-Z0-9一-龠０-９、。_-]+$/", $name)) {
            return "記号は_(アンダースコア)と-(ハイフン)のみ利用できます。";
        }

        return null;
    }
}


class PostTagsManager extends BaseModelManager {
    protected const TABLE_NAME = 'tags JOIN post_tags ON tags.id = post_tags.tag_id';
    protected const CLASS_NAME = 'Tag';
    protected const COLUMNS = array(
        'tags.id AS id',
        'tags.name AS name'
    );

    public static function create($post_id, $tag_id) {
        global $DB;
        
        try {
            $sql = "INSERT INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)";
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':tag_id', $tag_id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function update($post_id, $tag_names) {
        static::deleteAll($post_id);
        foreach($tag_names as $tag_name) {
            if ($tag_name) {
                $tag = TagManager::getOrCreate($tag_name);
                static::create($post_id, $tag->id);
            }
        }
    }

    private static function deleteAll($post_id) {
        global $DB;

        $sql = "DELETE FROM post_tags WHERE post_id = :post_id";
        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue('post_id', $post_id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}

class Tag {}