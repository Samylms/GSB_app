<?php
 	require_once 'DbOperation.php';
	

 $db = new DbOperation(); 
 
 $visiteurs = $db->getAllVisiteur();
 
 $response = array(); 
 
 $response['error'] = false; 
 $response['visiteurs'] = array(); 
 
 while($visiteur = $visiteurs->fetch_assoc()){
 $temp = array();
 $temp['numVis']=$visiteur['numVis'];
 $temp['email']=$visiteur['email'];
 $temp['token']=$visiteur['token'];
 array_push($response['visiteurs'],$temp);
 }
 
 echo json_encode($response);