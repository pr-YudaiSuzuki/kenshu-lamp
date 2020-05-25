<?php include __DIR__."/base/header.php" ?>

  <h2>Login</h2>
  <form action="/login.php" method="post">
    <input type="hidden" name="token" value="<?= CsrfValidator::generate(); ?>">
    <div>
      <label>
        <div>ユーザーID:</div>
        <input type="text" name="screen_name" size="30" placeholder="">
      </label>
    </div>
    <div>
      <label>
        <div>パスワード:</div>
        <input type="password" name="password" size="30" placeholder="">
      </label>
    </div>
    <button>ログイン</button>
  </form>
  <a href="/signup.php" >新規登録はコチラ</a>

<?php include __DIR__."/base/footer.php" ?>


