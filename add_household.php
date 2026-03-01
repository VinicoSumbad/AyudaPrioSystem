<?php
require 'auth.php';
$conn = mysqli_connect("localhost","root","","ayuda_system");
if(!$conn) die("Connection failed: ".mysqli_connect_error());

// Fetch all barangays for MSWDO
if($_SESSION['role']=='MSWDO'){
    $barangays = [];
    $res = mysqli_query($conn,"SELECT DISTINCT barangay FROM households ORDER BY barangay ASC");
    while($row=mysqli_fetch_assoc($res)) $barangays[] = $row['barangay'];
}

function computeScore($income,$size,$disaster,$source,$history){
    $income_score = ($income<=5000)?100:(($income<=10000)?70:(($income<=15000)?40:10));
    $size_score = ($size>=7)?100:(($size>=5)?70:(($size>=3)?40:10));
    $disaster_score = $disaster*33.33;
    $source_score = ($source=="Unemployed")?100:(($source=="Seasonal")?60:20);
    $history_score = ($history>=12)?100:(($history>=6)?60:20);
    return round(0.3*$income_score + 0.2*$size_score + 0.25*$disaster_score + 0.1*$source_score + 0.15*$history_score,2);
}

if(isset($_POST['add'])){
    $name = trim(mysqli_real_escape_string($conn,$_POST['name']));
    $size = (int)$_POST['size'];
    $income = (float)$_POST['income'];
    $disaster = (int)$_POST['disaster'];
    $source = mysqli_real_escape_string($conn,$_POST['source']);
    $history = (int)$_POST['history'];
    $barangay = $_POST['barangay'];

    // RBAC check
    if($_SESSION['role']!='MSWDO' && $_SESSION['barangay']!=$barangay) die("Access denied!");

    $errors=[];
    if($name=="") $errors[]="Name cannot be empty.";
    if($size<0 || $size>99) $errors[]="Family size must be between 0 and 99.";
    if($income<=2000 || $income>=100000) $errors[]="Income must be >2000 and <100000";
    if($history<0 || $history>99) $errors[]="Months since last ayuda must be 0-99.";

    $check=mysqli_query($conn,"SELECT id FROM households WHERE LOWER(household_head)=LOWER('$name')");
    if(mysqli_num_rows($check)>0) $errors[]="This household head already exists.";

    if(count($errors)>0){
        echo "<script>alert('".implode('\\n',$errors)."');window.history.back();</script>"; exit();
    }

    $score = computeScore($income,$size,$disaster,$source,$history);
    $priority = ($score>=70)?"High":(($score>=50)?"Medium":"Low");

    mysqli_query($conn,"INSERT INTO households (household_head,family_size,income,disaster_impact,income_source,assistance_history,score,priority,barangay)
                      VALUES('$name','$size','$income','$disaster','$source','$history','$score','$priority','$barangay')");
    header("Location: sampledashboard.php"); exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Household</title>
<style>
body{background:#f3f4f6;font-family:'Segoe UI';margin:0;}
.container{max-width:500px;margin:50px auto;background:white;padding:30px;border-radius:15px;box-shadow:0 10px 25px rgba(0,0,0,0.08);}
input,select,button{width:100%;padding:10px;margin:8px 0;border-radius:6px;border:1px solid #ccc;}
button{background:#22c55e;color:white;border:none;cursor:pointer;font-weight:600;}
</style>
</head>
<body>
<div class="container">
<h2>Add Household</h2>
<form method="POST">
<input type="text" name="name" placeholder="Household Head" required>
<input type="number" name="size" placeholder="Family Size" required>
<input type="number" name="income" placeholder="Monthly Income" required>
<select name="disaster" required>
<option value="">Disaster Impact</option>
<option value="0">None</option>
<option value="1">Minimal</option>
<option value="2">Partial</option>
<option value="3">Total</option>
</select>
<select name="source" required>
<option value="">Income Source</option>
<option value="Stable">Stable</option>
<option value="Seasonal">Seasonal</option>
<option value="Unemployed">Unemployed</option>
</select>
<input type="number" name="history" placeholder="Months since last ayuda" required>
<?php if($_SESSION['role']=='MSWDO'): ?>
<select name="barangay" required>
<?php foreach($barangays as $b): ?>
<option value="<?php echo htmlspecialchars($b); ?>"><?php echo htmlspecialchars($b); ?></option>
<?php endforeach; ?>
</select>
<?php else: ?>
<input type="hidden" name="barangay" value="<?php echo $_SESSION['barangay']; ?>">
<?php endif; ?>
<button type="submit" name="add">Add Household</button>
</form>
</div>
</body>
</html>
