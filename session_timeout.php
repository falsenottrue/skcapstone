<?php

// Set timeout duration (in seconds)
$timeout_duration = 900; // 15 minutes

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: dashboard.php?session=expired");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity
?>
