<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();


// Just respond with a 200 OK
http_response_code(200);
?>
