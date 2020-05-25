<?php

require_once __DIR__."/../app/config.php";
require_once __DIR__."/../app/utils.php";
require_once __DIR__."/Base.php";
require_once __DIR__."/User.php";
require_once __DIR__."/Image.php";
require_once __DIR__."/Tag.php";

class PostManager extends BaseModelManager {
    protected $TABLE_NAME = 'posts';
    protected $CLASS_NAME = 'Post';
    protected $COLUMNS = array(
        'id',
        'slug',
        'title',
        'body',
        'published_at',
        'is_open',
        'user_id'
    );

    public function create($user_id, $title, $body) {
        global $DB;

        $sql = (
            "INSERT INTO $this->TABLE_NAME (user_id, title, body)
             VALUES (:user_id, :title, :body)"
        );

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':title', $this->getFormattedTitle($title));
            $stmt->bindValue(':body', $this->getFormattedBody($body));
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Post Error: ';
            echo $e->getMessage();
            exit;
        }

        return $this->getLastInsert();
    }

    public function update($id, $title, $body) {
        global $DB;

        $sql = "UPDATE $this->TABLE_NAME SET title = :title, body = :body WHERE id = :id";
        
        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':title', $this->getFormattedTitle($title));
            $stmt->bindValue(':body', $this->getFormattedBody($body));
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $this->get('id', $id);
        } catch (PDOExeption $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function delete($slug) {
        global $DB;
        
        $sql = "DELETE FROM $this->TABLE_NAME WHERE slug = :slug";
        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':slug', $slug);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function validate($title, $body) {
        $error = array(
            'title' => $this->validateTitle($title),
            'body' => $this->validateBody($body),
        );

        return array_filter($error);
    }

    public function validateTitle($title) {
        if (!$title) {
            return 'タイトルを入力してください。';
        }

        return null;
    }
    
    public function validateBody($body) {
        if (!$body) {
            return "本文を入力してください。";
        }

        return null;
    }

    public function getFormattedTitle($title) {
        return trim(h($title));
    }

    public function getFormattedBody($body) {
        return trim(h($body));
    }

}

class TagPostsManager extends PostManager {
    protected $TABLE_NAME = 'posts JOIN post_tags ON posts.id = post_tags.post_id';
}

class Post {

}