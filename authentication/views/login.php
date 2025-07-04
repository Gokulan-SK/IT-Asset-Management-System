<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/pages/login.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css" />
</head>

<body>
  <section class="flex-container">
    <div class="intro-text">
      <div class="icon-header">
        <h3>Asset Management System</h3>
      </div>
      <h2>Manage your assets effortlessly</h2>
      <p>
        Welcome to the Asset Management System. Please log in to access your
        account and manage your assets efficiently. If you don't have an
        account, please contact your administrator to create one.
      </p>
    </div>
    <div class="form-container">
      <h6>WELCOME BACK</h6>
      <br />
      <h3>Login to start your session</h3>
      <br />
      <?php

      $errors = $errors ?? [];

      ?>


      <div class="form">
        <form action="" method="post">
          <div class="input-field">
            <div class="input-group">
              <input type="text" name="username" id="username" placeholder="" required autocomplete="" value="" />
              <label for="username">Email</label>
              <?php if (!empty($errors['usernameError'])): ?>
                <p class="error-text"><?php echo htmlspecialchars($errors['usernameError']); ?></p>
              <?php endif; ?>
            </div>
            <br />
            <div class="input-group">
              <input type="password" name="password" id="password" placeholder="" required autocomplete="new-password"
                value="" />
              <label for="password">Password</label>
              <?php if (!empty($errors['passwordError'])): ?>
                <p class="error-text"><?php echo htmlspecialchars($errors['passwordError']); ?></p>
              <?php endif; ?>
            </div>
          </div>
          <br />
          <div class="form-footer">
            <label for="remember-me"><input type="checkbox" name="remember-me" id="remember-me" value="1" />Remember
              me</label>
            <a href="<?= BASE_URL ?>forgot-password" id="forgot-pwd">Forgot password?</a>
          </div>
          <br /><br />
          <div class="submit-button">
            <input type="submit" value="Login" />
          </div>
        </form>
      </div>
    </div>
  </section>
</body>

</html>