<?php

require_once __DIR__."/config/environment.php";
require_once APP_DIR."/app/session.php";
require_once APP_DIR."/controllers/deleteController.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    post($_POST['slug']);
} else {
    include APP_DIR."/views/404.php";
}