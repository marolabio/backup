<?php 
	include_once 'includes/dbh.inc.php';
	session_start();
	if(isset($_POST['cust_id'])){
		
		$cust_id = $_POST['cust_id'];
		mysqli_query($conn , "update customers set cust_status = 'refund' where cust_id = '$cust_id'");
		mysqli_query($conn , "update reservation set res_status = 'refund' where cust_id = '$cust_id'");
		mysqli_query($conn , "update billing set bill_status = 'refund' where cust_id = '$cust_id'");


		header("Location: ../admin/confirmed.php?refund=success");
		exit();	

	}else{
		header("Location: ../admin/confirmed.php?refund=error");
		exit();	

	}


 ?>

