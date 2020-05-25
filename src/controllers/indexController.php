<?php

require_once __DIR__."/../models/Post.php";
require_once __DIR__."/../models/User.php";
require_once __DIR__."/../models/Tag.php";

function get() {
    $postManager = new PostManager;
    $userManager = new UserManager;
    $postTagsManager = new PostTagsManager;

    $posts = $postManager->filter();
    foreach ($posts as $post) {
        $post->user = $userManager->get('id', $post->user_id);
        $post->tags = $postTagsManager->filter('post_id', $post->id);
    }

    return include(__DIR__."/../views/index.php");
}