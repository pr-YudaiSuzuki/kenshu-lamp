<?php

require_once __DIR__."/config/environment.php";
require_once APP_DIR."/app/session.php";
require_once APP_DIR."/controllers/signupController.php";


if ($_SERVER['REQUEST_METHOD'] == "GET") {
    get();
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    post($_POST['screen_name'], $_POST['name'], $_POST['password']);
}