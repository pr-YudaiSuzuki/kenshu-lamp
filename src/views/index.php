<?php include __DIR__."/base/header.php" ?>

  <ul>
    <?php foreach ($posts as $post): ?>
    <li>
      <a href="/post.php?id=<?= $post->slug ?>"><?= $post->title ?></a> - 
      <a href="/user.php?id=<?= $post->user->screen_name ?>">@<?= $post->user->screen_name ?></a>
      <?php foreach ($post->tags as $tag): ?>
      <a href="/tag.php?name=<?= $tag->name ?>""><?= $tag->name ?></a>
      <?php endforeach ?>
    </li>
    <?php endforeach ?>
  </ul>

<?php include __DIR__."/base/footer.php" ?>


  