<?php

require_once __DIR__."/config/environment.php";
require_once APP_DIR."/app/session.php";
require_once APP_DIR."/controllers/tagsController.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    get();
} else {
    include APP_DIR."/views/404.php";
}