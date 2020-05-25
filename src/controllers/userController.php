<?php

require_once __DIR__."/../models/Post.php";
require_once __DIR__."/../models/User.php";
require_once __DIR__."/../models/Tag.php";

function get($screen_name) {
    $user = UserManager::getOr404('screen_name', $screen_name);
    
    $posts = PostManager::filter('user_id', $user->id);
    foreach ($posts as $post) {
        $post->user = UserManager::get('id', $post->user_id);
        $post->tags = PostTagsManager::filter('post_id', $post->id);
    }

    return include(__DIR__."/../views/user.php");
}