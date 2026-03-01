<?php
require 'auth.php';
if($_SESSION['role']!='MSWDO') die("Access denied!");

$conn = mysqli_connect("localhost","root","","ayuda_system");

if(isset($_POST['reset'])){
    $user_id = (int)$_POST['user_id'];
    $new_pass = trim($_POST['new_pass']);
    $hash = password_hash($new_pass, PASSWORD_DEFAULT);
    mysqli_query($conn,"UPDATE users SET password='$hash' WHERE id=$user_id");
    echo "<script>alert('Password updated!');</script>";
}

$brgy_users = mysqli_query($conn,"SELECT * FROM users WHERE role='Barangay'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Barangay Passwords</title>
</head>
<body>
<h2>Reset Barangay Passwords</h2>
<table border="1" cellpadding="10">
<tr><th>Barangay</th><th>Username</th><th>Reset Password</th></tr>
<?php while($row=mysqli_fetch_assoc($brgy_users)): ?>
<tr>
<td><?php echo $row['barangay']; ?></td>
<td><?php echo $row['username']; ?></td>
<td>
<form method="POST">
<input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
<input type="text" name="new_pass" placeholder="New Password" required>
<button type="submit" name="reset">Reset</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>
<a href="sampledashboard.php">Back to Dashboard</a>
</body>
</html>