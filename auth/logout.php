<?php

require_once __DIR__ . '/../includes/functions.php';

clear_login_cookies();

header('Location: login.php');
exit;

?>