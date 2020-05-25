<?php include __DIR__."/base/header.php" ?>

  <h2>新規登録</h2>
  <form action="/signup.php" method="post">
    <input type="hidden" name="token" value="<?= CsrfValidator::generate(); ?>">
    <div>
      <label>
        <div>ユーザーID:</div>
        <input type="text" name="screen_name" size="30" placeholder="半角英数と_(アンダーバー)">
      </label>
    </div>
    <div>
      <label>
        <div>表示名:</div>
        <input type="text" name="name" size="30" placeholder="50文字以下">
      </label>
    </div>
    <div>
      <label>
        <div>パスワード:</div>
        <input type="password" name="password" size="30" placeholder="8文字以上の半角英数記号">
      </label>
    </div>
    <button>登録</button>
  </form>

<?php include __DIR__."/base/footer.php" ?>

  