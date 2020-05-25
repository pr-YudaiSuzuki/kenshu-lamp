<?php include __DIR__."/base/header.php" ?>

  <h2>Login</h2>
  <form action="/login.php" method="post">
    <input type="hidden" name="token" value="<?= CsrfValidator::generate(); ?>">
    <label>User ID:
        <input type="text" name="screen_name" >
    </label>
    <label>Password:
        <input type="password" name="password" >
    </label>
    <button>Login</button>
  </form>
  <a href="/signup.php" >Sing up?</a>

<?php include __DIR__."/base/footer.php" ?>


