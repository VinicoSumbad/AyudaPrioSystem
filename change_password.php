<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['role']!='MSWDO'){
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost","root","","ayuda_system");
if(!$conn) die("Connection failed: ".mysqli_connect_error());

if(isset($_POST['change'])){
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $new_pass = $_POST['new_password'];
    $hash = password_hash($new_pass,PASSWORD_DEFAULT);

    mysqli_query($conn,"UPDATE users SET password='$hash' WHERE username='$username'");
    $success = "Password successfully changed!";
}

// Fetch all users
$users_res = mysqli_query($conn,"SELECT * FROM users WHERE role='Barangay' OR role='MSWDO'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Change Password - MSWDO</title>
<style>
body{background:#f3f4f6;font-family:'Segoe UI',sans-serif;}
.container{max-width:500px;margin:50px auto;background:white;padding:30px;border-radius:15px;box-shadow:0 10px 25px rgba(0,0,0,0.08);}
h2{text-align:center;margin-bottom:20px;}
input, select{width:100%;padding:12px;margin:8px 0;border-radius:6px;border:1px solid #ccc;box-sizing:border-box;}
button{width:100%;padding:12px;background:#22c55e;color:white;border:none;border-radius:6px;cursor:pointer;font-weight:600;}
.success{color:green;text-align:center;margin-bottom:10px;}
</style>
</head>
<body>
<div class="container">
<h2>Change User Password</h2>
<?php if(isset($success)) echo "<div class='success'>$success</div>"; ?>
<form method="POST">
<select name="username" required>
<option value="">Select User</option>
<?php while($u = mysqli_fetch_assoc($users_res)){
    echo "<option value='".$u['username']."'>".$u['username']." (".$u['role'].")</option>";
} ?>
</select>
<input type="password" name="new_password" placeholder="New Password" required>
<button type="submit" name="change">Change Password</button>
</form>
</div>
</body>
</html>