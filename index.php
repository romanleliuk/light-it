<?php
ini_set('display_errors', 1);
session_start();
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL & ~E_NOTICE);

require_once 'application/bootstrap.php';
