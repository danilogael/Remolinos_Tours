<?php
// admin/logout.php
session_start();
session_unset();
session_destroy();
header("Location: ../Login_APP/login.php");
exit();
