<?php
session_start();
session_unset();
session_destroy();

header("Location: connexion1.php");
exit();
?>
