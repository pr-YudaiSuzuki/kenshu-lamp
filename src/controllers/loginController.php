<?php

require_once __DIR__."/../app/utils.php";
require_once __DIR__."/../models/User.php";

function get() {
    return include(__DIR__."/../views/login.php");
}

function post($screen_name, $password) {
    if (!CsrfValidator::validate($_POST['token'])) {
        header('Content-Type: text/plain; charset=UTF-8', true, 400);
        die('CSRF validation failed.');
    }

    $user = UserManager::login($screen_name, $password);

    if ($user) {
        session_regenerate_id();
        $_SESSION['user_id'] = $user->id;
        return header("Location: /user.php?id=$user->screen_name");
    } else {
        return header("Location: /login.php");
    }
}