<?php

require_once __DIR__."/../models/Post.php";
require_once __DIR__."/../models/User.php";
require_once __DIR__."/../models/Tag.php";

function get($slug) {
    $post = PostManager::getOr404('slug', $slug);

    $post->thumbnail = ThumbnailManager::get('post_id', $post->id);
    $post->tags = PostTagsManager::filter('post_id', $post->id);
    $post->user = UserManager::get('id', $post->user_id);

    return include(__DIR__."/../views/post.php");
}