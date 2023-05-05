<?php
// Khởi tạo session
session_start();
// Import file Bridge.php vào
require_once "./mvc/bridge.php";
require_once './mvc/core/config.php';
// Khởi tạo App
$myApp = new App();
?>