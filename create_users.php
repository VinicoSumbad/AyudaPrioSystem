<?php
$conn = mysqli_connect("localhost","root","","ayuda_system");
if(!$conn) die("Connection failed: ".mysqli_connect_error());

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

foreach($users as $u){
    $username = $u[0];
    $password = password_hash($u[1], PASSWORD_DEFAULT);
    $role = $u[2];
    $barangay = $u[3];
    
    mysqli_query($conn,"INSERT INTO users (username,password,role,barangay) VALUES ('$username','$password','$role','$barangay')");
}

echo "Users created successfully!";
?>