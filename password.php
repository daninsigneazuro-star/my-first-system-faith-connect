<?php
/** Lets a signed-in user change their own password (also used after a reset). */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

require_login();

$user   = current_user();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $current = (string) ($_POST['current_password'] ?? '');
    $new     = (string) ($_POST['new_password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');

    $statement = db()->prepare('SELECT password_hash FROM users WHERE id = :id');
    $statement->execute(['id' => $user['id']]);
    $hash = (string) ($statement->fetchColumn() ?: '');

    if ($hash === '' || !password_verify($current, $hash)) {
        $errors['current_password'] = 'Your current password is not correct.';
    }

    if (strlen($new) < 8) {
        $errors['new_password'] = 'The new password must be at least 8 characters.';
    }

    if ($new !== $confirm) {
        $errors['confirm_password'] = 'The two passwords do not match.';
    }

    if ($errors === []) {
        db()->prepare('UPDATE users SET password_hash = :hash, must_change_password = 0 WHERE id = :id')
            ->execute(['hash' => password_hash($new, PASSWORD_DEFAULT), 'id' => $user['id']]);

        start_session();
        $_SESSION['user']['must_change_password'] = false;

        log_activity('user.password', 'Changed own password');
        set_flash('success', 'Your password has been updated.');
        redirect('dashboard.php');
    }

    set_flash('error', 'Please correct the highlighted fields.');
}

$pageTitle = 'Change Password';
$activeNav = 'password';

require __DIR__ . '/includes/layout/header.php';
?>

<form class="card" method="post" action="">
    <?= csrf_field() ?>
    <div class="form-grid">
        <div class="field">
            <label for="current_password">Current password *</label>
            <input type="password" id="current_password" name="current_password" required>
            <?php if (isset($errors['current_password'])): ?><span class="hint" style="color:#c62828"><?= e($errors['current_password']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="new_password">New password *</label>
            <input type="password" id="new_password" name="new_password" required minlength="8">
            <?php if (isset($errors['new_password'])): ?><span class="hint" style="color:#c62828"><?= e($errors['new_password']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="confirm_password">Confirm new password *</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
            <?php if (isset($errors['confirm_password'])): ?><span class="hint" style="color:#c62828"><?= e($errors['confirm_password']) ?></span><?php endif; ?>
        </div>
    </div>
    <button class="btn" type="submit">Update password</button>
</form>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
