<?php

require_once __DIR__."/Base.php";
require_once __DIR__."/Post.php";

class ImageManager extends BaseModelManager {
    protected const TABLE_NAME = 'images';
    protected const CLASS_NAME = 'Image';
    protected const COLUMNS = array(
        'id',
        'url'
    );

    public static function create($url) {
        global $DB;

        $sql = sprintf(
            "INSERT INTO %s (url) VALUES (:url)",
            static::TABLE_NAME
        );

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':url', $url);
            $stmt->execute();
            return static::getLastInsert();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function upload($file) {
        $filename = static::getFilename($file['tmp_name']);
        $url = UPLOADS_ABSOLUTE_PATH . "/" . $filename;
        if (move_uploaded_file($file['tmp_name'], $url)) {
            return static::create($filename);
        } else {
            return '画像のアップロードに失敗しました。';
        }
    }

    public static function validate($file) {
        if ($file['tmp_name']) {
            if (!isset($file) || !is_uploaded_file($file['tmp_name'])) {
                return 'このファイルはアップロードできません。';
            }
        }

        return null;
    }

    public static function unlink($id) {
        $image = static::get('id', $id);
        unlink(UPLOADS_DIR.$image->url);
    }

    public static function getFormattedFiles($files) {
        $formattedFiles = array();
        for ($i = 0; $i < count($files['name']); $i++) {
            $file = array(
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            );
            $formattedFiles[] = $file;
        }
        return $formattedFiles;
    }

    protected static function getFilename($tmp_name) {
        $filename = md5(uniqid(mt_rand(), true));
        switch (exif_imagetype($tmp_name)) {
            case IMAGETYPE_JPEG :
                $filename .= '.jpg';
                break;
            case IMAGETYPE_GIF :
                $filename .= '.gif';
                break;
            case IMAGETYPE_PNG :
                $filename .= '.png';
                break;
            default :
                return false;
        }

        return $filename;
    }
}

class ThumbnailManager extends BaseModelManager {
    protected const TABLE_NAME = 'post_images INNER JOIN images ON post_images.image_id = images.id AND post_images.is_thumbnail = true';
    protected const CLASS_NAME = 'Image';
    protected const COLUMNS = array(
        'images.id AS id',
        'post_images.post_id AS post_id',
        'images.url AS url'
    );

    public static function create($post_id, $image_id) {
        global $DB;
        
        $sql = (
            "INSERT INTO post_images (post_id, image_id, is_thumbnail)
             VALUES (:post_id, :image_id, true)"
        );

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':image_id', $image_id, PDO::PARAM_INT);
            $stmt->execute(); 
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function updateOrCreate($post_id, $image_id) {
        $thumbnail = static::get('post_id', $post_id);
        if ($thumbnail) {
            static::update($post_id, $image_id);
        } else {
            static::create($post_id, $image_id);
        }
    }

    public static function update($post_id, $image_id) {
        global $DB;

        $sql = (
            "UPDATE post_images SET image_id = :image_id
             WHERE post_id = :post_id AND is_thumbnail = :is_thumbnail"
        );

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':image_id', $image_id);
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':is_thumbnail', true);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function delete($post_id, $image_id) {
        global $DB;

        ImageManager::unlink($image_id);
        
        $sql = (
            "DELETE FROM post_images
             WHERE post_id = :id AND image_id = :image_id"
        );
        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':image_id', $image_id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    protected static function getFormattedKey($key) {
        if ($key == 'post_id') {
            return 'post_images.post_id';
        }
    }
}

class PostImagesManager extends BaseModelManager {
    protected const TABLE_NAME = 'post_images INNER JOIN images ON post_images.image_id = images.id';
    protected const CLASS_NAME = 'Image';
    protected const COLUMNS = array(
        'images.id AS id',
        'post_images.post_id AS post_id',
        'images.url AS url'
    );

    public static function create($post_id, $image_id) {
        global $DB;
        
        $sql = (
            "INSERT INTO post_images (post_id, image_id)
             VALUES (:post_id, :image_id)"
        );

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':image_id', $image_id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function delete($post_id, $image_id) {
        global $DB;

        $sql = "DELETE FROM post_images WHERE post_id = :post_id AND image_id = :image_id";

        try {
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':image_id', $image_id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    protected static function getFormattedKey($key) {
        if ($key == 'post_id') {
            return 'post_images.post_id';
        }
    }
}

class Image {
    private function __construct() {
        $this->url = UPLOADS_DIR . "/" . $this->url;
    }
}