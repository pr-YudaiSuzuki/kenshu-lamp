<?php

require_once __DIR__."/../models/User.php";

session_start();

function getCurrentUser() {
  if ($_SESSION['user_id']) {
    $userManager = new UserManager;
    return $userManager->get('id', $_SESSION['user_id']);
  }
}
