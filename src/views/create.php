<?php include __DIR__."/base/header.php" ?>

  <form action="/create.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= CsrfValidator::generate(); ?>">
    <input type="hidden" name="user_id" value="<?= $current_user->id ?>">
    
    <p>
      <label>
        <div>タイトル：</div>
        <input type="text" name="post[title]" value="<?= $post->title ?>">
      </label>
    </p>
    <p>
      <div>サムネイルの選択：</div>
      <input type="file" name="thumbnail">
    </p>
    <div>
      タグの追加：
      <ul>
        <li><input type="text" name="tags[]"></li>
        <li><input type="text" name="tags[]"></li>
        <li><input type="text" name="tags[]"></li>
      </ul>
    </div>
    <div>
      画像のアップロード：
      <ul>
        <li><input type="file" name="images[]"></li>
        <li><input type="file" name="images[]"></li>
        <li><input type="file" name="images[]"></li>
      </ul>
    </div>
    <div>
      <div>本文：</div>
      <textarea name="post[body]" rows="30" cols="300"></textarea>
    </div>
    <button>作成</button>
  </form>
  
<?php include __DIR__."/base/footer.php" ?>
  