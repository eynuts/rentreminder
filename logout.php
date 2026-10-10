<?php
// ============================================================
// LOGOUT — Ramadia Rent Reminder System
// Destroys the session and sends the user back to login.
// ============================================================

session_start();
session_unset();
session_destroy();

header('Location: index.php');
exit;
