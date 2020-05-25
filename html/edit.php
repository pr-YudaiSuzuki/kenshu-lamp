<?php

require_once __DIR__."/config/environment.php";
require_once APP_DIR."/app/session.php";
require_once APP_DIR."/controllers/editController.php";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    get($_GET['id']);
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    post($_POST);
}