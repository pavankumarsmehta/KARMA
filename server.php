<?php
if ($_SERVER['REQUEST_URI'] == '/pnkpanel/' || $_SERVER['REQUEST_URI'] == '/pnkpanel') {
    // Redirect to the login page
    header('Location: http://127.0.0.1:8000/pnkpanel/login', true, 301);
    exit;
}


/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */
//echo $_SERVER['REQUEST_URI'], PHP_URL_PATH; exit;
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
//echo __DIR__.'/public'.$uri; exit;
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    echo "Please Contact QD Team.";
    return false;
}

require_once __DIR__.'/public/index.php';
