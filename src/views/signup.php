<?php include __DIR__."/base/header.php" ?>

  <h2>Sign up</h2>
  <form action="/signup.php" method="post">
    <input type="hidden" name="token" value="<?= CsrfValidator::generate(); ?>">
    <label>User ID:
        <input type="text" name="screen_name" >
    </label>
    <label>Name:
        <input type="text" name="name" >
    </label>
    <label>Password:
        <input type="password" name="password" >
    </label>
    <button>Register</button>
  </form>

<?php include __DIR__."/base/footer.php" ?>

  