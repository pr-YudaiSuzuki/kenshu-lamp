<?php

require_once __DIR__."/config/environment.php";
require_once APP_DIR."/app/session.php";
require_once APP_DIR."/controllers/userController.php";


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    get($_GET['id']);
} else {
    include APP_DIR."/views/404.php";
}