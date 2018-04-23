<?php
 
 if($_SERVER['REQUEST_METHOD']=='GET'){
 $id = $_GET['idImg'];
 $sql = "select * from image where idImg = '$id'";
 require_once('dbConnect.php');
 
 $r = mysqli_query($con,$sql);
 
 $result = mysqli_fetch_array($r);
 
 header('content-type: image/jpeg');
 
 echo base64_decode($result['image']);
 
 mysqli_close($con);
 
 }else{
 echo "Error";
 }