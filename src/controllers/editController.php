<?php

require_once __DIR__."/../app/utils.php";
require_once __DIR__."/../models/Post.php";
require_once __DIR__."/../models/User.php";
require_once __DIR__."/../models/Tag.php";
require_once __DIR__."/../models/Image.php";

function get($slug) {
    $post = PostManager::getOr404('slug', $slug);
    
    if ($_SESSION['user_id'] != $post->user_id ) {
        header("Location: /login.php");
    }

    $post->user = UserManager::get('id', $post->user_id);
    $post->thumbnail = ThumbnailManager::get('post_id', $post->id);
    $post->tags = PostTagsManager::filter('post_id', $post->id);
    $post->images = PostImagesManager::filter('post_id', $post->id, "AND is_thumbnail = false");

    return include(__DIR__."/../views/edit.php");
}

function post($data) {
    if (!CsrfValidator::validate($_POST['token'])) {
        header('Content-Type: text/plain; charset=UTF-8', true, 400);
        die('CSRF validation failed.');
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
        var_dump($error);
    }

    try {
        $DB->beginTransaction();
        $post = PostManager::update(
            $data['post']['id'],
            $data['post']['title'],
            $data['post']['body']
        );


        if ($_FILES['thumbnail']['tmp_name']) {
            $thumbnail = ImageManager::upload($_FILES['thumbnail']);
            ThumbnailManager::updateOrCreate($post->id, $thumbnail->id);
        }

        foreach ($image_files as $image_file) {
            if ($image_file['tmp_name']) {
                $image = ImageManager::upload($image_file);
                PostImagesManager::create($post->id, $image->id);
            }
        }

        PostTagsManager::update(
            $data['post']['id'],
            array_unique($data['tags'])
        );

        if ($data['del_images']) {
            foreach ($data['del_images'] as $image_id) {
                PostImagesManager::delete($post->id, $image_id);
            }
        }

        $DB->commit();
    } catch (PDOException $e) {
        $DB->rollBack();
        echo $e->getMessage();
        exit;
    }

    return header("Location: /edit.php?id=$post->slug");
}
