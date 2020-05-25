<?php

require_once __DIR__."/../app/utils.php";
require_once __DIR__."/../models/Post.php";

function post($slug) {
    if (!CsrfValidator::validate($_POST['token'])) {
        header('Content-Type: text/plain; charset=UTF-8', true, 400);
        die('CSRF validation failed.');
    }

    $post = PostManager::get('slug', $slug);
    $user = UserManager::get('id', $post->user_id);

    if ($_SESSION['user_id'] === $user->id) {
        PostManager::delete($slug);
        return header("Location: /user.php?id=$user->screen_name");
    } else {
        return header("Location: /login.php");
    }
}