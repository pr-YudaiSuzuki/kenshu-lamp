<?php

require_once __DIR__."/Base.php";
require_once __DIR__."/User.php";
require_once __DIR__."/Post.php";

class TagManager extends BaseModelManager {
    protected $TABLE_NAME = 'tags';
    protected $CLASS_NAME = 'Tag';
    protected $COLUMNS = array(
        'id',
        'name'
    );

    public function create($name) {
        global $DB;

        $sql = "INSERT INTO $this->TABLE_NAME (name) VALUES (:name)";
        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->execute();
            return $this->getLastInsert();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function getOrCreate($name) {
        global $DB;
        
        $sql = (
            "SELECT COUNT(*) AS cnt FROM $this->TABLE_NAME
             WHERE LOWER(name) = LOWER(:name)"
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
            return $this->get('name', $name);
        } else {
            return $this->create($name);
        }
    }

    public function validate($name) {
        if (!preg_match("/^[ぁ-んァ-ヶーa-zA-Z0-9一-龠０-９、。_-]+$/", $name)) {
            return "記号は_(アンダースコア)と-(ハイフン)のみ利用できます。";
        }

        return null;
    }
}


class PostTagsManager extends BaseModelManager {
    protected $TABLE_NAME = 'tags JOIN post_tags ON tags.id = post_tags.tag_id';
    protected $CLASS_NAME = 'Tag';
    protected $COLUMNS = array(
        'tags.id AS id',
        'tags.name AS name'
    );

    public function create($post_id, $tag_id) {
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

    public function update($post_id, $tag_names) {
        $this->reset($post_id);
        $tagManager = new TagManager;
        foreach($tag_names as $tag_name) {
            if ($tag_name) {
                $tag = $tagManager->getOrCreate($tag_name);
                $this->create($post_id, $tag->id);
            }
        }
    }

    private function reset($post_id) {
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

class Tag
{
    public $id;
    public $name;
}