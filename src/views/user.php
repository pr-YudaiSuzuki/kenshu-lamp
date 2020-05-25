<?php include __DIR__."/base/header.php" ?>

  <h1><?= $user->name ?>@<?= $user->screen_name ?>さんの投稿記事一覧</h1>
  <?php if ($user->id == $current_user->id) : ?>
  [<a href="/create.php">記事作成</a>] [<a href="/logout.php">ログアウト</a>]
  <?php endif ?>
  <ul>
    <?php foreach ($posts as $post): ?>
    <li>
      <a href="/post.php?id=<?= $post->slug ?>"><?= $post->title ?></a> - 
      <a href="/user.php?id=<?= $user->screen_name ?>">@<?= $user->screen_name ?></a>
      <?php foreach ($post->tags as $tag): ?>
      <a href="/tag.php?name=<?= $tag->name ?>""><?= $tag->name ?></a>
      <?php endforeach ?>
    </li>
    <?php endforeach ?>
  </ul>

<?php include __DIR__."/base/footer.php" ?>
