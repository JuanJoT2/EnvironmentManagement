<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = rtrim($protocol . $host . $scriptName, '/');

if (!defined('BASE_URL')) {
    define('BASE_URL', $baseUrl . '/');
}
?>
