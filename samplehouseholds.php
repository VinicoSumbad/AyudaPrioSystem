<?php
require 'auth.php';
$conn = mysqli_connect("localhost", "root", "", "ayuda_system");
if(!$conn) die("Connection failed: " . mysqli_connect_error());

// Determine barangay access
if($_SESSION['role']=='MSWDO'){
    // MSWDO can view any barangay via GET parameter
    $barangay = $_GET['barangay'] ?? "Calungbuyan";
} else {
    // Barangay can only view their own barangay
    $barangay = $_SESSION['barangay'];
}

// Sorting
$order = "score DESC";
if(isset($_GET['sort'])){
    if($_GET['sort']=="alpha") $order="household_head ASC";
    if($_GET['sort']=="priority") $order="FIELD(priority,'High','Medium','Low')";
}

// Search
$search="";
$search_query="";
if(isset($_GET['search']) && trim($_GET['search'])!=""){
    $search=mysqli_real_escape_string($conn,$_GET['search']);
    $search_query=" AND household_head LIKE '%$search%' ";
}

// Fetch households
$result=mysqli_query($conn,"
SELECT * FROM households h1
WHERE barangay='$barangay'
AND family_size BETWEEN 1 AND 99
AND income BETWEEN 2000 AND 100000
$search_query
AND id=(SELECT MAX(id) FROM households h2 WHERE h2.household_head=h1.household_head)
ORDER BY $order
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Household Rankings - <?php echo htmlspecialchars($barangay); ?></title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{background:#f3f4f6;}
.navbar{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:#e5e7eb;}
.logo{font-size:14px;font-weight:500;}
.navbar nav a{text-decoration:none;margin-left:20px;color:#333;font-size:14px;}
.container{max-width:1200px;margin:50px auto;padding:0 20px;}
.add-btn,.sort-btn,.search-btn{display:inline-block;padding:10px 16px;border:none;border-radius:8px;color:white;text-decoration:none;font-size:14px;}
.add-btn{background:#22c55e;}
.sort-btn{background:#3b82f6;margin-left:10px;}
.search-btn{background:#f59e0b;margin-left:10px;}
.search-input{padding:10px 12px;border-radius:6px;border:1px solid #ccc;font-size:14px;width:200px;margin-left:10px;}
table{width:100%;border-collapse:collapse;background:white;border-radius:12px;overflow:hidden;margin-top:20px;box-shadow:0 10px 25px rgba(0,0,0,0.08);}
th,td{padding:14px 12px;text-align:left;}
th{background:#5b8def;color:white;}
tr:nth-child(even){background:#f9fafb;}
.label{padding:5px 12px;border-radius:20px;color:white;font-size:12px;font-weight:600;}
.label-red{background:#ef4444;}
.label-orange{background:#f59e0b;}
.label-yellow{background:#eab308;}
.edit-btn{padding:5px 10px;background:#5b8def;color:white;border:none;border-radius:6px;cursor:pointer;}
.top-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:15px;}
.left-side{display:flex;align-items:center;gap:20px;}
</style>
</head>
<body>

<div class="navbar">
<div class="logo">AYUDA PRIORITIZATION SYSTEM</div>
<nav>
<a href="<?php echo ($_SESSION['role']=='MSWDO')?'sampledashboard.php':'logout.php'; ?>"> <?php echo ($_SESSION['role']=='MSWDO')?'Dashboard':'Logout'; ?> </a>
</nav>
</div>

<div class="container">
<div class="top-bar">
<div class="left-side">
<h2 style="color:#2e2b5f;"><?php echo htmlspecialchars($barangay); ?></h2>

<?php if($_SESSION['role']=='Barangay'): ?>
<a href="add_household.php" class="add-btn">+ Add Household</a>
<?php endif; ?>

<a href="?barangay=<?php echo urlencode($barangay); ?>&sort=alpha" class="sort-btn">Sort A-Z</a>
<a href="?barangay=<?php echo urlencode($barangay); ?>&sort=priority" class="sort-btn">Sort by Priority</a>
</div>

<form method="GET">
<input type="hidden" name="barangay" value="<?php echo htmlspecialchars($barangay); ?>">
<input type="text" name="search" class="search-input" placeholder="Search by name..." value="<?php echo htmlspecialchars($search); ?>">
<button type="submit" class="search-btn">Search</button>
</form>
</div>

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
<?php if($_SESSION['role']=='Barangay'): ?><th>Action</th><?php endif; ?>
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
    <td><span class='label $labelClass'>".$row['priority']."</span></td>";
    if($_SESSION['role']=='Barangay'){
        echo "<td><a href='edit_household.php?id=".$row['id']."'><button class='edit-btn'>Edit</button></a></td>";
    }
    echo "</tr>";
}
?>
</table>
</div>
</body>
</html>
