<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/pages/login.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css" />
</head>

<body>
    <section class="flex-container">
        <div class="intro-text">
            <div class="icon-header">
                <h3>Asset Management System</h3>
            </div>
            <h2>Password Reset Assistance</h2>
            <p>
                Enter your registered email to receive a password reset link. If your account exists, you will get a
                reset link. Contact your admin if you're unable to
                reset it.
            </p>
        </div>

        <div class="form-container">
            <h6>FORGOT PASSWORD</h6>
            <br />
            <h3>Reset your password</h3>
            <br />
            <?php if (!empty($message)): ?>
                <p style="color: green;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
            <?php if (!empty($errors['emailError'])): ?>
                <p class="error-text"><?= htmlspecialchars($errors['emailError']) ?></p>
            <?php endif; ?>

            <div class="form">
                <form action="" method="post">
                    <div class="input-field">
                        <div class="input-group">
                            <input type="email" name="email" id="email" placeholder="" required value="" />
                            <label for="email">Registered Email</label>
                        </div>
                    </div>
                    <br /><br />
                    <div class="submit-button">
                        <input type="submit" value="Send Reset Link" />
                    </div>
                    <br />
                    <div style="text-align: center;">
                        <a href="<?= BASE_URL ?>login">Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>

</html>