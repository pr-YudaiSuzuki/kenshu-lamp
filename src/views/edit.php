<?php include __DIR__."/base/header.php" ?>
  [<a href="/post.php?id=<?= $post->slug ?>">PAGE</a>]
  <form action="/edit.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= CsrfValidator::generate(); ?>">
    <input type="hidden" name="user_id" value="<?= $post->user_id ?>">
    <input type="hidden" name="post[slug]" value="<?= $post->slug ?>">
    
    <p>
      <label>
        <div>タイトル：</div>
        <input type="text" name="post[title]" value="<?= $post->title ?>">
      </label>
    </p>
    <p>
      <div>サムネイルの変更：</div>
      <div><input type="file" name="thumbnail"></div>
    </p>
    <p>
      <div>現在のサムネイル</div>
      <img width="320" src="<?= $post->thumbnail->url ?>">
    </p>
    <div>
      タグの編集：
      <ul>
        <?php foreach ($post->tags as $tag): ?>
          <li><input type="text" name="tags[]" value="<?= $tag->name ?>"></li>
        <?php endforeach ?>
          <li><input type="text" name="tags[]"></li>
          <li><input type="text" name="tags[]"></li>
          <li><input type="text" name="tags[]"></li>
      </ul>
    </div>
    <div>
      画像の編集：
      <ul>
        <?php foreach ($post->images as $image): ?>
          <li><label><img width="100" src="<?= $image->url ?>"> 削除[<input type="checkbox" name="del_images[]" value="<?= $image->id ?>">]</label></li>
        <?php endforeach ?>
        <li><input type="file" name="images[]"></li>
        <li><input type="file" name="images[]"></li>
        <li><input type="file" name="images[]"></li>
      </ul>
    </div>
    <div>
    </div>
    </div>
    <div>
      <div>本文：</div>
      <textarea name="post[body]" rows="30" cols="300"><?= $post->body ?></textarea>
    </div>
    <button>更新</button>
  </form>
  <form action="/delete.php" method="post">
    <input type="hidden" name="token" value="<?= CsrfValidator::generate(); ?>">
    <input type="hidden" name="slug" value="<?= $post->slug ?>">
    <button>削除</button>
  </form>
<?php include __DIR__."/base/footer.php" ?>
  