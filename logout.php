<?php

require_once('function.php');

unset($_SESSION['agriculture_user_login']);

header('Location: login.php');

?>