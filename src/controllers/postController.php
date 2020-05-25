<?php

require_once __DIR__."/../models/Post.php";
require_once __DIR__."/../models/User.php";
require_once __DIR__."/../models/Tag.php";

function get($slug) {
    $postManager = new PostManager;
    $thumbnailManager = new ThumbnailManager;
    $userManager = new UserManager;
    $postTagsManager = new PostTagsManager;

    $post = $postManager->getOr404('slug', $slug);

    $post->thumbnail = $thumbnailManager->get('post_id', $post->id);
    $post->tags = $postTagsManager->filter('post_id', $post->id);
    $post->user = $userManager->get('id', $post->user_id);

    return include(__DIR__."/../views/post.php");
}