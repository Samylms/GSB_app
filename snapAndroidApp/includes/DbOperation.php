<?php
  
class DbOperation
{
    //Database connection link
    private $con;
 
    //Class constructor
    function __construct()
    {
        //Getting the DbConnect.php file
        require_once dirname(__FILE__) . '/DbConnect.php';
 
        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();
 
        //Initializing our connection link of this class
        //by calling the method connect of DbConnect class
        $this->con = $db->connect();
    }
	
	/*
	* The create operation
	* When this method is called a new record is created in the database
	*/
	function createVehicule( $idMarque,$libelle, $type, $libre){
		$stmt = $this->con->prepare("INSERT INTO marque (idMarque,libelle, type, libre) VALUES ( ?,?, ?, ?) ");
		$stmt->bind_param( "issi", $idMarque, $libelle, $type, $libre);
		if($stmt->execute())
			$stmt->close();
		     /*récupération dernier id insérer*/
		$last_id_marque = $this->con->insert_id;
		
		$stmt2 = $this->con->prepare("INSERT INTO vehicule (noImmat,idMarque) VALUES (?,?)"); 
		$stmt2->bind_param( "ii", $noImmat, $last_id_marque);
		$stmt2->execute();
		$stmt2->close();
        printf ("Last Record has id %d.\n", $stmt->insert_id);
    			
				 /*récupération dernier id insérer*/
		$last_id_marque2 = $this->con->insert_id;
		
		$stmt3 = $this->con->prepare("INSERT INTO possede (noImmat,idMarque) VALUES (?,?)"); 
		$stmt3->bind_param( "ii", $noImmat, $last_id_marque2);
		$stmt3->execute();
		$stmt3->close();
        printf ("Last Record has id %d.\n", $stmt->insert_id);
				
				return true; 
		return false; 
		
	}

	/*
	* The read operation
	* When this method is called it is returning all the existing record of the database
	*/
	function getVehicule(){
		$stmt = $this->con->prepare("SELECT idMarque, libelle, type, libre FROM marque");
		$stmt->execute();
		$stmt->bind_result( $idMarque, $libelle, $type, $libre);
		
		$vehicules = array(); 
		
		while($stmt->fetch()){
			$vehicule  = array();
			$vehicule['idMarque'] = $idMarque ;
 			$vehicule['libelle'] = $libelle ;
			$vehicule['type'] = $type; 
			$vehicule['libre'] = $libre; 
 			
			array_push($vehicules, $vehicule);
			
			
			  /* fetch values */
			  /*
    while ($stmt->fetch()) {
	 print_r ($vehicules);
         
    }*/

		}

		return $vehicules; 
	}
	
	/*
	* The update operation
	* When this method is called the record with the given id is updated with the new given values
	*/
	function updateVehicule( $idMarque,$libelle, $type, $libre ){
		$stmt = $this->con->prepare("UPDATE marque SET libelle = ?, type = ?, libre =? WHERE idMarque=?");
		$stmt->bind_param("ssii", $libelle, $type, $libre,$idMarque);
		if($stmt->execute())
			return true; 
		return false; 
	}
	
	
	/*
	* The delete operation
	* When this method is called record is deleted for the given id 
	*/
	function deleteVehicule($idMarque){
		
		$stmt = $this->con->prepare("DELETE FROM vehicule WHERE idMarque = ? ");
		$stmt->bind_param("i", $idMarque);
		if($stmt->execute())
			
		$stmt = $this->con->prepare("DELETE FROM marque WHERE idMarque = ? ");
		$stmt->bind_param("i", $idMarque);
		if($stmt->execute())
			
			return true; 
		
		return false; 
	}
	
	function createVisiteur( $nom, $prenom, $age, $numPermis){
		$stmt = $this->con->prepare("INSERT INTO visiteur (nom, prenom, age, numPermis) VALUES ( ?, ?, ?, ?)");
		$stmt->bind_param( "ssii", $nom, $prenom, $age, $numPermis);
		if($stmt->execute())
			return true; 
		return false; 
	}
	
	
	
		 
	
function getOptionVehicule(){
		$stmt = $this->con->prepare("SELECT idMarque, libelle, type, libre, libelle2, prix FROM marque m, optionvehicule o WHERE m.idMarque = o.numOptionV");
		$stmt->execute();
		$stmt->bind_result( $idMarque, $libelle, $type, $libre,$libelle2,$prix);
		
		$vehicules = array(); 
		
		while($stmt->fetch()){
			$vehicule  = array();
			$vehicule['idMarque'] = $idMarque ;
 			$vehicule['libelle'] = $libelle ;
			$vehicule['type'] = $type; 
			$vehicule['libre'] = $libre; 
			$vehicule['libelle2'] = $libelle2; 
			$vehicule['prix'] = $prix; 

 			
			array_push($vehicules, $vehicule);
			
			
			  /* fetch values */
			  /*
    while ($stmt->fetch()) {
	 print_r ($vehicules);
         
    }*/

		}

		return $vehicules; 
	}
	
 
	
	
    //storing token in database 
    public function registerVisiteur($email,$token){
        if(!$this->isEmailExist($email)){
            $stmt = $this->con->prepare("INSERT INTO visiteur (nom,prenom,age,numPermis,email,token,password) VALUES (NULL,NULL,NULL,NULL,?,?,NULL)");
            $stmt->bind_param("ss",$email,$token);
            if($stmt->execute())
                return 0; //return 0 means success
            return 1; //return 1 means failure
        }else{
            return 2; //returning 2 means email already exist
        }
    }
 
    //the method will check if email already exist 
    private function isEmailexist($email){
        $stmt = $this->con->prepare("SELECT numVis FROM visiteur WHERE email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
 
    //getting all tokens to send push to all visiteur
    public function getAllTokens(){
        $stmt = $this->con->prepare("SELECT token FROM visiteur");
        $stmt->execute(); 
        $result = $stmt->get_result();
        $tokens = array(); 
        while($token = $result->fetch_assoc()){
            array_push($tokens, $token['token']);
        }
        return $tokens; 
    }
 
    //getting a specified token to send push to selected visiteur
    public function getTokenByEmail($email){
        $stmt = $this->con->prepare("SELECT token FROM visiteur WHERE email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute(); 
        $result = $stmt->get_result()->fetch_assoc();
        return array($result['token']);        
    }
 
    //getting all the registered visiteur from database 
    public function getAllVisiteur(){
        $stmt = $this->con->prepare("SELECT numVis,email,token FROM visiteur");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result; 
    }
	
	 function rechercheVisiteur( ){
		$stmt = $this->con->prepare("SELECT numVis,nom,prenom FROM visiteur ");
		$stmt->execute();
		$stmt->bind_result( $numVis, $nom, $prenom );
		
		$visiteurs = array(); 
		
		while($stmt->fetch()){
			$visiteur  = array();
			$visiteur['numVis'] = $numVis ;
 			$visiteur['nom'] = $nom ;
			$visiteur['prenom'] = $prenom; 
  			
			array_push($visiteurs, $visiteur);
			
			
			  /* fetch values */
			  /*
    while ($stmt->fetch()) {
	 print_r ($vehicules);
         
    }*/

		}

		return $visiteurs; 
	}
	
	
	
	/*
	* The create operation
	* When this method is called a new record is created in the database
	*/
	function createEmprunt($dateDep,$dateArr,$noImmat,$numVis){
		

		$stmt = $this->con->prepare("INSERT INTO emprunt (dateDep,dateArr,noImmat,numVis)VALUES (?,?,?,?)");
		$stmt->bind_param("iiii",$dateDep, $dateArr,$noImmat,$numVis);
		if($stmt->execute())
  				return true; 
		return false; 
 
	}
	 
	
	/*
	* The delete operation
	* count nb vehicule
	*/
	/*
	function countVehicule(){
		
$conn = mysql_connect("localhost", "root", "");
mysql_select_db("gsb", $conn);

$result = mysql_query("SELECT * FROM vehicule");
echo mysql_num_rows($result);

}*/


}