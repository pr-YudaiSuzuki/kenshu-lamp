<?php

require_once __DIR__."/../models/Post.php";
require_once __DIR__."/../models/User.php";
require_once __DIR__."/../models/Tag.php";

function get() {
    $tags = TagManager::filter();

    return include(__DIR__."/../views/tags.php");
}