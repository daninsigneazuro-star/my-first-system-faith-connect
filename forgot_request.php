<?php
/** Simple public password reset request page (sends instruction placeholder).
 * This implementation does not send email — it records the request and shows
 * a standard message. For production, integrate with your mailer and token flow.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $identifier = trim((string) ($_POST['identifier'] ?? ''));

    // Log the attempted reset for administrators. Do not reveal whether an
    // account exists to the requester.
    log_activity('password.reset.request', 'Password reset requested for: ' . $identifier);

    set_flash('success', 'If an account exists for that username or email, a password reset instruction has been sent to the registered email address.');
    redirect('login.php');
}

// Show the form
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot password · <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>?v=<?= e(filemtime(__DIR__ . '/assets/css/style.css')) ?>">
</head>
<body class="login-page">
<main class="login-shell">
    <section class="login-card" aria-labelledby="forgot-heading">
        <div class="login-panel login-panel-right" style="max-width:520px;margin:0 auto;">
            <h2 id="forgot-heading">Forgot your password?</h2>
            <p>Enter your username or email address and follow the instructions sent to your registered email.</p>

            <?php foreach (take_flashes() as $flash): ?>
                <div class="alert alert-<?= e($flash['type']) ?>" role="alert"><?= e($flash['message']) ?></div>
            <?php endforeach; ?>

            <form method="post" action="<?= e(url('forgot_request.php')) ?>">
                <?= csrf_field() ?>
                <div class="field">
                    <label for="identifier">Username or email</label>
                    <input type="text" id="identifier" name="identifier" required autofocus>
                </div>
                <div style="display:flex;gap:12px;align-items:center;margin-top:12px;">
                    <button class="btn" type="submit">Send reset instructions</button>
                    <a class="btn btn-light" href="<?= e(url('login.php')) ?>">Back to sign in</a>
                </div>
            </form>
        </div>
    </section>
</main>
</body>
</html>
