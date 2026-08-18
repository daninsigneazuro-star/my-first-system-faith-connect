<?php
/** Admin login and authentication check. */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        set_flash('error', 'Please enter both your username and password.');
    } else {
        [$success, $error] = attempt_login($username, $password);

        if ($success) {
            redirect('dashboard.php');
        }

        set_flash('error', $error);
    }
}

$flashes = take_flashes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Authorized staff sign in for the Faith Connect church registry system.">
    <title>Staff sign in · <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>?v=<?= e(filemtime(__DIR__ . '/assets/css/style.css')) ?>">
</head>
<body class="login-page">
<div class="login-background" aria-hidden="true">
    <span class="active" data-slide="1"></span>
    <span data-slide="2"></span>
</div>
<main class="login-shell">
    <section class="login-card" aria-labelledby="signin-heading">
        <div class="login-panel login-panel-left">
            <img class="logo-img" src="<?= e(url('assets/img/parish-logo.jpg')) ?>" alt="Official seal of Holy Cross Parish" width="120" height="120">
            <p class="login-parish">Holy Cross Parish · Carigara, Leyte</p>
            <h1><?= e(APP_NAME) ?></h1>
            <p class="subtitle">Church Registry System</p>
            <div class="login-divider-icon" aria-hidden="true">✛</div>
            <div class="login-quote">
                <p>"Rejoice that your names are written in heaven."</p>
                <p class="quote-author"><em>— Luke 10:20</em></p>
                <p class="quote-footer"><code>Faith Connect Admin Portal | Preserving the Sacred Milestones of God's Family</code></p>
            </div>
        </div>

        <div class="login-panel login-panel-right">
            <div class="form-header">
                <div class="form-icon" aria-hidden="true">✛</div>
                <div>
                    <h2 id="signin-heading">Welcome Back</h2>
                    <p>Sign in to continue to Faith Connect</p>
                </div>
            </div>

            <?php foreach ($flashes as $flash): ?>
                <div class="alert alert-<?= e($flash['type']) ?>" role="alert"><?= e($flash['message']) ?></div>
            <?php endforeach; ?>

            <form method="post" action="<?= e(url('login.php')) ?>">
                <?= csrf_field() ?>
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= e($username) ?>" autocomplete="username" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required>
                </div>
                <div class="form-actions-row">
                    <label class="checkbox-field">
                        <input type="checkbox" id="show-password" aria-controls="password">
                        Show password
                    </label>
                    <a class="forgot-link" href="<?= e(url('forgot_request.php')) ?>">Forgot password?</a>
                </div>
                <button class="btn btn-block btn-teal login-submit" type="submit">Sign In</button>
                <div class="login-divider"><span>or</span></div>
                <button type="button" class="btn btn-light about-button" id="open-about">
                    <span class="about-icon" aria-hidden="true">i</span>
                    About Faith Connect
                </button>

            </form>
        </div>
    </section>

    <!-- About modal (login page) -->
    <div id="about-modal" class="modal" aria-hidden="true">
        <div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="about-title">
            <button class="modal-close" id="about-close" aria-label="Close">✕</button>
            <h2 id="about-title">About Faith Connect</h2>
            <p>Faith Connect is a church registry system maintained by Holy Cross Parish. It helps parish staff manage sacramental records, registrations, and reports in a secure and auditable way.</p>
            <p>For assistance or account issues, please contact the parish administrator.</p>
        </div>
    </div>

</main>

<script>
// About modal behaviour for the login page
(function(){
    var open = document.getElementById('open-about');
    var modal = document.getElementById('about-modal');
    var close = document.getElementById('about-close');

    if (!open || !modal) return;

    function show() {
        modal.setAttribute('aria-hidden', 'false');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        // focus the close button for accessibility
        close && close.focus();
    }
    function hide() {
        modal.setAttribute('aria-hidden', 'true');
        modal.classList.remove('show');
        document.body.style.overflow = '';
        open && open.focus();
    }

    open.addEventListener('click', function(e){ e.preventDefault(); show(); });
    close && close.addEventListener('click', function(){ hide(); });
    modal.addEventListener('click', function(e){ if (e.target === modal) hide(); });
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') hide(); });
})();

// Scroll-driven background slideshow
(function(){
    var bg = document.querySelector('.login-background');
    if (!bg) return;
    var slides = Array.from(bg.querySelectorAll('span'));
    if (slides.length < 2) return;

    var active = 0; // index of active slide
    var busy = false;
    function setActive(i){
        slides.forEach(function(s, idx){
            s.classList.toggle('active', idx === i);
        });
        active = i;
    }
    setActive(0);

    function toggleNext(dir){
        if (busy) return;
        busy = true;
        var next = active + (dir > 0 ? 1 : -1);
        if (next < 0) next = 0;
        if (next >= slides.length) next = slides.length - 1;
        if (next !== active) setActive(next);
        setTimeout(function(){ busy = false; }, 700);
    }

    var lastTouchY = null;
    window.addEventListener('wheel', function(e){
        if (Math.abs(e.deltaY) < 10) return;
        toggleNext(e.deltaY);
    }, {passive: true});

    window.addEventListener('touchstart', function(e){ lastTouchY = e.touches && e.touches[0] && e.touches[0].clientY; }, {passive: true});
    window.addEventListener('touchend', function(e){
        if (lastTouchY == null) return;
        var endY = (e.changedTouches && e.changedTouches[0] && e.changedTouches[0].clientY) || lastTouchY;
        var diff = lastTouchY - endY;
        if (Math.abs(diff) > 30) toggleNext(diff);
        lastTouchY = null;
    }, {passive: true});
})();

// Show password toggle
(function(){
    var chk = document.getElementById('show-password');
    var pwd = document.getElementById('password');
    if (!chk || !pwd) return;
    chk.addEventListener('change', function () {
        try {
            pwd.type = this.checked ? 'text' : 'password';
        } catch (e) {
            // fallback for older browsers
            var newInput = document.createElement('input');
            newInput.type = this.checked ? 'text' : 'password';
            newInput.id = pwd.id;
            newInput.name = pwd.name;
            newInput.value = pwd.value;
            newInput.className = pwd.className;
            pwd.parentNode.replaceChild(newInput, pwd);
            pwd = newInput;
        }
    });
})();
</script>
</body>
</html>
            </form>
        </div>
    </section>
</main>
</body>
</html>
