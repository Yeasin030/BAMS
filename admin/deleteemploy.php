<?php 
session_start();
include_once 'assets/db/bdconfig.php';
$employee_id = $_GET['id'];

$conn->query("DELETE FROM employees 
WHERE employee_id='$employee_id'");

if($conn->affected_rows){
    header("Location: ../admin/employlist.php");

}

?>