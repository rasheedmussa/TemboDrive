<?php
// logout.php - Logout functionality
require_once 'config.php';

session_destroy();
header('Location: login.php');
exit;
?>