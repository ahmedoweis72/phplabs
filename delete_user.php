<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $conn = connectDB();
    $id = (int)$_GET['id'];
    
    // Delete user's profile picture if exists
    $sql = "SELECT profile_picture FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    
    if ($user && $user['profile_picture'] && file_exists($user['profile_picture'])) {
        unlink($user['profile_picture']);
    }
    
    // Delete user from database
    $sql = "DELETE FROM users WHERE id = $id";
    mysqli_query($conn, $sql);
    
    mysqli_close($conn);
}

header('Location: tabledata.php');
exit;