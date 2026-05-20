<?php

require_once __DIR__ . '/../aunth/functions.php';

clear_login_cookies();

header('Location: login.php');
exit;

?>