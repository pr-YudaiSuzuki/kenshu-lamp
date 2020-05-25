<?php include __DIR__."/base/header.php" ?>
  [<a href="/post.php?id=<?= $post->slug ?>">PAGE</a>]
  <form action="/edit.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= CsrfValidator::generate(); ?>">
    <input type="hidden" name="user_id" value="<?= $post->user_id ?>">
    <input type="hidden" name="post[id]" value="<?= $post->id ?>">
    
    <div><input type="text" name="post[title]" value="<?= $post->title ?>"></div>
    <div>
      <img width="160" src="<?= $post->thumbnail->url ?>">
      <input type="file" name="thumbnail">
    </div>
    <div><?= $post->screen_name ?>
    <ul>
      <?php foreach ($post->tags as $tag): ?>
        <li><input type="text" name="tags[]" value="<?= $tag->name ?>"></li>
      <?php endforeach ?>
        <li><input type="text" name="tags[]"></li>
        <li><input type="text" name="tags[]"></li>
    </ul>
    <ul>
      <?php foreach ($post->images as $image): ?>
        <li><label><input type="checkbox" name="del_images[]" value="<?= $image->id ?>"><img width="100" src="<?= $image->url ?>"><?= $image->url ?></label></li>
      <?php endforeach ?>
    </ul>
    <div>
      <ul>
        <li><input type="file" name="images[]"></li>
        <li><input type="file" name="images[]"></li>
        <li><input type="file" name="images[]"></li>
      </ul>
    </div>
    </div>
    <div>
      <textarea name="post[body]" rows="30" cols="300"><?= $post->body ?></textarea>
    </div>
    <button>Update</button>
  </form>
  <form action="/delete.php" method="post">
    <input type="hidden" name="token" value="<?= CsrfValidator::generate(); ?>">
    <input type="hidden" name="slug" value="<?= $post->slug ?>">
    <button>Delete</button>
  </form>
<?php include __DIR__."/base/footer.php" ?>
  