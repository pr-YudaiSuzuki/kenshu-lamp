<?php include __DIR__."/base/header.php" ?>

  <?php if ($current_user->id == $post->user->id): ?>
    [<a href="/edit.php?id=<?= $post->slug ?>">EDIT</a>]
  <?php endif ?>
  <h1><?= $post->title ?></h1>
  <p><?= $post->user->screen_name ?></p>
  <img src="<?= $post->thumbnail->url ?>">
  <div>
  <?php foreach ($post->tags as $tag): ?>
  <a href="/tag.php?name=<?= $tag->name ?>"><?= $tag->name ?></a>
  <?php endforeach ?>
  </div>
  <div>
    <?= h($post->body) ?>
  </div>

<?php include __DIR__."/base/footer.php" ?>
 