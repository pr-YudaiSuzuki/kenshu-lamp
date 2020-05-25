<?php

require_once __DIR__."/../models/Post.php";
require_once __DIR__."/../models/User.php";
require_once __DIR__."/../models/Tag.php";

function get($name) {
    $tagManager = new TagManager;
    $tagPostsManager = new TagPostsManager;
    $userManager = new UserManager;
    $postTagsManager = new PostTagsManager;

    $tag = $tagManager->getOr404('name', $name);
    
    $posts = $tagPostsManager->filter('tag_id', $tag->id);
    foreach ($posts as $post) {
        $post->user = $userManager->get('id', $post->user_id);
        $post->tags = $postTagsManager->filter('post_id', $post->id);
    }

    return include(__DIR__."/../views/tag.php");
}