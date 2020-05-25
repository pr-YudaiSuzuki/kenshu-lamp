<?php

require_once __DIR__."/../app/utils.php";
require_once __DIR__."/../models/User.php";

function get() {
    return include(__DIR__."/../views/signup.php");
}

function post($screen_name, $name, $password) {
    if (!CsrfValidator::validate($_POST['token'])) {
        header('Content-Type: text/plain; charset=UTF-8', true, 400);
        die('CSRF validation failed.');
    }

    $userManager = new UserManager;

    $error = $userManager->validate($screen_name, $name, $password);
    
    if ($error) {
        header("Location: /signup.php");
    } else {
        $user = $userManager->create($screen_name, $name, $password);
        $_SESSION['user_id'] = $user->id;
        header("Location: /user.php?id=$screen_name");
    }
}