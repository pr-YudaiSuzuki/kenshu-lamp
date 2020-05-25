<?php include __DIR__."/base/header.php" ?>

  <h1>Tags</h1>
  <ul>
    <?php foreach ($tags as $tag): ?>
    <li>
      <a href="/tag.php?name=<?= $tag->name ?>"><?= $tag->name ?></a>
    </li>
    <?php endforeach ?>
  </ul>

<?php include __DIR__."/base/footer.php" ?>
