<?php
session_start();
session_destroy();
header("Location: /event-booking");
exit();
?>
