<?php
session_start();
$conn = mysqli_connect("localhost","root","","ayuda_system");
if(!$conn) die("Connection failed: ".mysqli_connect_error());

/*
// Create MSWDO/admin and barangay accounts if not exist
$users = [
    ["mswdo","Admin_123","MSWDO",null],
    ["banucal","Banucal_123","Barangay","Banucal"],
    ["bequi_walin","Bequi-Walin_123","Barangay","Bequi-Walin"],
    ["bugui","Bugui_123","Barangay","Bugui"],
    ["calungbuyan","Calungbuyan_123","Barangay","Calungbuyan"],
    ["carcarabasa","Carcarabasa_123","Barangay","Carcarabasa"],
    ["labut","Labut_123","Barangay","Labut"],
    ["poblacion_norte","PoblacionNorte_123","Barangay","Poblacion Norte (Namatting)"],
    ["poblacion_sur","PoblacionSur_123","Barangay","Poblacion Sur (Demang)"],
    ["san_vicente","SanVicente_123","Barangay","San Vicente (Kamatliwan)"],
    ["suysuyan","Suysuyan_123","Barangay","Suysuyan"],
    ["tay_ac","Tay-ac_123","Barangay","Tay-ac"]
];
*/

/*foreach($users as $u){
    $username = $u[0];
    $check = mysqli_query($conn,"SELECT id FROM users WHERE username='$username'");
    if(mysqli_num_rows($check)==0){
        $password = password_hash($u[1], PASSWORD_DEFAULT);
        $role = $u[2];
        $barangay = $u[3];
        mysqli_query($conn,"INSERT INTO users (username,password,role,barangay) VALUES ('$username','$password','$role','$barangay')");
    }
}
*/

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = $_POST['password'];

    $res = mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($res)>0){
        $user = mysqli_fetch_assoc($res);
        if(password_verify($password,$user['password'])){
            $_SESSION['username']=$user['username'];
            $_SESSION['role']=$user['role'];
            $_SESSION['barangay']=$user['barangay']; // null if MSWDO

            // Redirect based on role
            if($user['role']=='MSWDO'){
                header("Location: sampledashboard.php");
            } else {
                $brgy = urlencode($user['barangay']);
                header("Location: samplehouseholds.php?barangay=$brgy");
            }
            exit();
        } else {
            $error="Invalid username or password!";
        }
    } else {
        $error="Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login - Ayuda System</title>
<style>
body{background:#f3f4f6;font-family:'Segoe UI',sans-serif;}
.container{max-width:400px;margin:80px auto;background:white;padding:30px;border-radius:15px;box-shadow:0 10px 25px rgba(0,0,0,0.08);}
h2{text-align:center;margin-bottom:20px;}
input{width:100%;padding:12px;margin:8px 0;border-radius:6px;border:1px solid #ccc;box-sizing:border-box;}
button{width:100%;padding:12px;background:#5b8def;color:white;border:none;border-radius:6px;cursor:pointer;font-weight:600;}
.error{color:red;text-align:center;margin-bottom:10px;}
</style>
</head>
<body>
<div class="container">
<h2>Login - Ayuda System</h2>
<?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
<form method="POST">
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="login">Login</button>
</form>
</div>
</body>
</html>