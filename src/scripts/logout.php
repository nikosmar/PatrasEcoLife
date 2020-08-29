<?php 
    $_SESSION['loggedin'] = false;

    session_unset();
    session_destroy();

    header('Location: ..');
?>
