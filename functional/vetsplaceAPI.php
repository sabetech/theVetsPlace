<?php
	require_once("theVetsDB_API.php");
	class vetsplaceAPI{
		var $dbVar;
		
		function vetsplaceAPI(){
			global $dbVar;
			$dbVar = new theVetsDB_API();
		}
		
		function authenticate($username,$password){
			global $dbVar;
			if ($dbVar->connect()){
				$password = md5($password);
				$query_str = "SELECT * FROM users WHERE username= '$username' AND password= '$password'";
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows() == 1){
					return true;
				}else{
					return false;
				}
			}
		}
		
		function getUserInfo(){
			global $dbVar;
			$userinfo = $dbVar->fetch();
			$userid = $userinfo['user_id'];
			$usr_name = $userinfo['username'];
			$access_level = $userinfo['access_level'];
			
			$userInfoXML = <<<XML
			<userInfo>
				<username>$usr_name</username>
				<accessLevel>$access_level</accessLevel>
				<userid>$userid</userid>
			</userInfo>
XML;
			$userInfoXML_Obj = simplexml_load_string($userInfoXML);
			return $userInfoXML_Obj;
		}
		
		function signUp($username,$password){ //TODO
			global $dbVar;
			if ($dbVar->connect()){
			
				$user_id = uniqid(substr($username,0,2));
				$password = md5($password);
				
				$query_str = "INSERT INTO users (user_id,username,password,access_level) VALUES ('$user_id','$username','$password','regular')";
				
				$result = $dbVar->runQuery($query_str);
				
				if ($result){
					return true;
				}else{
					return false;
				}
			}
		}
		
		function acceptUserChanges($username,$password){ //TODO
		
		}
		
		function addNewClient($cliName,$cliPhone="N/A",$cliOtherPhn="N/A",$cliEmail="N/A",$cliAddress="N/A",$cliFolderNum,$imgFileLink="N/A"){
			global $dbVar;
			if ($dbVar->connect()){
			
				$cli_id = uniqid(substr($cliName,0,2));
				
				$query_str = "INSERT INTO clients (client_id,clientName,address,mainPhone,otherPhoneNos,email,folderNo,picture) VALUES ('$cli_id','$cliName','$cliAddress','$cliPhone','$cliOtherPhn','$cliEmail','$cliFolderNum','$imgFileLink')";
				
				//echo $query_str;
				$result = $dbVar->runQuery($query_str);
				
				if ($result){ //track user
					$this->trackUser("added the client $cliName to the database");
					session_start();
					$_SESSION['new_client_id'] = $cli_id;
					
					return true;
				}
			}
		}
		
		function getClientById($client_id){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM clients WHERE client_id = '$client_id'";
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					$newClient = $dbVar->fetch();
					$folderNum = $newClient['folderNo'];
					$cliId = $newClient['client_id'];
					$cliName = $newClient['clientName'];
					$cliAddress = $newClient['address'];
					$cliEmail = $newClient['email'];
					
					$jsonStr = "{\"folderNum\":\"$folderNum\",\"cliId\":\"$cliId\",\"cliName\":\"$cliName\",\"address\":\"$cliAddress\",\"email\":\"$cliEmail\"}";
					
					return $jsonStr;
				}
			}
		}
		
		function getNewClient(){
			session_start();
			global $dbVar;
			if ($dbVar->connect()){
				$client_id = $_SESSION['new_client_id'];
				
				$query_str = "SELECT * FROM clients WHERE client_id = '$client_id'";
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					$newClient = $dbVar->fetch();
					$folderNum = $newClient['folderNo'];
					$cliId = $newClient['client_id'];
					$cliName = $newClient['clientName'];
					$cliAddress = $newClient['address'];
					$cliEmail = $newClient['email'];
					
					$jsonStr = "{\"folderNum\":\"$folderNum\",\"cliId\":\"$cliId\",\"cliName\":\"$cliName\",\"address\":\"$cliAddress\",\"email\":\"$cliEmail\"}";
					
					return $jsonStr;
				}
			}
		}
		
		function savePet($petName,$petAnimal,$petBreed,$petOrigin,$petBreeder,$sex,$microNo,$owner){
			global $dbVar;
			if ($dbVar->connect()){
				$pet_id = uniqid(substr($petName,0,2));
				
				$query_str = "INSERT INTO pets_tbl (pet_id,pet_name,animal,breed,origin,owner_id,breeder,sex,microchip_no) VALUES ('$pet_id','$petName','$petAnimal','$petBreed','$petOrigin','$owner','$petBreeder','$sex','$microNo')";
				
				$result = $dbVar->runQuery($query_str);
				
				if ($result){ //track user
					$query_str = "SELECT clientName FROM pets_tbl INNER JOIN clients ON pets_tbl.owner_id = clients.client_id WHERE pet_id='$pet_id'";
					
					$dbVar->runQuery($query_str);
					$dbVar->fetch();
					$this->trackUser("saved the pet $petName a/an $petAnimal to the database");
					
					$jsonStr = "{\"petName\":\"$petName\",\"animal\":\"$petAnimal\",\"sex\":\"$sex\",\"owner\":\"$owner\"}";
					return $jsonStr;
				}
			}
		}
		
		function getClients($start=0,$end=0){
			global $dbVar;
			if ($dbVar->connect()){
				
				$query_str = "SELECT * FROM clients LIMIT {$start},{$end}";
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows() > 0){
					
					$json_Str = "{\"clients\":[";
					
					$clients = $dbVar->fetch();
					
					$cliName = $clients['clientName'];
					$clientId = $clients['client_id'];
					$cliFolderNo = $clients['folderNo'];
					
					$json_Str .= "{\"clientId\":\"$clientId\",\"name\":\"$cliName\",\"folderNo\":\"$cliFolderNo\"}";
					$clients = $dbVar->fetch();
					
					while($clients){
						$clientId = $clients['client_id'];
						$cliName = $clients['clientName'];
						$cliFolderNo = $clients['folderNo'];
						
						$json_Str .= ",{\"clientId\":\"$clientId\",\"name\":\"$cliName\",\"folderNo\":\"$cliFolderNo\"}";
						$clients = $dbVar->fetch();
					}
					$json_Str .= "]}";
					
					return $json_Str;
					//fetch his/her picture l8tr;
					
				}else{
					return "{\"Error Occured\":\"OUCH\"}";
				}
				
			}
		}
		
		function getClientDetail($clientId){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM pets_tbl INNER JOIN clients ON pets_tbl.owner_id=clients.client_id WHERE client_id = '$clientId'";
				$dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows() > 0){
					$c_detail = $dbVar->fetch();
					$json_str = "{\"c_detail\":[";
					
					$c_cliId = $c_detail['client_id'];
					$c_name = $c_detail['clientName'];
					$c_address = $c_detail['address'];
					$c_phone = $c_detail['mainPhone'];
					$c_other_phone = $c_detail['otherPhoneNos'];
					$c_email = $c_detail['email'];
					$c_folderNo = $c_detail['folderNo'];
					$pet_id = $c_detail['pet_id'];
					$c_petName = $c_detail['pet_name'];
					$c_petAnim = $c_detail['animal'];
					//$c_picture = $c_detail['picture']; include pic of both client and animal
					
					$json_str .= "{\"client_id\":\"$c_cliId\",\"c_name\":\"$c_name\",\"address\":\"$c_address\",\"phone\":\"$c_phone\",\"otherPhone\":\"$c_other_phone\",\"email\":\"$c_email\",\"folderNo\":\"$c_folderNo\",\"pet_id\":\"$pet_id\",\"petName\":\"$c_petName\",\"animal\":\"$c_petAnim\"}";
					
					$c_detail = $dbVar->fetch();
					
					while($c_detail){
						$c_cliId = $c_detail['client_id'];
						$c_name = $c_detail['clientName'];
						$c_address = $c_detail['address'];
						$c_phone = $c_detail['mainPhone'];
						$c_other_phone = $c_detail['otherPhoneNos'];
						$c_email = $c_detail['email'];
						$c_folderNo = $c_detail['folderNo'];
						$pet_id = $c_detail['pet_id'];
						$c_petName = $c_detail['pet_name'];
						$c_petAnim = $c_detail['animal'];
						
						$json_str .= ",{\"client_id\":\"$c_cliId\",\"c_name\":\"$c_name\",\"address\":\"$c_address\",\"phone\":\"$c_phone\",\"otherPhone\":\"$c_other_phone\",\"email\":\"$c_email\",\"folderNo\":\"$c_folderNo\",\"pet_id\":\"$pet_id\",\"petName\":\"$c_petName\",\"animal\":\"$c_petAnim\"}";
						
						$c_detail = $dbVar->fetch();
					}
					
					$json_str.="]}";
					return $json_str;
				}else{//ie if client has no pets
					$query_str = "SELECT * FROM clients WHERE client_id = '$clientId'";
					$result = $dbVar->runQuery($query_str);
					
					if ($dbVar->get_num_rows() > 0){
						$c_detail = $dbVar->fetch();
						
						$json_str = "{\"c_detail\":[";
						
						$c_cliId = $c_detail['client_id'];
						$c_name = $c_detail['clientName'];
						$c_address = $c_detail['address'];
						$c_phone = $c_detail['mainPhone'];
						$c_other_phone = $c_detail['otherPhoneNos'];
						$c_email = $c_detail['email'];
						$c_folderNo = $c_detail['folderNo'];
						
						$json_str .= "{\"client_id\":\"$c_cliId\",\"c_name\":\"$c_name\",\"address\":\"$c_address\",\"phone\":\"$c_phone\",\"otherPhone\":\"$c_other_phone\",\"email\":\"$c_email\",\"folderNo\":\"$c_folderNo\",\"petName\":\"\"}";
						
						$json_str.="]}";
						return $json_str;

					}
				}
			}
		}
		
		function getPetByClientId($cli_id){
			global $dbVar;
			if($dbVar->connect()){
				$query_str = "SELECT * FROM pets_tbl WHERE owner_id='$cli_id'";
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					$json_str = "{\"pets\":[";
					
					$curPet = $dbVar->fetch();
					
					$petId = $curPet['pet_id'];
					$pet_name = $curPet['pet_name'];
					$pet_anim = $curPet['animal'];
					
					$json_str .= "{\"petId\":\"$petId\",\"petName\":\"$pet_name\",\"petAnim\":\"$pet_anim\"}";
					
					$curPet = $dbVar->fetch();
					
					while($curPet){
						$petId = $curPet['pet_id'];
						$pet_name = $curPet['pet_name'];
						$pet_anim = $curPet['animal'];
						$ownerId = $curPet['owner_id'];		
						
						$json_str .= ",{\"petId\":\"$petId\",\"petName\":\"$pet_name\",\"petAnim\":\"$pet_anim\"}";
						
						$curPet = $dbVar->fetch();
					}
					
					$json_str .= "]}";
					
					return $json_str;
				}
			}
		}
		
		function deleteClient($clientId){
			global $dbVar;
			if ($dbVar->connect()){
				try{
					$dbVar->beginTransaction();
						$query_str = "DELETE FROM pets_tbl WHERE owner_id = '$clientId'";
						$result = $dbVar->runQuery($query_str);
					
						$query_str2 = "DELETE FROM clients WHERE client_id = '$clientId'";
						$result = $dbVar->runQuery($query_str2);
						
					$dbVar->commit();
					
					$query_str = "SELECT clientName FROM clients WHERE client_id = '$clientId'";
					$dbVar->runQuery($query_str);
					$client = $dbVar->fetch();
					$clientName = $client['clientName'];
					$this->trackUser("deleted the client $clientName along with his/her pets ");
					
					return true; //track user
					}catch(Exception $e){
						$dbVar->rollback();
						return false;
					}
			}
		}
		
		function updateClient($clientId,$name,$address,$phone,$otherPhone,$email,$folderNo){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "UPDATE clients SET clientName='$name', address='$address', mainPhone='$phone', otherPhoneNos='$otherPhone', email='$email', folderNo='$folderNo' WHERE client_id='$clientId'";
				$result = $dbVar->runQuery($query_str);
				
				if ($result){ //track user
					$this->trackUser("update the information of client $name");
					return true;
				}
			}
		}
		
		function getClientId($clientName){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM clients WHERE clientName = '$clientName'";
				$result = $dbVar->runQuery($query_str);
				if ($dbVar->get_num_rows()>0){
					$clientId = $dbVar->fetch();
					return $clientId['client_id'];
				}
			}
		}
		
		function searchClient($cliName){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM clients WHERE clientName LIKE '%$cliName%' OR folderNo LIKE '$cliName%' LIMIT 0 , 17";
				$result = $dbVar->runQuery($query_str);
				//group into one function l8tr
				if ($dbVar->get_num_rows() > 0){
					$json_Str = "{\"clients\":[";
					
					$clients = $dbVar->fetch();
					
					$cliName = $clients['clientName'];
					$clientId = $clients['client_id'];
					$clientPhone = $clients['mainPhone'];
					$cliFolderNo = $clients['folderNo'];
					
					$json_Str .= "{\"clientId\":\"$clientId\",\"name\":\"$cliName\",\"phone\":\"$clientPhone\",\"folderNo\":\"$cliFolderNo\"}";
					
					$clients = $dbVar->fetch();
					
					while($clients){
						$clientId = $clients['client_id'];
						$cliName = $clients['clientName'];
						$clientPhone = $clients['mainPhone'];
						$cliFolderNo = $clients['folderNo'];
						
						$json_Str .= ",{\"clientId\":\"$clientId\",\"name\":\"$cliName\",\"phone\":\"$clientPhone\",\"folderNo\":\"$cliFolderNo\"}";
						
						$clients = $dbVar->fetch();
					}
					$json_Str .= "]}";
					
					return $json_Str;
				}else{
					
					return "Gibberish ... client does not exist...";
				}
			}
		}
		
		function getAnimals(){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM animals";
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					
					$curAnimal = $dbVar->fetch();
					$animal = $curAnimal['animal'];
					$animals = "[{\"animal\":\"$animal\"}";
					
					while($curAnimal = $dbVar->fetch()){
						$animal = $curAnimal['animal'];
						$animals .= ",{\"animal\":\"$animal\"}";
					}
					$animals .= "]";
					return $animals;
				}
				
			}
		}
		
		function getPetById($petId){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM pets_tbl WHERE pet_id='$petId'";
				$result = $dbVar->runquery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					$json_str = "{\"pets\":[";
					
					$curPet = $dbVar->fetch();
					
					$petId = $curPet['pet_id'];
					$pet_name = $curPet['pet_name'];
					$pet_anim = $curPet['animal'];
					
					$json_str .= "{\"petId\":\"$petId\",\"petName\":\"$pet_name\",\"petAnim\":\"$pet_anim\"}";
					
					$curPet = $dbVar->fetch();
					
					while($curPet){
						$petId = $curPet['pet_id'];
						$pet_name = $curPet['pet_name'];
						$pet_anim = $curPet['animal'];
						$ownerId = $curPet['owner_id'];		
						
						$json_str .= ",{\"petId\":\"$petId\",\"petName\":\"$pet_name\",\"petAnim\":\"$pet_anim\"}";
						
						$curPet = $dbVar->fetch();
					}
					
					$json_str .= "]}";
					
					return $json_str;
				}
			}
		}
		
		function getPets($start,$end){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM pets_tbl LIMIT {$start}, {$end}";
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					$json_str = "{\"pets\":[";
					
					$curPet = $dbVar->fetch();
					
					//picture of the pet can included here
					
					$petId = $curPet['pet_id'];
					$pet_name = $curPet['pet_name'];
					$pet_anim = $curPet['animal'];
					
					$json_str .= "{\"petId\":\"$petId\",\"petName\":\"$pet_name\",\"petAnim\":\"$pet_anim\"}";
					
					$curPet = $dbVar->fetch();
					
					while($curPet){
						$petId = $curPet['pet_id'];
						$pet_name = $curPet['pet_name'];
						$pet_anim = $curPet['animal'];
						$ownerId = $curPet['owner_id'];		
						
						$json_str .= ",{\"petId\":\"$petId\",\"petName\":\"$pet_name\",\"petAnim\":\"$pet_anim\"}";
						
						$curPet = $dbVar->fetch();
					}
					
					$json_str .= "]}";
					
					return $json_str;
				}else{
					//No pets at all....
				}
			}
		}
		
		function getPetDetail($petId){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM pets_tbl INNER JOIN clients ON pets_tbl.owner_id = clients.client_id WHERE pet_id = '$petId'";
				
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows() > 0){
					$json_str = "{\"petDetail\":[";
					$petDetail = $dbVar->fetch();
					
					$petID = $petDetail['pet_id'];
					$pet_name = $petDetail['pet_name'];
					$pet_anim = $petDetail['animal'];
					$pet_breed = $petDetail['breed'];
					$pet_origin = $petDetail['origin'];
					$pet_ownerID = $petDetail['owner_id'];
					$pet_ownerName = $petDetail['clientName'];
					$pet_breeder = $petDetail['breeder'];
					$pet_sex = $petDetail['sex'];
					$pet_microChp = $petDetail['microchip_no'];
					
					$json_str .= "{\"petID\":\"$petID\",
								  \"pet_name\":\"$pet_name\",
								  \"pet_anim\":\"$pet_anim\",
								  \"pet_breed\":\"$pet_breed\",
								  \"pet_origin\":\"$pet_origin\",
								  \"pet_ownerID\":\"$pet_ownerID\",
								  \"pet_ownerName\":\"$pet_ownerName\",
								  \"pet_breeder\":\"$pet_breeder\",
								  \"pet_sex\":\"$pet_sex\",
								  \"pet_microChp\":\"$pet_microChp\"}";
								  
					$json_str .= "]}";
					
					return $json_str;
					
				}
			}
		}
		
		function searchPets($petName){
			//search by animal type
			//search by name
			//search by 
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM pets_tbl WHERE pet_name LIKE '%$petName%' LIMIT 0, 20";
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows() > 0){
					$json_str = "{\"pets\":[";
					$curPet = $dbVar->fetch();
					
					//picture of the pet can included here
					
					$petId = $curPet['pet_id'];
					$pet_name = $curPet['pet_name'];
					$pet_anim = $curPet['animal'];
					$ownerId = $curPet['owner_id'];		
					$json_str .= "{\"petId\":\"$petId\",\"petName\":\"$pet_name\",\"petAnim\":\"$pet_anim\"}";
					
					$curPet = $dbVar->fetch();
					
					while($curPet){
						$petId = $curPet['pet_id'];
						$pet_name = $curPet['pet_name'];
						$pet_anim = $curPet['animal'];
						$ownerId = $curPet['owner_id'];		
						
						$json_str .= ",{\"petId\":\"$petId\",\"petName\":\"$pet_name\",\"petAnim\":\"$pet_anim\",\"ownerId\":\"$owernerId\"}";
						
						$curPet = $dbVar->fetch();
					}
					
					$json_str .= "]}";
					
					return $json_str;
				}
			}
		}
		
		function deletePet($petId){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "DELETE FROM pets_tbl WHERE pet_id = '$petId'";
				$result = $dbVar->runQuery($query_str);
				
				if ($result){ 
				//track user
					$query_str = "SELECT pet_name, clientName FROM pets_tbl INNER JOIN clients ON pets_tbl.owner_id = clients.client_id WHERE pet_id = '$petId'";
					$dbVar->runQuery($query_str);
					$pet = $dbVar->fetch();
					$petName = $pet['pet_name'];
					$owner = $pet['clientName'];
					$this->trackUser("deleted the pet $petName belonging to $owner");
					return true;
				}else
					return false;
			}
		}
		
		function searchAnimal($term){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM  animals WHERE animal LIKE '%$term%'";
				$dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					$json_str="[";
					$animals = $dbVar->fetch();
					$sugg = $animals['animal'];
					$json_str .= "\"$sugg\"";
					
					while($animals = $dbVar->fetch()){
						$sugg = $animals['animal'];
						$json_str .= ",\"$sugg\",";
					}
					
					$json_str .= "]";
					return $json_str;
					
					
					
				}
			}
		}
		
		function updatePet($petId,$pet_Name,$animal,$breed,$origin,$petSex, $breeder,$microNo,$ownerId){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "UPDATE pets_tbl SET pet_name='$pet_Name', animal='$animal', breed='$breed', origin='$origin', sex='$petSex', breeder='$breeder', microchip_no='$microNo', owner_id='$ownerId' WHERE pet_id = '$petId'";
				
				$result = $dbVar->runQuery($query_str);
				if ($result){ //track user
					$query_str = "SELECT pet_name FROM pets_tbl WHERE pet_id = '$petId'";
					$dbVar->runQuery($query_str);
					$pet = $dbVar->fetch();
					$petName = $pet['pet_name'];
					$this->trackUser("updated the details of a pet $petName");
					return true;
				}else{
					return false;
				}
			}
		}
		
		function setVaccination($petId,$occurence,$date,$description){
			global $dbVar;
			if ($dbVar->connect()){
				if ($occurence === 'vac'){
					$vacId = uniqid(substr("vaccination",0,2));
					try{
					$dbVar->beginTransaction();
						
						$query_str1 = "UPDATE pet_vaccination SET state = 'out-dated', status = 'DONE' WHERE petId = '$petId'";
						$query_str2 = "INSERT INTO pet_vaccination (vaccinationID,petId,date,status,state,description) VALUES ('$vacId','$petId','$date','PENDING','current','$description')";
						
						$dbVar->runQuery($query_str1);
						$dbVar->runQuery($query_str2);
						
					$dbVar->commit();
					//trackUser;
						$query_str2 = "SELECT pet_name FROM pet_vaccination INNER JOIN pets_tbl ON pet_vaccination.petId = pets_tbl.pet_id WHERE petId = '$petId'";
						$dbVar->runQuery($query_str2);
						$pet = $dbVar->fetch();
						$petName = $pet['pet_name'];
						$this->trackUser("set the date for vaccination for $petName on $date");
					}catch(Exception $e){
						$dbVar->rollback();
					}
					
					
					if ($dbVar->result){
						return true;
					}else
						return false;
					
					
				}else{
					$dewormId = uniqid(substr("deworm",0,2));
					try{
						$dbVar->beginTransaction();
							$query_str1 =  "UPDATE pet_deworming SET state = 'out-dated', status = 'DONE' WHERE petId = '$petId'";
							$query_str2 = "INSERT INTO pet_deworming (dewormingID,petId,date,status,state,description) VALUES ('$dewormId','$petId','$date','PENDING','current','$description')";
							
							$dbVar->runQuery($query_str1);
							$dbVar->runQuery($query_str2);
							//track user
						$dbVar->commit();
						
						$query_str2 = "SELECT pet_name FROM pet_deworming INNER JOIN pets_tbl ON pet_deworming.petId = pets_tbl.pet_id WHERE petId = '$petId'";
						$dbVar->runQuery($query_str2);
						$pet = $dbVar->fetch();
						$petName = $pet['pet_name'];
						$this->trackUser("set the date for deworming for $petName on $date");
					}catch(Exception $e){
						$dbVar->rollback();
					}
					
						if ($dbVar->result){
							return true;
						}else
							return false;
				}
			}
		}
		
		function getDewormDate($petId){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT date FROM pet_deworming WHERE petId = '$petId' AND state='current'";
				
				$result = $dbVar->runQuery($query_str);
				if ($dbVar->get_num_rows()>0){
					$date = $dbVar->fetch();
					
					$dwormDate = $date['date'];
					$dwormDate = date('d-m-Y',strtotime($dwormDate));
					
					return $dwormDate;
				}else{
					return 'Not Set';
				}
			}
		}
		
		function getVaccinationDate($petId){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT date FROM pet_vaccination WHERE petId = '$petId' AND state='current'";
				
				$result = $dbVar->runQuery($query_str);
				if ($dbVar->get_num_rows()>0){
					$date = $dbVar->fetch();
					
					$vacDate = $date['date'];
					$vacDate = date('d-m-Y',strtotime($vacDate));
					
					return $vacDate;
				}else{
					return 'Not Set';
				}
			}
		}
		
		function addAnimalToDB($animal){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM animal WHERE animal='$animal'";
				
				$dbVar->runQuery($query_str);
				if ($dbVar->get_num_rows()>0){
					//animal already exists;
				}else{
					$animal_id = uniqid(substr($animal,0,2));
					$query_str = "INSERT INTO animals (animal_id, animal) VALUES ('$animal_id','$animal')";
					$result = $dbVar->runQuery($query_str);
					if ($result){
						//track user
						$this->trackUser("added a new animal into the Database!");
						return true;
					}else{
						return false;
					}
				}
			}
		}
		
		function getAllItems(){
			global $dbVar;
			if ($dbVar->connect()){
				//$query_str = "SELECT * FROM inventory";
				
				$query_str = "SELECT * FROM inventorylogs INNER JOIN inventory ON inventorylogs.itemId = inventory.itemId WHERE item_state='existing' AND stockState='current' ORDER BY Quantity";
				
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows() > 0){
					$fetch_result = $dbVar->fetch(); 
					$jsonStr = "{\"items\":[";
					
					$itemName = $fetch_result['itemName'];
					$itemType = $fetch_result['itemType'];
					$itemQty = $fetch_result['Quantity'];
					
					$jsonStr .= "{\"itemName\":\"$itemName\",\"itemType\":\"$itemType\",\"itemQty\":\"$itemQty\"}";
					$fetch_result = $dbVar->fetch();
					
					while ($fetch_result){
						$itemName = $fetch_result['itemName'];
						$itemType = $fetch_result['itemType'];
						$itemQty = $fetch_result['Quantity'];
						$jsonStr .= ",{\"itemName\":\"$itemName\",\"itemType\":\"$itemType\",\"itemQty\":\"$itemQty\"}"; 
						$fetch_result = $dbVar->fetch();
					}
					
					$jsonStr .= "]}";
					return $jsonStr;
				}else{	
					$query_str = "SELECT * FROM inventory";
					$result = $dbVar->runQuery($query_str);
					
					$fetch_result = $dbVar->fetch(); 
					
					$jsonStr = "{\"items\":[";
					
					$itemName = $fetch_result['itemName'];
					$itemType = $fetch_result['itemType'];
					
					$jsonStr .= "{\"itemName\":\"$itemName\",\"itemType\":\"$itemType\"}";
					$fetch_result = $dbVar->fetch();
					
					while ($fetch_result){
						$itemName = $fetch_result['itemName'];
						$itemType = $fetch_result['itemType'];
						
						$jsonStr .= ",{\"itemName\":\"$itemName\",\"itemType\":\"$itemType\"}";
						$fetch_result = $dbVar->fetch();
						
					}
					$jsonStr .= "]}";
					return $jsonStr;
				}
			}
		}
		
		function getCurExistnItems(){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM inventorylogs INNER JOIN inventory ON inventorylogs.itemId = inventory.itemId WHERE item_state='existing' AND stockState='current' ORDER BY itemType";
				
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					$json_str = "{\"currentItems\":[";
					
					$curItem = $dbVar->fetch();
					
					$itemId = $curItem['itemId'];
					$itemName = $curItem['itemName'];
					$itemQty = $curItem['Quantity'];
					$itemType = $curItem['itemType'];
					
					$json_str.= "{\"$itemType\":[{\"itemName\":\"$itemName\",\"itemQty\":\"$itemQty\",\"itemId\":\"$itemId\"}";
					
					$prevItemType = $itemType;

					$curItem = $dbVar->fetch();
				
					while($curItem){
						$itemId = $curItem['itemId'];
						$itemName = $curItem['itemName'];
						$itemQty = $curItem['Quantity'];
						$itemType  = $curItem['itemType'];
						
						if ($itemType === $prevItemType){
							$json_str.=	",{\"itemName\":\"$itemName\",\"itemQty\":\"$itemQty\",\"itemId\":\"$itemId\"}";
							$prevItemType = $itemType;
						}else{
							$json_str.= "]},";
							$json_str.= "{\"$itemType\":[{\"itemName\":\"$itemName\",\"itemQty\":\"$itemQty\",\"itemId\":\"$itemId\"}";
							
							$prevItemType = $itemType;
						}
							
						$curItem = $dbVar->fetch();							
					}
					$json_str.= "]}]}";	
					return $json_str;
				}else{
					$query_str = "SELECT * FROM inventory";
					
					$result = $dbVar->runQuery($query_str);
					
					if ($dbVar->get_num_rows()>0){
						$json_str = "{\"currentItems\":[";
						
						$curItem = $dbVar->fetch();
						
						$itemId = $curItem['itemId'];
						$itemName = $curItem['itemName'];
						$itemType = $curItem['itemType'];
						
						$json_str.= "{\"$itemType\":[{\"itemName\":\"$itemName\",\"itemId\":\"$itemId\"}";
						
						$prevItemType = $itemType;
						
						$curItem = $dbVar->fetch();
					
						while($curItem){
							$itemId = $curItem['itemId'];
							$itemName = $curItem['itemName'];
							$itemType  = $curItem['itemType'];
							
							if ($itemType === $prevItemType){
								$json_str.=	",{\"itemName\":\"$itemName\",\"itemId\":\"$itemId\"}";
								$prevItemType = $itemType;
							}else{
								$json_str.= "]},";
								$json_str.= "{\"$itemType\":[{\"itemName\":\"$itemName\",\"itemId\":\"$itemId\"}";
								
								$prevItemType = $itemType;
							}
								
							$curItem = $dbVar->fetch();							
						}
						$json_str.= "]}]}";	
						return $json_str;
					}
				}
			}
		}
		
		function searchItemWidQty($itemName){
			global $dbVar;
			if ($dbVar->connect()){
				//$query_str = "SELECT * FROM inventory";
				
				$query_str = "SELECT * FROM inventorylogs INNER JOIN inventory ON inventorylogs.itemId = inventory.itemId WHERE item_state='existing' AND stockState='current' AND inventory.itemName LIKE '%$itemName%' ORDER BY Quantity"; 
				
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows() > 0){
					$fetch_result = $dbVar->fetch(); 
					$jsonStr = "{\"items\":[";
					
					$itemName = $fetch_result['itemName'];
					$itemType = $fetch_result['itemType'];
					$itemQty = $fetch_result['Quantity'];
					
					$jsonStr .= "{\"itemName\":\"$itemName\",\"itemType\":\"$itemType\",\"itemQty\":\"$itemQty\"}";
					$fetch_result = $dbVar->fetch();
					
					while ($fetch_result){
						$itemName = $fetch_result['itemName'];
						$itemType = $fetch_result['itemType'];
						$itemQty = $fetch_result['Quantity'];
						$jsonStr .= ",{\"itemName\":\"$itemName\",\"itemType\":\"$itemType\",\"itemQty\":\"$itemQty\"}"; 
						$fetch_result = $dbVar->fetch();
					}
					
					$jsonStr .= "]}";
					return $jsonStr;
				}else{
					$query_str = "SELECT * FROM inventory WHERE itemName LIKE '$itemName%'";
					$result = $dbVar->runQuery($query_str);
					
					$fetch_result = $dbVar->fetch(); 
					
					$jsonStr = "{\"items\":[";
					
					$itemName = $fetch_result['itemName'];
					$itemType = $fetch_result['itemType'];
					
					$jsonStr .= "{\"itemName\":\"$itemName\",\"itemType\":\"$itemType\"}";
					$fetch_result = $dbVar->fetch();
					
					while ($fetch_result){
						$itemName = $fetch_result['itemName'];
						$itemType = $fetch_result['itemType'];
						
						$jsonStr .= ",{\"itemName\":\"$itemName\",\"itemType\":\"$itemType\"}";
						$fetch_result = $dbVar->fetch();
						
					}
					$jsonStr .= "]}";
					return $jsonStr;
				}
			}
		}
		
		function getStockRecs($from,$to){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM inventorylogs INNER JOIN inventory ON inventorylogs.itemId = inventory.itemId WHERE date >= '$from' AND date <= '$to' AND item_state='existing' ORDER BY date desc";
				
				$result = $dbVar->runQuery($query_str);
				if ($dbVar->get_num_rows() > 0){
					$json_str2 = "{\"invtory\":[";
					$stockInfo = $dbVar->fetch();
					$itmId = $stockInfo['inv_log_id'];
					$itmname = $stockInfo['itemName'];
					$itmType = $stockInfo['itemType'];
					$itmDate = $stockInfo['date'];
					$itmQty = $stockInfo['Quantity'];
					
					$json_str2 .= "{\"inv_log_id\":\"$itmId\",\"itemName\":\"$itmname\",\"itemType\":\"$itmType\",\"itemDate\":\"$itmDate\",\"itemQty\":\"$itmQty\"}";
					
					$stockInfo = $dbVar->fetch();
					
					while($stockInfo){
						$itmId = $stockInfo['inv_log_id'];
						$itmname = $stockInfo['itemName'];
						$itmType = $stockInfo['itemType'];
						$itmDate = $stockInfo['date'];
						$itmQty = $stockInfo['Quantity'];
						
						$json_str2 .= ",{\"inv_log_id\":\"$itmId\",\"itemName\":\"$itmname\",\"itemType\":\"$itmType\",\"itemDate\":\"$itmDate\",\"itemQty\":\"$itmQty\"}";
						$stockInfo = $dbVar->fetch();
					}
					
					$json_str2 .= "]}";
					return $json_str2;
				}else
					return "gibberish";
				
			}
		}
		
		function getNewItems($date){
			global $dbVar;
			if ($dbVar->connect()){
				//echo $date;
				$query_str = "SELECT * FROM inventorylogs INNER JOIN inventory ON inventorylogs.itemid = inventory.itemId WHERE item_state = 'new' AND stockState = 'current' AND date='$date'";
				
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows() > 0){
					$json_str = "{\"newItems\":[";
					$newItems = $dbVar->fetch();
					
					$itemName = $newItems['itemName'];
					$itemType = $newItems['itemType'];
					$itemQty = $newItems['Quantity'];
					
					$json_str .= "{\"itemName\":\"$itemName\",\"itemType\":\"$itemType\",\"itemQty\":\"$itemQty\"}";
					
					$newItems = $dbVar->fetch();
					
					while($newItems){
						$itemName = $newItems['itemName'];
						$itemType = $newItems['itemType'];
						$itemQty = $newItems['Quantity'];
					
						$json_str .= ",{\"itemName\":\"$itemName\",\"itemType\":\"$itemType\",\"itemQty\":\"$itemQty\"}";
						
						$newItems = $dbVar->fetch();
					}
					
					$json_str .= "]}";
					
					return $json_str;
					
				}else{
					echo "wrong date";
					//new item fetch error here.. error include no data with date , wrong date actually
				}
			}
		}
		
		function getRecentItems(){
			
		}
		
		function searchItem($itemName){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM inventory WHERE itemName LIKE '$itemName%'";
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows() > 0){
					$json_str = "{\"item\":[";
					$searchedItem = $dbVar->fetch();
					
					$itemId = $searchedItem['itemId'];
					$itemName = $searchedItem['itemName'];
					$itemType = $searchedItem['itemType'];
					
					$json_str .= "{\"itemId\":\"$itemId\",\"itemName\":\"$itemName\",\"itemType\":\"$itemType\"}";
					
					$json_str .= "]}";
					
					return $json_str;
				}
			}
		}
		
		function saveNewItem($itemId,$itemDate,$itemQty,$itemName){
			global $dbVar;
			if ($dbVar->connect()){
				$newItem_id = uniqid(substr('Nw',0,2));
				
				try{
					$dbVar->beginTransaction();
					
					$query_str2 = "UPDATE inventorylogs SET stockState='out-dated' WHERE itemId='$itemId' AND item_state= 'new'";
					
					$query_str = "INSERT INTO inventorylogs (inv_log_id, itemId, item_state, stockState, Quantity, date) VALUES ('$newItem_id','$itemId','new','current','$itemQty','$itemDate')";
					
					$updateResult = $dbVar->runQuery($query_str2);
					$result = $dbVar->runQuery($query_str);
					
					$dbVar->commit();
					
					//$dbVar->free_result();
					//track user
					$this->trackUser("Added A new Item $itemName with Quantity $itemQty at $itemDate");
					$newItems_detail = $this->getNewItemDetails($newItem_id,$itemName);
					echo $newItems_detail;
					
					
				}catch(Exception $e){
					$dbVar->rollback();
					//$dbVar->free_result();
					//display error or whateer to the user;
				}
			}
		}
		
		function getNewItemDetails($newItem_Id,$itemName){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM inventorylogs WHERE inv_log_id = '$newItem_Id'";
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					$newItemDetails = $dbVar->fetch();
					
					$nw_qty = $newItemDetails['Quantity'];
					$nw_date = $newItemDetails['date'];
	
					$json_str = "{\"newItemId\":\"$newItem_Id\",\"itemName\":\"$itemName\",\"qty\":\"$nw_qty\",\"date\":\"$nw_date\"}";
					
					$dbVar->free_result();
					return $json_str;
				}
			}
		}
		
		function getItemCategories(){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT itemType FROM inventory GROUP BY itemType";
				$dbVar->runQuery($query_str);
				if ($dbVar->get_num_rows() > 0){
					$itemCategories = $dbVar->fetch();
					
					$json_str = "{\"itemCategories\":[";
					$itemType = $itemCategories['itemType'];
					$json_str .= "{\"itemType\":\"$itemType\"}";
					
					$itemCategories = $dbVar->fetch();
					
					while($itemCategories){
						$itemType = $itemCategories['itemType'];
						$json_str .= ",{\"itemType\":\"$itemType\"}";
					
						$itemCategories = $dbVar->fetch();
					}
					
					$json_str .= "]}";
					
					return $json_str;
				}
			}
		}

		function takeStock($itemId,$quantity,$date){
			global $dbVar;
			if($dbVar->connect()){
				try{
					$inv_Log_Id = uniqid(substr('In',0,2));
					$dbVar->beginTransaction();
					
					$query_str2 = "UPDATE inventorylogs SET stockState = 'out-dated' WHERE item_state = 'existing' AND date <> '$date'";
					
					$query_str="INSERT INTO inventorylogs (inv_log_id,itemId,item_state,stockState,Quantity,date) VALUES ('$inv_Log_Id','$itemId','existing','current','$quantity','$date')";
					
					$dbVar->runQuery($query_str2);
					$dbVar->runQuery($query_str);
					$dbVar->commit();
					//track user
					return true;
				}catch(Exception $e){
					$dbVar->rollback();
				
				}
			}
		}
		
		function editStockVal($id,$val){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "UPDATE inventorylogs SET Quantity='$val' WHERE inv_log_id='$id'";
				$result = $dbVar->runQuery($query_str);
				if ($result){
					$query_str = "SELECT itemName FROM inventorylogs INNER JOIN inventory ON inventorylogs.inv_log_id = inventory.itemId WHERE inv_log_id = '$id'";
					$dbVar->runQuery($query_str);
					$item = $dbVar->fetch();
					$itemName = $item['itemName'];

					$this->trackUser("updated the inventory log of item $itemName to $val");
					return true;
				}else{
					return false;
				}
				          
			}
		}
		function checkIfStockTaken($date){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM inventorylogs WHERE date='$date' AND item_state='existing'";
				$result = $dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					return true;
				}else{
					return false;
				}
			}
		}
		
		function expiryDate(){
			//not really a function. its just a reminder for me to do some drug expiry date functionality
		}
		
		function getOverviewInfo(){
			global $dbVar;
			if ($dbVar->connect()){
				
				$query_str = "SELECT COUNT(clientName) AS clientNo FROM clients";
				$result = $dbVar->runQuery($query_str);
				
				$json_str = "{\"SUMMARY\":[{";	;
				if ($dbVar->get_num_rows()>0){
					$clients = $dbVar->fetch();
					$clientNo = $clients['clientNo'];
					$json_str .= "\"clientNo\":\"$clientNo\",";
				}else{
					$json_str .= "\"clientNo\":\"0\",";
				}
				return $this->getDogs($json_str);
			}
				
		}
		
		function getDogs($json_str){
			global $dbVar;
			if ($dbVar->connect()){
			$query_str = "SELECT COUNT(animal) AS Dogs FROM pets_tbl WHERE animal = 'Dog'";
			$dbVar->runQuery($query_str);
			
				if ($dbVar->get_num_rows()>0){
					$dogs = $dbVar->fetch();
					$dogsNo = $dogs['Dogs'];
					$json_str .= "\"Dogs\":\"$dogsNo\",";
				}else{
					$json_str .= "\"Dogs\":\"0\",";
				}
			}
			return $this->getCats($json_str);
		}
		
		function getCats($json_str){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT COUNT(animal) AS Cats FROM pets_tbl WHERE animal = 'Cat'";
				$dbVar->runQuery($query_str);
				if ($dbVar->get_num_rows()>0){
					$cats = $dbVar->fetch();
					$catsNo = $cats['Cats'];
					
					$json_str .= "\"Cats\":\"$catsNo\",";
				}else{
					$json_str .= "\"Cats\":\"0\",";
				}
			}
			return $this->getOthers($json_str);
		}
		
		function getOthers($json_str){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT COUNT(animal) AS others FROM pets_tbl WHERE animal <> 'Dog' OR animal <> 'Cat'";
				$dbVar->runQuery($query_str);
				if ($dbVar->get_num_rows() > 0){
					$others = $dbVar->fetch();
					$othersNo = $others['others'];
					
					$json_str .= "\"others\":\"$othersNo\"}]}";
				}else{
					$json_str .= "\"others\":\"0\"}]}";
				}
				
			}
			return $json_str;
		}
		
		function getDueVaccinations($date){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM pet_vaccination JOIN pets_tbl ON pet_vaccination.petId = pets_tbl.pet_id JOIN clients ON pets_tbl.owner_id = clients.client_id WHERE date = '$date' AND status = 'PENDING'"; 
				
				$dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					$json_str = "{\"Notices\":[";
					
					$curVaccination = $dbVar->fetch();
					
					$vacId = $curVaccination['vaccinationID'];
					$petName = $curVaccination['pet_name'];
					$clientName = $curVaccination['clientName'];
					$clientPhone = $curVaccination['mainPhone'];
					$folderNo = $curVaccination['folderNo'];
					$description = $curVaccination['description'];
					
					$json_str .= "{\"vacId\":\"$vacId\",\"petName\":\"$petName\",\"clientName\":\"$clientName\",\"clientPhone\":\"$clientPhone\",\"folderNo\":\"$folderNo\",\"description\":\"$description\"}";
					
					$curVaccination = $dbVar->fetch();
					
					while($curVaccination){
						$vacId = $curVaccination['vaccinationID'];
						$petName = $curVaccination['pet_name'];
						$clientName = $curVaccination['clientName'];
						$clientPhone = $curVaccination['mainPhone'];
						$folderNo = $curVaccination['folderNo'];
						$description = $curVaccination['description'];
						
						$json_str .= ",{\"vacId\":\"$vacId\",\"petName\":\"$petName\",\"clientName\":\"$clientName\",\"clientPhone\":\"$clientPhone\",\"folderNo\":\"$folderNo\",\"description\":\"$description\"}";
					
						$curVaccination = $dbVar->fetch();
					}
					$json_str .= "]}";
					return $json_str;
				}else{
					$json_str = "{\"Message\":\"No Vaccinations Due\"}";
					return $json_str;
				}
			}
		}
		
		function getDueDeworming($date){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM `pet_deworming` JOIN pets_tbl ON `pet_deworming`.petId = pets_tbl.pet_id JOIN clients ON pets_tbl.owner_id = clients.client_id WHERE date = '$date' AND status = 'PENDING'";
				
				$dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					$json_str = "{\"Notices\":[";
					
					$curDeworming = $dbVar->fetch();
					
					$dewormId = $curDeworming['dewormingID'];
					$petName = $curDeworming['pet_name'];
					$clientName = $curDeworming['clientName'];
					$clientPhone = $curDeworming['mainPhone'];
					$folderNo = $curDeworming['folderNo'];
					$description = $curDeworming['description'];
					
					$json_str .= "{\"dewormId\":\"$dewormId\",\"petName\":\"$petName\",\"clientName\":\"$clientName\",\"clientPhone\":\"$clientPhone\",\"folderNo\":\"$folderNo\",\"description\":\"$description\"}";
					
					$curDeworming = $dbVar->fetch();
					
					while($curDeworming){
						$dewormId = $curDeworming['dewormingID'];
						$petName = $curDeworming['pet_name'];
						$clientName = $curDeworming['clientName'];
						$clientPhone = $curDeworming['mainPhone'];
						$folderNo = $curDeworming['folderNo'];
						$description = $curDeworming['description'];
						
						$json_str .= "{\"dewormId\":\"$dewormId\",\"petName\":\"$petName\",\"clientName\":\"$clientName\",\"clientPhone\":\"$clientPhone\",\"folderNo\":\"$folderNo\",\"description\":\"$description\"}";
						
						$curDeworming = $dbVar->fetch();
					}
					$json_str .= "]}";
					return $json_str;
					
				}else{
					$json_str = "{\"Message\":\"No Deworming Due\"}";
					return $json_str;
				}
			}
		}
		
		function getRecentActivites($searchTxt){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM userlogs WHERE  user LIKE '%$searchTxt%' OR date LIKE '$searchTxt%' ORDER BY `date` desc LIMIT 0,30";
				$dbVar->runQuery($query_str);
				if ($dbVar->get_num_rows()>0){
					$activities = $dbVar->fetch();
					$json_activities = "{\"Activities\":[";
					
					$theUser = $activities['user'];
					$theDate = $activities['date'];
					$details = $activities['details'];
					
					$theDate = date('d-m-Y',strtotime($theDate));
					
					$json_activities .= "{\"user\":\"$theUser\",\"date\":\"$theDate\",\"details\":\"$details\"}";
					$activities = $dbVar->fetch();
					while($activities){
						$theUser = $activities['user'];
						$theDate = $activities['date'];
						$details = $activities['details'];
						
						$theDate = date('d-m-Y',strtotime($theDate));
						
						$json_activities .= ",{\"user\":\"$theUser\",\"date\":\"$theDate\",\"details\":\"$details\"}";
						$activities = $dbVar->fetch();
					}
					$json_activities .= "]}";
					return $json_activities;
				}
				
			}
		}
		
		function trackUser($action){
			global $dbVar;
			if ($dbVar->connect()){
				$logId = uniqid(substr($user,0,2));
				$myDate = date('Y-m-d');
				
				session_start();
				$user = $_SESSION['USER'];
				
				$query_str = "INSERT INTO userlogs (logId,user,date,details) VALUES ('$logId','$user','$myDate','$action')";
				$dbVar->runQuery($query_str);	
			}
		}
		
		function saveDBNewItem($itemName,$itemType){
			global $dbVar;
			if ($dbVar->connect()){
			
				$itemId = uniqid(substr($itemName,0,2));
				$query_str = "SELECT itemName FROM inventory WHERE itemName='$itemName'";
				$dbVar->runQuery($query_str);
				
				if ($dbVar->get_num_rows()>0){
					return false;
				}
				
				try{
					$dbVar->beginTransaction();
						$query_str = "INSERT INTO inventory (itemId,itemName,itemType) VALUES ('$itemId','$itemName','$itemType')";
						
						$result = $dbVar->runQuery($query_str);
						
						$inv_log_id = uniqid(substr('In',0,2));
						$dateToday = date('Y-m-d');
						
						$query_str2 = "INSERT INTO inventorylogs (inv_log_id,itemId,item_state,stockState,Quantity,date) VALUES ('$inv_log_id','$itemId','existing','current','0','$dateToday')";
						
						$result = $dbVar->runQuery($query_str2);
						
					$dbVar->commit();
					$this->trackUser("ADDED A NEW ITEM INTO THE DATABASE");
					return true;
				}catch(Exception $e){
					$dbVar->rollback();
					return false;
				}	
			}
		}
	
		function delDBItem($itemId){
			global $dbVar;
			if ($dbVar->connect()){
				try{
				$dbVar->beginTransaction();
					$query_str = "DELETE FROM inventorylogs WHERE itemId = '$itemId'";
					$query_str2 = "DELETE FROM inventory WHERE itemId = '$itemId'";
					
					$dbVar->runQuery($query_str);
					$dbVar->runQuery($query_str2);
				$dbVar->commit();
				$this->trackUser("DELETED AN ITEM FROM THE DATABASE");
				return true;
				}catch(Exception $e){
					$dbVar->rollback();
					return false;
				}
			}
		}
		
		function updateItem($itemId,$itemName){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "UPDATE inventory SET itemName = '$itemName' WHERE itemId='$itemId'";
				
				$result = $dbVar->runQuery($query_str);
				//$query_str = "UPDATE inventorylogs SET "
				//$dvVar->runQuery();
				if (true){
					$this->trackUser("UPDATED AN ITEM IN THE DATABASE");
					return true;
				}else{
					return false;
				}
			}
		}
		
		function sendMsgToAll($msg){
			global $dbVar;
			if ($dbVar->connect()){
				$query_str = "SELECT * FROM clients";
				$dbVar->runQuery($query_str);
				if ($dbVar->get_num_rows()>0){
					
					while($clientsNo = $dbVar->fetch()){
						$phone = $clientNo['mainPhone'];
						if ($phone != ""){
							$url = "http://www.mytxtbox.com/smsghapi.ashx/sendmsg?api_id=123456&user=kofex&password=kofex&to={$phone}&text={$msg}&from='VetsPlace'";
						}else{
							continue;
						}
					}
				}
			}
		}
	}
?>