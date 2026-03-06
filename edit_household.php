<?php
require 'auth.php';
$conn = mysqli_connect("localhost","root","","ayuda_system");
if(!$conn) die("Connection failed: ".mysqli_connect_error());

$id = (int)$_GET['id'];
$res = mysqli_query($conn,"SELECT * FROM households WHERE id=$id");
$data = mysqli_fetch_assoc($res);
if(!$data) die("Household not found");

// RBAC check
if($_SESSION['role']!='MSWDO' && $_SESSION['barangay']!=$data['barangay']) die("Access denied!");

// Fetch all barangays for MSWDO
if($_SESSION['role']=='MSWDO'){
    $barangays=[];
    $res2=mysqli_query($conn,"SELECT DISTINCT barangay FROM households ORDER BY barangay ASC");
    while($row=mysqli_fetch_assoc($res2)) $barangays[]=$row['barangay'];
}

// Score calculation function
function computeScore($income,$size,$disaster,$source,$history){
    $income_score = ($income<=5000)?100:(($income<=10000)?70:(($income<=15000)?40:10));
    $size_score = ($size>=7)?100:(($size>=5)?70:(($size>=3)?40:10));
    $disaster_score = $disaster*33.33;
    $source_score = ($source=="Unemployed")?100:(($source=="Seasonal")?60:20);
    $history_score = ($history>=12)?100:(($history>=6)?60:20);
    return round(0.3*$income_score + 0.2*$size_score + 0.25*$disaster_score + 0.1*$source_score + 0.15*$history_score,2);
}

// Handle update
if(isset($_POST['update'])){
    $name = trim(mysqli_real_escape_string($conn,$_POST['name']));
    $size = (int)$_POST['size'];
    $income = (float)$_POST['income'];
    $disaster = (int)$_POST['disaster'];
    $source = mysqli_real_escape_string($conn,$_POST['source']);
    $history = (int)$_POST['history'];
    $barangay = $_POST['barangay'];

    if($_SESSION['role']!='MSWDO' && $_SESSION['barangay']!=$barangay) die("Access denied!");

    $errors=[];
    if($name=="") $errors[]="Name cannot be empty.";
    if($size<0 || $size>99) $errors[]="Family size must be 0-99.";
    if($income<=2000 || $income>=100000) $errors[]="Income must be 2000-100000.";
    if($history<0 || $history>99) $errors[]="Months since last ayuda must be 0-99.";

    $check=mysqli_query($conn,"SELECT id FROM households WHERE LOWER(household_head)=LOWER('$name') AND id<>$id");
    if(mysqli_num_rows($check)>0) $errors[]="This household head already exists.";

    if(count($errors)>0){
        echo "<script>alert('".implode('\\n',$errors)."');window.history.back();</script>"; 
        exit();
    }

    $score = computeScore($income,$size,$disaster,$source,$history);
    $priority = ($score>=70)?"High":(($score>=50)?"Medium":"Low");

    mysqli_query($conn,"UPDATE households SET household_head='$name', family_size='$size', income='$income',
                      disaster_impact='$disaster', income_source='$source', assistance_history='$history',
                      score='$score', priority='$priority', barangay='$barangay' WHERE id=$id");

    header("Location: sampledashboard.php?barangay=" . urlencode($barangay)); 
    exit();
}

// Handle delete
if(isset($_POST['delete']) && $_SESSION['role']=='MSWDO'){
    mysqli_query($conn,"DELETE FROM households WHERE id=$id");
    header("Location: sampledashboard.php?barangay=" . urlencode($data['barangay'])); 
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Household</title>
<link rel="stylesheet" href="css/edit_household.css">
</head>
<body>
<div class="container">
<h2>Edit Household</h2>
<form method="POST">
<input type="text" name="name" value="<?php echo htmlspecialchars($data['household_head']); ?>" required>
<input type="number" name="size" value="<?php echo $data['family_size']; ?>" required>
<input type="number" name="income" value="<?php echo $data['income']; ?>" required>
<select name="disaster" required>
<option value="0" <?php if($data['disaster_impact']=="0") echo "selected"; ?>>None</option>
<option value="1" <?php if($data['disaster_impact']=="1") echo "selected"; ?>>Minimal</option>
<option value="2" <?php if($data['disaster_impact']=="2") echo "selected"; ?>>Partial</option>
<option value="3" <?php if($data['disaster_impact']=="3") echo "selected"; ?>>Total</option>
</select>
<select name="source" required>
<option value="Stable" <?php if($data['income_source']=="Stable") echo "selected"; ?>>Stable</option>
<option value="Seasonal" <?php if($data['income_source']=="Seasonal") echo "selected"; ?>>Seasonal</option>
<option value="Unemployed" <?php if($data['income_source']=="Unemployed") echo "selected"; ?>>Unemployed</option>
</select>
<input type="number" name="history" value="<?php echo $data['assistance_history']; ?>" required>

<?php if($_SESSION['role']=='MSWDO'): ?>
<select name="barangay" required>
<?php foreach($barangays as $b): ?>
<option value="<?php echo htmlspecialchars($b); ?>" <?php if($data['barangay']==$b) echo "selected"; ?>>
<?php echo htmlspecialchars($b); ?>
</option>
<?php endforeach; ?>
</select>
<?php else: ?>
<input type="hidden" name="barangay" value="<?php echo $_SESSION['barangay']; ?>">
<?php endif; ?>

<div class="button-group">
    <button type="submit" name="update" class="edit-btn">Update</button>
    <?php if($_SESSION['role']=='MSWDO'): ?>
    <button type="submit" name="delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete this household?');">Delete</button>
    <?php endif; ?>a

</form>
</div>
</body>
</html>