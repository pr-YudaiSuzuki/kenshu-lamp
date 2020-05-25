<?php
 require_once __DIR__."/../../app/session.php";
 $current_user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kenshu backend</title>
</head>
<body>
  <h1>
    <a href="/">Kenshu</a>
  </h1>
  <?php if ($current_user): ?>
  [<a href="/user.php?id=<?= $current_user->screen_name ?>">マイページ</a>]
  <?php else: ?>
  [<a href="/login.php">ログイン</a>]
  <?php endif ?>

