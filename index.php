<?php
/** Public homepage for Faith Connect. */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

$primaryUrl = is_logged_in() ? url('dashboard.php') : url('login.php');
$primaryLabel = is_logged_in() ? 'Open dashboard' : 'Staff sign in';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Faith Connect, the secure church registry system of Holy Cross Parish in Carigara, Leyte.">
    <title><?= e(APP_NAME) ?> · <?= e(APP_PARISH) ?></title>
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>">
</head>
<body class="home-page">
<header class="home-header">
    <a class="home-brand" href="<?= e(url('index.php')) ?>" aria-label="Faith Connect home">
        <img src="<?= e(url('assets/img/parish-logo.jpg')) ?>" alt="" width="48" height="48">
        <span><strong><?= e(APP_NAME) ?></strong><small><?= e(APP_PARISH) ?></small></span>
    </a>
    <a class="home-signin" href="<?= e($primaryUrl) ?>"><?= e($primaryLabel) ?> <span aria-hidden="true">→</span></a>
</header>

<main>
    <section class="home-hero" aria-labelledby="hero-heading">
        <div class="home-hero-image" role="img" aria-label="Holy Cross Parish church in Carigara, Leyte"></div>
        <div class="home-hero-content">
            <p class="eyebrow">Serving the parish since 1595</p>
            <h1 id="hero-heading">Faith remembered.<br>Records preserved.</h1>
            <p class="hero-copy">A secure digital home for the sacramental history of Holy Cross Parish—helping authorized parish staff care for every baptism, confirmation, marriage, and burial record.</p>
            <a class="home-primary" href="<?= e($primaryUrl) ?>"><?= e($primaryLabel) ?> <span aria-hidden="true">→</span></a>
        </div>
    </section>

    <section class="home-purpose" aria-labelledby="purpose-heading">
        <div>
            <p class="eyebrow">Our purpose</p>
            <h2 id="purpose-heading">Honoring every chapter of parish life</h2>
        </div>
        <div class="purpose-copy">
            <p><?= e(APP_NAME) ?> helps the parish office keep sacred records organized, searchable, and protected for the generations who will need them.</p>
            <p>Access is reserved for authorized personnel. Parishioners requesting certificates or record assistance are welcome to visit the parish office during office hours.</p>
        </div>
    </section>

    <section class="record-types" aria-label="Records maintained by Faith Connect">
        <article><span aria-hidden="true">01</span><h3>Baptism</h3><p>Beginning the journey of faith.</p></article>
        <article><span aria-hidden="true">02</span><h3>Confirmation</h3><p>Strengthened in the Holy Spirit.</p></article>
        <article><span aria-hidden="true">03</span><h3>Marriage</h3><p>Two lives joined in covenant.</p></article>
        <article><span aria-hidden="true">04</span><h3>Burial</h3><p>Remembered in hope and prayer.</p></article>
    </section>
</main>

<footer class="home-footer">
    <p><strong><?= e(APP_PARISH) ?></strong><br>Archdiocese of Palo</p>
    <p><?= e(APP_NAME) ?> · <?= e(APP_SUBTITLE) ?></p>
</footer>
</body>
</html>
