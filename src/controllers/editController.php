<?php

require_once __DIR__."/../app/utils.php";
require_once __DIR__."/../models/Post.php";
require_once __DIR__."/../models/User.php";
require_once __DIR__."/../models/Tag.php";
require_once __DIR__."/../models/Image.php";

function get($slug) {
    $postManager = new PostManager;
    $userManager = new UserManager;
    $imageManager = new ImageManager;
    $thumbnailManager = new ThumbnailManager;
    $postImagesManager = new PostImagesManager;
    $postTagsManager = new PostTagsManager;

    $post = $postManager->getOr404('slug', $slug);
    
    if ($_SESSION['user_id'] != $post->user_id ) {
        header("Location: /login.php");
    }

    $post->user = $userManager->get('id', $post->user_id);
    $post->thumbnail = $thumbnailManager->get('post_id', $post->id);
    $post->tags = $postTagsManager->filter('post_id', $post->id);
    $post->images = $postImagesManager->filter('post_id', $post->id, "AND is_thumbnail = false");

    return include(__DIR__."/../views/edit.php");
}

function post($data) {
    if (!CsrfValidator::validate($_POST['token'])) {
        header('Content-Type: text/plain; charset=UTF-8', true, 400);
        die('CSRF validation failed.');
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
        var_dump($error);
        // header("Location: /index.php");
    }

    try {
        $DB->beginTransaction();
        $post = $postManager->update(
            $data['post']['id'],
            $data['post']['title'],
            $data['post']['body']
        );


        if ($_FILES['thumbnail']['tmp_name']) {
            $thumbnail = $imageManager->upload($_FILES['thumbnail']);
            $thumbnailManager->updateOrCreate($post->id, $thumbnail->id);
        }

        foreach ($image_files as $image_file) {
            if ($image_file['tmp_name']) {
                $image = $imageManager->upload($image_file);
                $postImagesManager->create($post->id, $image->id);
            }
        }

        $postTagsManager->update(
            $data['post']['id'],
            array_unique($data['tags'])
        );

        if ($data['del_images']) {
            foreach ($data['del_images'] as $image_id) {
                $postImagesManager->delete($post->id, $image_id);
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
