<?php

require_once __DIR__."/../models/Post.php";
require_once __DIR__."/../models/User.php";
require_once __DIR__."/../models/Tag.php";

function get($screen_name) {
    $postManager = new PostManager;
    $userManager = new UserManager;
    $postTagsManager = new PostTagsManager;

    $user = $userManager->getOr404('screen_name', $screen_name);
    
    $posts = $postManager->filter('user_id', $user->id);
    foreach ($posts as $post) {
        $post->user = $userManager->get('id', $post->user_id);
        $post->tags = $postTagsManager->filter('post_id', $post->id);
    }

    return include(__DIR__."/../views/user.php");
}