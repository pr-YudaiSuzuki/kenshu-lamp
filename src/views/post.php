<?php include __DIR__."/base/header.php" ?>

  <?php if ($current_user->id == $post->user->id): ?>
    [<a href="/edit.php?id=<?= $post->slug ?>">記事編集</a>]
  <?php endif ?>
  <h1><?= $post->title ?></h1>
  <p><?= $post->user->screen_name ?></p>
  <?php if ($post->thumbnail): ?>
  <img src="<?= $post->thumbnail->url ?>">
  <?php endif ?>
  <?php if ($post->images): ?>
  <ul>
    <?php foreach ($post->images as $image): ?>
    <li><img src="<?= $image->url ?>" width="160"></li>
    <?php endforeach ?>
  </ul>
  <?php endif ?>
  <?php if ($post->tags): ?>
  <p>
    <?php foreach ($post->tags as $tag): ?>
    <a href="/tag.php?name=<?= $tag->name ?>"><?= $tag->name ?></a>
    <?php endforeach ?>
  </p>
  <?php endif ?>
  <div>
    <?= h($post->body) ?>
  </div>

<?php include __DIR__."/base/footer.php" ?>
 