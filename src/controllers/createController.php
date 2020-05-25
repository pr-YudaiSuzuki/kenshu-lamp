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
    
    $postManager = new PostManager;
    $imageManager = new ImageManager;
    $thumbnailManager = new ThumbnailManager;
    $postImagesManager = new PostImagesManager;
    $tagManager = new TagManager;
    $postTagsManager = new PostTagsManager;
    

    $error = array();
    $error['post'] = $postManager->validate($data['post']['title'], $data['post']['body']);
    $error['thumbnail'] = $imageManager->validate($_FILES['thumbnail']);
    
    $image_files = $imageManager->getFormattedFiles($_FILES['images']);
    foreach ($image_files as $image_file) {
        $error['images'][] = $imageManager->validate($image_file);
    }
    
    foreach ($data['tags'] as $tag_name) {
        if ($tag_name) {
            $error['tags'][] = $tagManager->validate($tag_name);
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
        $post = $postManager->create(
            $data['user_id'],
            $data['post']['title'],
            $data['post']['body']
        );

        if ($_FILES['thumbnail']['tmp_name']) {
            $thumbnail = $imageManager->upload($_FILES['thumbnail']);
            $thumbnailManager->create($post->id, $thumbnail->id);
        }
        
        foreach ($image_files as $image_file) {
            if ($image_file['tmp_name']) {
                $image = $imageManager->upload($image_file);
                $postImagesManager->create($post->id, $image->id);
            }
        }

        foreach($data['tags'] as $tag_name) {
            if ($tag_name) {
                $tag = $tagManager->getOrCreate($tag_name);
                $postTagsManager->create($post->id, $tag->id);
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

