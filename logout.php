<?php
require_once 'config/auth.php';

// Perform logout
$result = $auth->logout();

// Redirect to login page
header('Location: login.php?message=' . urlencode($result['message']));
exit();
?>
