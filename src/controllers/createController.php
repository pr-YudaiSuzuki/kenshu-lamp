<?php

require_once __DIR__."/../app/utils.php";
require_once __DIR__."/../models/Post.php";
require_once __DIR__."/../models/Image.php";

function get() {
    if ($_SESSION['user_id']) {
        return include(__DIR__."/../views/create.php");
    } else {
        return header('Location: /login.php');
    }
}

function post($data=null) {
    if (!CsrfValidator::validate($_POST['token'])) {
        header('Content-Type: text/plain; charset=UTF-8', true, 400);
        die('CSRF validation failed.');
    }

    if ($data['user_id'] != $_SESSION['user_id']) {
        header("Location: /login.php");
    }
    
    global $DB;
    
    $error = array();
    $error['post'] = PostManager::validate($data['post']['title'], $data['post']['body']);
    $error['thumbnail'] = ImageManager::validate($_FILES['thumbnail']);
    
    $image_files = ImageManager::getFormattedFiles($_FILES['images']);
    foreach ($image_files as $image_file) {
        $error['images'][] = ImageManager::validate($image_file);
    }
    
    foreach ($data['tags'] as $tag_name) {
        if ($tag_name) {
            $error['tags'][] = TagManager::validate($tag_name);
        }
    }
    
    $error['images'] = array_filter($error['images']);
    $error['tags'] = array_filter($error['tags']);
    $error = array_filter($error);

    if ($error) {
        header("Location: /create.php");
    }


    try {
        $DB->beginTransaction();
        $post = PostManager::create(
            $data['user_id'],
            $data['post']['title'],
            $data['post']['body']
        );

        if ($_FILES['thumbnail']['tmp_name']) {
            $thumbnail = ImageManager::upload($_FILES['thumbnail']);
            ThumbnailManager::create($post->id, $thumbnail->id);
        }
        
        foreach ($image_files as $image_file) {
            if ($image_file['tmp_name']) {
                $image = ImageManager::upload($image_file);
                PostImagesManager::create($post->id, $image->id);
            }
        }

        foreach($data['tags'] as $tag_name) {
            if ($tag_name) {
                $tag = TagManager::getOrCreate($tag_name);
                PostTagsManager::create($post->id, $tag->id);
            }
        }

        $DB->commit();
    } catch (PDOException $e) {
        $DB->rollBack();
        echo $e->getMessage();
        exit;
    }

    header("Location: /edit.php?id=$post->slug");
}

