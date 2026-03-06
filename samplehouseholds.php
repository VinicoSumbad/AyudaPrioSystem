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
<link rel="stylesheet" href="css/samplehouseholds.css">
</head>
<body>

<div class="navbar">
<div class="logo">AUTOMATED AYUDA PRIORITIZATION SYSTEM</div>
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
