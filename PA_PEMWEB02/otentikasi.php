<?php
session_start();

function sudah_login() {
    return isset($_SESSION['user_id']);
}

function adalah_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>