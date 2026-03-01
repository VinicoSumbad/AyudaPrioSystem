<?php
require 'auth.php';
if($_SESSION['role']!='MSWDO') die("Access denied!");
$conn = mysqli_connect("localhost","root","","ayuda_system");
if(!$conn) die("Connection failed: ".mysqli_connect_error());

if(isset($_POST['user_id'], $_POST['new_password'])){
    $id = (int)$_POST['user_id'];
    $pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    mysqli_query($conn,"UPDATE users SET password='$pass' WHERE id=$id");
    echo "<script>alert('Password successfully reset!');window.location.href='sampledashboard.php';</script>";
}