<?php
require 'auth.php';
$conn = mysqli_connect("localhost","root","","ayuda_system");
if(!$conn) die("Connection failed: ".mysqli_connect_error());

// Fetch all barangays
$barangays = [];
$res = mysqli_query($conn,"SELECT DISTINCT barangay FROM households ORDER BY barangay ASC");
while($row = mysqli_fetch_assoc($res)){
    $barangays[] = $row['barangay'];
}

// Selected barangay
$selected_barangay = $_GET['barangay'] ?? ($barangays[0] ?? '');

// Sorting
$order = "score DESC";
if(isset($_GET['sort'])){
    if($_GET['sort']=="alpha") $order="household_head ASC";
    if($_GET['sort']=="priority") $order="FIELD(priority,'High','Medium','Low')";
}

// Search
$search = "";
$search_query = "";
if(isset($_GET['search']) && trim($_GET['search'])!=""){
    $search=mysqli_real_escape_string($conn,$_GET['search']);
    $search_query=" AND household_head LIKE '%$search%' ";
}

// Fetch households for selected barangay (MSWDO sees all, barangay sees own)
if($_SESSION['role']=='MSWDO'){
    $query = "SELECT * FROM households h1 WHERE barangay='$selected_barangay' 
              AND family_size BETWEEN 1 AND 99 
              AND income BETWEEN 2000 AND 100000
              $search_query
              AND id=(SELECT MAX(id) FROM households h2 WHERE h2.household_head=h1.household_head)
              ORDER BY $order
              LIMIT 50";
} else {
    $user_brgy = $_SESSION['barangay'];
    $query = "SELECT * FROM households h1 WHERE barangay='$user_brgy' 
              AND family_size BETWEEN 1 AND 99 
              AND income BETWEEN 2000 AND 100000
              $search_query
              AND id=(SELECT MAX(id) FROM households h2 WHERE h2.household_head=h1.household_head)
              ORDER BY $order
              LIMIT 50";
}

$result = mysqli_query($conn,$query);

// Fetch barangay accounts for MSWDO management
if($_SESSION['role']=='MSWDO'){
    $accounts = mysqli_query($conn,"SELECT * FROM users WHERE role='Barangay' ORDER BY barangay ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard - Ayuda System</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{background:#f3f4f6;}
.navbar{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:#e5e7eb;}
.logo{font-size:16px;font-weight:600;}
.navbar nav a{text-decoration:none;margin-left:20px;color:#333;font-size:14px;}
.container{max-width:1200px;margin:40px auto;padding:0 20px;}
.card{background:white;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.08);padding:20px;margin-bottom:30px;}
.card h3{margin-bottom:15px;color:#2e2b5f;}
select, input, button{padding:8px 12px;margin-right:10px;border-radius:6px;border:1px solid #ccc;}
button{cursor:pointer;}
table{width:100%;border-collapse:collapse;background:white;border-radius:12px;overflow:hidden;margin-top:15px;box-shadow:0 10px 25px rgba(0,0,0,0.08);}
th,td{padding:12px;text-align:left;}
th{background:#5b8def;color:white;}
tr:nth-child(even){background:#f9fafb;}
.label{padding:4px 10px;border-radius:20px;color:white;font-size:12px;font-weight:600;}
.label-red{background:#ef4444;}
.label-orange{background:#f59e0b;}
.label-yellow{background:#eab308;}
.edit-btn{padding:4px 8px;background:#5b8def;color:white;border:none;border-radius:6px;cursor:pointer;}
.reset-btn{padding:4px 8px;background:#f59e0b;color:white;border:none;border-radius:6px;cursor:pointer;}
.delete-btn{padding:4px 8px;background:#ef4444;color:white;border:none;border-radius:6px;cursor:pointer;}
.add-btn{padding:6px 12px;background:#22c55e;color:white;border:none;border-radius:6px;cursor:pointer;}
</style>
</head>
<body>

<div class="navbar">
<div class="logo">AYUDA DASHBOARD</div>
<nav>
<a href="logout.php">Logout</a>
</nav>
</div>

<div class="container">

<div class="card">
<h3>Barangay Selection & Household Rankings</h3>
<form method="GET">
<?php if($_SESSION['role']=='MSWDO'): ?>
<select name="barangay" onchange="this.form.submit()">
<?php foreach($barangays as $b): ?>
<option value="<?php echo htmlspecialchars($b); ?>" <?php if($b==$selected_barangay) echo "selected"; ?>>
<?php echo htmlspecialchars($b); ?>
</option>
<?php endforeach; ?>
</select>
<?php endif; ?>
<select name="sort" onchange="this.form.submit()">
<option value="score" <?php if(!isset($_GET['sort'])) echo "selected"; ?>>Sort by Score</option>
<option value="alpha" <?php if(isset($_GET['sort']) && $_GET['sort']=="alpha") echo "selected"; ?>>Sort A-Z</option>
<option value="priority" <?php if(isset($_GET['sort']) && $_GET['sort']=="priority") echo "selected"; ?>>Sort by Priority</option>
</select>
<input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
<button type="submit">Search</button>
<?php if($_SESSION['role']=='MSWDO'): ?>
<a href="add_household.php" class="add-btn">+ Add Household</a>
<?php endif; ?>
</form>

<table>
<tr>
<th>Rank</th>
<th>Household Head</th>
<th>Family Size</th>
<th>Income</th>
<th>Disaster Impact</th>
<th>Income Source</th>
<th>Assistance History</th>
<th>Score</th>
<th>Priority</th>
<th>Action</th>
</tr>

<?php
$rank=1;
while($row=mysqli_fetch_assoc($result)){
    $labelClass=($row['priority']=="High")?"label-red":(($row['priority']=="Medium")?"label-orange":"label-yellow");
    echo "<tr>
    <td>".$rank++."</td>
    <td>".htmlspecialchars($row['household_head'])."</td>
    <td>".$row['family_size']."</td>
    <td>₱".number_format($row['income'])."</td>
    <td>".$row['disaster_impact']."</td>
    <td>".$row['income_source']."</td>
    <td>".$row['assistance_history']."</td>
    <td>".round($row['score'],2)."%</td>
    <td><span class='label $labelClass'>".$row['priority']."</span></td>
    <td>";
    if($_SESSION['role']=='MSWDO' || $_SESSION['barangay']==$row['barangay']){
        echo "<a href='edit_household.php?id=".$row['id']."' class='edit-btn'>Edit</a> ";
        echo "<form method='POST' action='delete_household.php' style='display:inline;'>
              <input type='hidden' name='id' value='".$row['id']."'>
              <button type='submit' class='delete-btn' onclick=\"return confirm('Delete this household?');\">Delete</button>
              </form>";
    }
    echo "</td></tr>";
}
?>
</table>
</div>

<?php if($_SESSION['role']=='MSWDO'): ?>
<div class="card">
<h3>Manage Barangay Accounts</h3>
<table>
<tr>
<th>Barangay</th>
<th>Username</th>
<th>Password Reset</th>
</tr>
<?php while($acc=mysqli_fetch_assoc($accounts)): ?>
<tr>
<td><?php echo htmlspecialchars($acc['barangay']); ?></td>
<td><?php echo htmlspecialchars($acc['username']); ?></td>
<td>
<form method="POST" action="reset_password.php" style="display:inline;">
<input type="hidden" name="user_id" value="<?php echo $acc['id']; ?>">
<input type="text" name="new_password" placeholder="New Password" value="<?php echo htmlspecialchars($acc['barangay']."_123"); ?>">
<button type="submit" class="reset-btn">Reset</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>
<?php endif; ?>

</div>
</body>
</html>
