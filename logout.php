<?php
/** Step 6 of the program flow: admin logout, back to the login screen. */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

logout();
set_flash('success', 'You have been signed out.');
redirect('login.php');
