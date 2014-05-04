						<h2>Add Client's Pets</h2>
								
								<div class="span4">
									<label>Pet Name:</label>
									<span id="petName_err" class="fg-color-red"></span>
									<div class="input-control text">
										<input type="text" id="petName" placeholder="Type in Pet Name"/>
										<button class="btn-clear"></button>
									</div>
									
									<label>Animal:</label>
									<span id="animal_err" class="fg-color-red"></span>
									
									<div>
									<select id="animals">
											
									</select>
									<input type="text" id="petAnimal" placeholder="What animal is it?" style="display:none;"/>
									</div>
									
									<label>Breed:</label>
									
									<div class="input-control text">
										<input type="text" id="petBreed" placeholder="What breed?"/>
										<button class="btn-clear"></button>
									</div>
									
									<label>Origin:</label>
									<div class="input-control text">
										<input type="text" id="petOrigin" placeholder="The Origin"/>
										<button class="btn-clear"></button>
									</div>
									
									<label>Breeder:</label>
									<div class="input-control text">
										<input type="text" id="petBreeder" placeholder="the breeder"></input>	
									</div>
									
									<label>Sex:</label>
									<label class="input-control radio " onclick="">
                                    <input type="radio" name="sex" value="male" checked="">
                                    <span class="helper">male</span>
									</label>
									<label class="input-control radio" onclick="">
                                    <input type="radio" name="sex" value="female" checked="">
                                    <span class="helper">female</span>
									</label>
									<br />
									<label>Microchip No:</label>
									<div class="input-control text">
										<input type="text" id="microchpNo" placeholder="Microchip number?"/>
										<button class="btn-clear"></button>
									</div>
									
									<label>Upload Picture of pet:</label>
									<div class="input-control text">
										<input type="file" placeholder="Type in Client's folder Number here"/>
									</div>
									
									<button id="btnaddClientPet" class="bg-color-green fg-color-white place-right">Add Pet</button>
									
							</div>
							
							<div class= "span4" >
								<h3>Client's information</h3>
								<div id="newCli_Info" class="tile-group" style="width: auto; max-width: 322px;">
									
								</div>
								
								<div id="newPet_Info" style="display:none">
								
								</div>
							</div>
							
<script type="text/javascript">
	$(document).ready(function(){
		//getElement
		
		var petCount = 0;
		$("#addClientPet").click(function(){});
			
		$("#btnaddClientPet").click(function(){
			var petName_err="", petAnimal_err="";
			
			if ($("#petName").val() == ""){
				petName_err = "<strong>Please Type in Pet Name!</strong>";
			}else if ($("animals").filter(":selected").val() == 'other'){
				if ($("#petAnimal").val() == ""){
					petAnimal_err = "<strong>Please Type in Animal!</strong>";
				}
			}else{
			var petName = $("#petName").val();
			var petAnimal = "";
				if ($("#animals").val() == 'other'){
					petAnimal = $("#petAnimal").val();
				}else{
					petAnimal = $("#animals").val();
				}
			
			var petBreed = $("#petBreed").val();
			var petOrigin = $("#petOrigin").val();
			var petBreeder = $("#petBreeder").val();
			var petSex = $("input:radio[name='sex']:checked").val();
			var petMicrChpNo = $("#microchpNo").val();
			
			savePetUrl = "functional/savePet.php";
			savePet(savePetUrl,petName,petAnimal,petBreed,petOrigin,petBreeder,petSex,petMicrChpNo,cur_Client_Id);
			if ($("#animals").val() == 'other'){
				var newAnimal = $("#petAnimal").val();
				addAnimalToDb(newAnimal);
			}
			
			$("input[id=petName]").val("");
			$("input[id=petAnimal]").val("");
			$("input[id=petBreed]").val("");
			$("input[id=petOrigin]").val("");
			$("input[id=petBreeder]").val("");
			$("input[id=microchpNo]").val("");
				
		}
			$("#petName_err").html(petName_err);
			$("#animal_err").html(petAnimal_err);	 
		
		});
		
		function savePet(savePetUrl,petName,petAnimal,petBreed,petOrigin,petBreeder,petSex,petMicrChpNo,cur_Client_Id){
			$.post(savePetUrl,
				{petName:petName,
				 petAnimal:petAnimal,
				 petBreed:petBreed,
				 petOrigin:petOrigin,
				 petBreeder:petBreeder,
				 sex:petSex,
				 microchpNo:petMicrChpNo,
				 owner:cur_Client_Id},
				 function (responseText){
							var reply = responseText;
							try{
								var replyJSON = JSON.parse(reply);
								
								curDivPet_id ="addPetDiv"+petCount;
								bufferPetInfo = "<div id=\""+curDivPet_id+"\" class=\"tile icon bg-color-pinkDark\" style=\"display:none;\">"+
								"<div class=\"tile-content\" >"+
								"<img src=\"images/petImgs/petIcon.png\" style=\"width:60px;height:60px;\" >"+
								"<p>Pet Name: "+replyJSON.petName+"</p>"+
								"</div>"+
								"<div class=\"brand\">"+
									"<div class=\"badge\">"+replyJSON.animal+"</div>"+
									"<div class=\"name\">"+replyJSON.sex+"</div>"+
								"</div>"+
								"</div>";
								//"<p><small>Sex: "+replyJSON.sex+"</small></p>";
								
								$("#newCli_Info").append(bufferPetInfo);
								$("#"+curDivPet_id).fadeIn('fast');
								petCount++;
								
								displayNotice("Client's Pet has been added successfully","Pet Added");
							}catch(e){
								console.log("somethig is terribly wrong! "+e);
							}
				 })
		}
		
		function addAnimalToDb(newAnimal){
			var url = "functional/addAnimalToDB.php";
			$.post(url,
					{animal:newAnimal},
					function (responseText){
						console.log("done Deal");
					});
		}
		
	});
</script>