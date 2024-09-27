<?php
session_start();
include("db/db.php");
$type=$_POST['type'];
$type=base64_decode($type);
if($type=="login"){
	$username=trim(base64_decode($_POST['username']));
	$password=trim(base64_decode($_POST['password'])); 
	$checkcredentials=$conn->prepare("SELECT * FROM Staff WHERE username='$username' AND password='$password' AND status='1'");
	$checkcredentials->execute();
	$checkrowcount=$checkcredentials->rowCount();
	if($checkrowcount==1){
		$fetchrow=$checkcredentials->fetch(PDO::FETCH_ASSOC);
		$response=['msg'=>'Login success!','status'=>'success'];
		$_SESSION['staffid']=$fetchrow['id'];
		$_SESSION['user']=$fetchrow['firstname']." ".$fetchrow['lastname'];
	}else{
		$response=['msg'=>'invalid credentials!','status'=>'error'];
	}
	echo json_encode($response);
}
?>