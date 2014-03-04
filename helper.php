<?php
/**
 * Some helper functions and other useful things
 */

defined('ROOT_PATH') or define('ROOT_PATH', __DIR__ . '/');

if (false === isSessionStarted()) {
    session_start();
}

function redirectTo($to)
{
    if (headers_sent()) {
        die("Error redirecting: this should probobly be handled!");
    }

    exit(header("Location: {$to}"));
}

function d($str)
{
    echo "<pre>";
    print_r($str);
    die;
}

function isSessionStarted()
{
    return session_status() === PHP_SESSION_ACTIVE;
}

function _safe($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
