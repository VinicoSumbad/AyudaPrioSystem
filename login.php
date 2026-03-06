<?php
session_start();
$conn = mysqli_connect("localhost","root","","ayuda_system");
if(!$conn) die("Connection failed: ".mysqli_connect_error());

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = $_POST['password'];

    $res = mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($res)>0){
        $user = mysqli_fetch_assoc($res);
        if(password_verify($password,$user['password'])){
            $_SESSION['username']=$user['username'];
            $_SESSION['role']=$user['role'];
            $_SESSION['barangay']=$user['barangay'];

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

<!-- Landing Page CSS -->
<link rel="stylesheet" href="css/landingpage.css">

<link rel="stylesheet" href="css/login.css">
</head>

<body>

<!-- NAVBAR (copied from landing page) -->
<header class="navbar">
  <div class="logo">AUTOMATED AYUDA PRIORITIZATION SYSTEM</div>
  <nav>
    <a href="about.php">About</a>
    <a href="login.php">Log in</a>
  </nav>
</header>

<!-- LOGIN FORM -->
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