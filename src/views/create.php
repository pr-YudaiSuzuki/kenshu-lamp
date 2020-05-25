<?php include __DIR__."/base/header.php" ?>

  <form action="/create.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= CsrfValidator::generate(); ?>">
    <input type="hidden" name="user_id" value="<?= $current_user->id ?>">
    
    <div><input type="text" name="post[title]""></div>
    <div><input type="file" name="thumbnail"></div>
    <div><?= $current_user->screen_name ?></div>
    <div>
      tags
      <ul>
        <li><input type="text" name="tags[]"></li>
        <li><input type="text" name="tags[]"></li>
        <li><input type="text" name="tags[]"></li>
      </ul>
    </div>
    <div>
      images
      <ul>
        <li><input type="file" name="images[]"></li>
        <li><input type="file" name="images[]"></li>
        <li><input type="file" name="images[]"></li>
      </ul>
    </div>
    <div>
      <textarea name="post[body]" rows="30" cols="300"></textarea>
    </div>
    <button>Create</button>
  </form>
  
<?php include __DIR__."/base/footer.php" ?>
  