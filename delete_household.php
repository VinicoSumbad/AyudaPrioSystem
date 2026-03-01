<?php
require 'auth.php';
if(!isset($_SESSION['username'])) header("Location: login.php");

// Only MSWDO or the barangay of the household can delete
$conn = mysqli_connect("localhost","root","","ayuda_system");
if(!$conn) die("Connection failed: ".mysqli_connect_error());

if(isset($_POST['id'])){
    $id = (int)$_POST['id'];

    // Get the household info first
    $res = mysqli_query($conn,"SELECT barangay FROM households WHERE id=$id");
    if(mysqli_num_rows($res) > 0){
        $data = mysqli_fetch_assoc($res);
        $house_barangay = $data['barangay'];

        if($_SESSION['role']=='MSWDO' || $_SESSION['barangay']==$house_barangay){
            mysqli_query($conn,"DELETE FROM households WHERE id=$id");
            $redirect = "sampledashboard.php?barangay=".urlencode($house_barangay);
            echo "<script>alert('Household deleted!');window.location.href='$redirect';</script>";
            exit();
        } else {
            die("Access denied.");
        }
    } else {
        die("Household not found.");
    }
} else {
    die("Invalid request.");
}
?>