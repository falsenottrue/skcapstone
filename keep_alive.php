<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
$_SESSION['LAST_ACTIVITY'] = time(); // refresh session activity
?>
