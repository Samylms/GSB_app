<?php 

	//getting the dboperation class
	require_once '../includes/DbOperation.php';

	//function validating all the paramters are available
	//we will pass the required parameters to this function 
	function isTheseParametersAvailable($params){
		//assuming all parameters are available 
		$available = true; 
		$missingparams = ""; 
		
		foreach($params as $param){
			if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
				$available = false; 
				$missingparams = $missingparams . ", " . $param; 
			}
		}
		
		//if parameters are missing 
		if(!$available){
			$response = array(); 
			$response['error'] = true; 
			$response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';
			
			//displaying error
			echo json_encode($response);
			
			//stopping further execution
			die();
		}
	}
	
	//an array to display response
	$response = array();
	
	//if it is an api call 
	//that means a get parameter named api call is set in the URL 
	//and with this parameter we are concluding that it is an api call
	if(isset($_GET['apicall'])){
		
		switch($_GET['apicall']){
			
			//the CREATE operation
			//if the api call value is 'createvehicule'
			//we will create a record in the database
			case 'createvehicule':
				//first check the parameters required for this request are available or not 
				isTheseParametersAvailable(array('idMarque','libelle','type','libre'));
				
				//creating a new dboperation object
				$db = new DbOperation();
				
				//creating a new record in the database
				$result = $db->createVehicule(
				 	$_POST['idMarque'],
 					$_POST['libelle'],
					$_POST['type'],
					$_POST['libre']
				);
				

				//if the record is created adding success to response
				if($result){
					//record is created means there is no error
					$response['error'] = false; 

					//in message we have a success message
					$response['message'] = 'vehicule ajouté avec succes';

					//and we are getting all the vehicule from the database in the response
					$response['vehicules'] = $db->getVehicule();
				}else{

					//if record is not added that means there is an error 
					$response['error'] = true; 

					//and we have the error message
					$response['message'] = 'Some error occurred please try again';
				}
				
			break; 
			
			//the READ operation
			//if the call is readVehicule
			case 'getvehicule':
				$db = new DbOperation();
				$response['error'] = false; 
				$response['message'] = 'Requête réussi';
				$response['vehicules'] = $db->getVehicule();
			
			break; 
			
			
			//the UPDATE operation
			case 'updatevehicule':
				isTheseParametersAvailable(array('idMarque','libelle','type','libre'));
				$db = new DbOperation();
				$result = $db->updateVehicule(
 					$_POST['idMarque'],
					$_POST['libelle'],
					$_POST['type'],
					$_POST['libre']
				);
				
				if($result){
					$response['error'] = false; 
					$response['message'] = 'Vehicule mis à jour avec succes';
					$response['vehicules'] = $db->getVehicule();
				}else{
					$response['error'] = true; 
					$response['message'] = 'Some error occurred please try again';
				}
			break; 
			
			//the delete operation
			case 'deletevehicule':

				//for the delete operation we are getting a GET parameter from the url having the id of the record to be deleted
				if(isset($_GET['idMarque'])){
					$db = new DbOperation();
					if($db->deleteVehicule($_GET['idMarque'])){
						$response['error'] = false; 
						$response['message'] = 'Vehicule supprimé avec succes';
						$response['vehicules'] = $db->getVehicule();
					}else{
						$response['error'] = true; 
						$response['message'] = 'Some error occurred please try again';
					}
				}else{
					$response['error'] = true; 
					$response['message'] = 'Nothing to delete, provide an id please';
				}
			break; 
			
				case 'createvisiteur':
				//first check the parameters required for this request are available or not 
				isTheseParametersAvailable(array('nom','prenom','age','numPermis'));
				
				//creating a new dboperation object
				$db = new DbOperation();
				
				//creating a new record in the database
				$result = $db->createVisiteur(
					$_POST['nom'],
					$_POST['prenom'],
					$_POST['age'],
					$_POST['numPermis']
				);
				

				//if the record is created adding success to response
				if($result){
					//record is created means there is no error
					$response['error'] = false; 

					//in message we have a success message
					$response['message'] = 'visiteur ajouté avec succes';

					//and we are getting all the vehicule from the database in the response
					//$response['visiteur'] = $db->getVisiteur();
				}else{

					//if record is not added that means there is an error 
					$response['error'] = true; 

					//and we have the error message
					$response['message'] = 'Some error occurred please try again';
				}
				
			break; 
			
			
			 
			
			 
			
			case 'getoptionvehicule':
				$db = new DbOperation();
				$response['error'] = false; 
				$response['message'] = 'Requête réussi';
				$response['vehicules'] = $db->getOptionVehicule();
			
			break; 
			
				 
			
			case 'recherchevisiteur':
              
				$db = new DbOperation();
				$response['error'] = false; 
				$response['message'] = 'Requête réussi';
				$response['visiteurs'] = $db->rechercheVisiteur( );
		 
			
			break; 
			
			
			case 'createemprunt':
				//first check the parameters required for this request are available or not 
				isTheseParametersAvailable(array('dateDep','dateArr','noImmat','numVis'));
				
				//creating a new dboperation object
				$db = new DbOperation();
				
				//creating a new record in the database
				$result = $db->createEmprunt(
					$_POST['dateDep'],
					$_POST['dateArr'],
					$_POST['noImmat'],
					$_POST['numVis']

					
				);
				

				//if the record is created adding success to response
				if($result){
					//record is created means there is no error
					$response['error'] = false; 

					//in message we have a success message
					$response['message'] = 'emprunt ajouté avec succes';

					 
				}else{

					//if record is not added that means there is an error 
					$response['error'] = true; 

					//and we have the error message
					$response['message'] = 'Some error occurred please try again';
				}
				
			break; 
			
			
			 
			 
			
			/*
			//the READ operation
			//if the call is countvehicule
			case 'countvehicule':
				$db = new DbOperation();
				$response['error'] = false; 
				$response['message'] = 'Request successfully completed';
				$response['vehicules'] = $db->countVehicule();
			
			break; 
			*/
		}
		
	}else{
		//if it is not api call 
		//pushing appropriate values to response array 
		$response['error'] = true; 
		$response['message'] = 'Invalid API Call';
	}
	
	//displaying the response in json structure 
	echo json_encode($response);
	
	function getFileExtension($file)
{
    $path_parts = pathinfo($file);
    return $path_parts['extension'];
}
	
