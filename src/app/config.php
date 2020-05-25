<?php

define('UPLOADS_DIR', "/uploads");
define('UPLOADS_ABSOLUTE_PATH', $_SERVER['DOCUMENT_ROOT']."/uploads");

define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('PDO_DSN', 'mysql:host='.DB_HOST.'; dbname='.DB_NAME.';charset=utf8');

try {
  $DB = new PDO(
    PDO_DSN,
    DB_USER,
    DB_PASSWORD,
    array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    )
  );
} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}