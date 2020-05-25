<?php

require_once __DIR__."/../models/Post.php";
require_once __DIR__."/../models/User.php";
require_once __DIR__."/../models/Tag.php";

function get() {
    $tagManager = new TagManager;
    $tags = $tagManager->filter();

    return include(__DIR__."/../views/tags.php");
}