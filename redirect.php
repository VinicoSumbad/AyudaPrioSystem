<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

if($_SESSION['role']=='MSWDO'){
    header("Location: sampledashboard.php");
    exit();
} else if($_SESSION['role']=='Barangay'){
    header("Location: samplehouseholds.php?barangay=".urlencode($_SESSION['barangay']));
    exit();
} else {
    echo "Unauthorized access!";
    exit();
}
?>