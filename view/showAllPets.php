<div class="span8"><!-- settin div size to change-->
	<h2>Show All Pets</h2>
	<div class="input-control text span4">
		<input id="txtSearchPet" type="text" placeholder="Search for pet by Name"/>
		<button class="btn-petSearch"></button>
	</div>
</div>

<div id="divPetList" class="offset1" style="width:80%; height:100%; top:100px; position:absolute;">
	<ul id="ul_AllPets" class="listview fluid">
		
	</ul>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		var loading = "<img src=\"images/preloader-w8-cycle-black.gif\">";
		
		$("#btnShowAllPets").click(function(){
			$("#ul_AllPets").html(loading);
			$(currentDiv).fadeOut(200,function(){
				$("#divShowAllPets").fadeIn(500,function(){
					loadPetsAsync("functional/getPets.php",0,17);//Set bounds later using a config file
					currentDiv = "#divShowAllPets";
				});
			});
		});
		
		
		$("#txtSearchPet").keyup(function(){
			$("#ul_AllPets").html(loading);
			loadPetsAsync("functional/searchPet.php",0,17);
		});
		
		function loadPetsAsync(url,start,end){
			$.get(url,
			{petName:$("#txtSearchPet").val(),
			 start:start,
			 end:end},
			function(responseText){
				var reply = responseText;
				try{
					var replyJSON = JSON.parse(reply);
					var bufferPets ="";
					
					if (start > 0){//the first next has been clicked
						bufferPets += "<div id='prevPetList' class='tile bg-color-orange'><div class='tile-content'><h2><< PREVIOUS PETS</h2></div></div>"
					}
					
					for (var i=0;i<replyJSON.pets.length;i++){
									//show pets here
						bufferPets += "<div id = '"+replyJSON.pets[i].petId+"' class='petDiv tile bg-color-blueDark' style='display:none;' onClick = 'petClicked(this)'>"+
						"<div class='tile-content'>"+
						"<img src='images/petImgs/petIcon.png' style='width:60px; height:60px'>"+
						"<p>"+replyJSON.pets[i].petName+"</p>"+
						"</div>"+
						"<div class='brand'>"+
							"<div class='name'>"+replyJSON.pets[i].petAnim+"</div>"+
						"</div>"+
						"</div>";
					}
						if ($("#txtSearchPet").val().length == 0){ //the pet search box is empty
							bufferPets += "<div id='nxtPetList' class='tile bg-color-orange'><div class='tile-content'><h2>MORE PETS >></h2></div></div>";
						}
				}catch(e){
					bufferPets = "<div id='petGoBack' class='tile double bg-color-red'>"+
					"<div class='tile-content'><h3>No Pets Found</h3><p class='fg-color-yellow'>Click here to Show all pets </p></div></div>";
				}
				
				$("#ul_AllPets").html(bufferPets);
				
				$(".petDiv").each(function(){
					try{
						$(this).show(500);
					}catch(e){}
				});
				
				try{
					$("#nxtPetList").click(function(){
						loadPetsAsync("functional/getPets.php",end,(end+17));
					});
					
					$("#prevPetList").click(function(){
						loadPetsAsync("functional/getPets.php",(start-17),(end-17));
					});
					
					$("#petGoBack").click(function(){
						loadPetsAsync("functional/getPets.php",0,17);
					});
				}catch(e){}
				
			});			
		}
	});
	
	function changeCurrentDiv(url,id){
		var loading = "<img src=\"images/preloader-w8-cycle-black.gif\">";
		
		if (url != 'functional/getClientById.php'){
			$(currentDiv).fadeOut(200,function(){
				$("#ul_AllPets").html(loading);
				$("#divShowAllPets").fadeIn(500,function(){
					currentDiv = "#divShowAllPets";
					findPet(url,id);
					
				});
				currentDiv = "#divShowAllClients";
			});
		}else{
			$(currentDiv).fadeOut(200,function(){
				$("#divAddClientPet").fadeIn(500,function(){
					getClientById(url,id);
					currentDiv= "#divAddClientPet";
				})
			})
		}
	}
	
	function findPet(url,id){
		$.get(url,
			{id:id},
			function (responseText){
				var reply = responseText;
				var bufferPet = "";
				try{
					var replyJSON = JSON.parse(responseText);
					for (var i=0;i < replyJSON.pets.length;i++){
						bufferPet += "<div id = '"+replyJSON.pets[i].petId+"' class='petDivById tile bg-color-blueDark' style='display:none;' onClick = 'petClicked(this)'>"+
							"<div class='tile-content'>"+
							"<img src='images/petImgs/petIcon.png' style='width:60px; height:60px'>"+
							"<p>"+replyJSON.pets[i].petName+"</p>"+
							"</div>"+
							"<div class='brand'>"+
								"<div class='name'>"+replyJSON.pets[i].petAnim+"</div>"+
							"</div>"+
							"</div>";
					}
				}catch(e){
					console.log("No pet found");
				}
				
				$("#ul_AllPets").html(bufferPet);
				
				$(".petDivById").each(function(){
					try{
						$(this).show(500);
					}catch(e){}
				});	
			});
	}
	
	function getClientById(url,id){
		loadAnimals();
		$.get(url,{id:id},
			function(responseText){
				try{
					var reply = responseText;
					var replyJSON = JSON.parse(reply);
						bufferClientInfo = "<div class=\"tile double bg-color-green\">"+"<div class=\"tile-content\">"+
											"<img src=\"images/clientImgs/defaultPic.jpg\" class=\"place-left\" style=\"width:60px;height:60px;\">"
											+"<h3 style=\"margin-botton:5px;\">"+replyJSON.cliName+"</h3>"+"<p>Address: "+replyJSON.address+"</p><p>Email: "+replyJSON.email+"</p>"+
											"</div>"+
											"<div class=\"brand\">"+
											"<span class=\"name\">The Vets Place Folder ID: "+replyJSON.folderNum+"</span></div></div>";
											cur_Client_Id = replyJSON.cliId;
											$("#newCli_Info").html(bufferClientInfo);
						}catch(e){
							console.log(e+" is the exception");// DO SUMPHIN HERE 
						}
			});
	}
	
	var previousPetDiv;
	var previousPetHTML;
	function petClicked(petDivObj){
		try{
			if (previousPetDiv == petDivObj){}
			else{
				getPetDetails(petDivObj);
			}
		}catch(e){
			getPetDetails(petDivObj);
		}
	}
	
	function getPetDetails(petDivObj){
		try{
			var classAdd = 'triple double-vertical';
			var classRemove = 'triple double-vertical';
			var classChangeSpeed = 200;
			var classEffect = 'easeInOutQuad';

			$.get("functional/getPetDetails.php",
			{petId:$(petDivObj).attr('id')},
			function (responseText){
				var reply = responseText;
				//console.log(reply);
				console.log($(petDivObj).attr('id'));
				
				var replyJSON = JSON.parse(reply);
				
				var buffPetDetail = "";
				
			
			buffPetDetail += "<div class='tile-content'>"+
								 "<div class='tool-bar place-right' data-role='button-set'>"+
								 "<button id='deworm_pet' class='tool-button shortcut' style='width:32px;height:32px;'><i class='icon-droplet' style='color:#202020;'></i></button>"+
								 "<button id='edit_pet' class='tool-button shortcut' style='width:32px;height:32px;'><i class='icon-pencil' style='color:#202020;'></i></button>"+
								 "<button id='del_pet' class='tool-button shortcut' style='width:32px;height:32px;'><i class='icon-remove' style='color:#202020;'></i></button>"+
								 "</div>"+
								 "<img src='images/petImgs/defaultAnim.jpg' style='width:80px;height:80px'>"+
								 "<p>Name: "+replyJSON.petDetail[0].pet_name+"</p>"+
								 "<p>Animal: "+replyJSON.petDetail[0].pet_anim+"</p>"+
								 "<p>Sex: "+replyJSON.petDetail[0].pet_sex+"</p>"+
								 "<p>Breed: "+replyJSON.petDetail[0].pet_breed+"</p>"+
								 "<p>Origin: "+replyJSON.petDetail[0].pet_origin+"</p>"+
								 "<p>Microchip_no: "+replyJSON.petDetail[0].pet_microChp+"</p>"+
								 "<div class='place-right'>"+
								 "<h4><u>Owner</u></h4>"+
								 "<p><a id='"+replyJSON.petDetail[0].pet_ownerID+"' class='ownerLink'>"+replyJSON.petDetail[0].pet_ownerName+"</a></p>"+
								 "</div>";
								 
					displayPetDetails(petDivObj,buffPetDetail);
					
					$("a[class='ownerLink']").click(function(){
						var url = "functional/getClientById.php";
						changeToClientDiv(url,this.id);
						
					});
					
					$("#deworm_pet").click(function (){
						//console.log("clicked deworn");
						
						
						var dewormPetHtml = "<div id='periodicals' style='display:none;'><h3 class='padding20'>Vaccination/Deworming for "+replyJSON.petDetail[0].pet_name+"</h3>"+
						"<div class='span2' style='margin-left:2em;'><p>Vaccination Info:</p>"+"<p class='fg-color-yellow'>Vaccination Date:<br /><p id='vaccinationDate' class='fg-color-yellow'>Loading...</p></p><p style='margin-top:1em;'>Deworming Info:</p><p class='fg-color-yellow'> Deworming Date:<br /><p id='dewormDate' class='fg-color-yellow'>Loading...</p></p></div>"+
						
						//----------------CHOOSE A DATE -----------
									
						"<div class='span3' style='margin-left:1em;'>";
						
						var months = new Array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
						
						dewormPetHtml += "Choose the date<div style='margin-bottom:1em;'><select id='days' class='fg-color-darken' style='width:3em;margin-right:1em;'>";
						
						for (var i=0;i<31;i++){
							dewormPetHtml += "<option value='"+(i+1)+"' class='fg-color-darken'>"+(i+1)+"</option>";
						}
						
						dewormPetHtml += "</select>";
						dewormPetHtml += "<select id='month' class='fg-color-darken' style='width:4em;margin-right:1em;'>";
						
						for (var i=0;i<12;i++){
							dewormPetHtml += "<option value='"+i+1+"' style='width:2em;margin-right:1em;' class='fg-color-darken'>"+ months[i] +"</option>";
						}
						
						dewormPetHtml += "</select>";
						dewormPetHtml += "<select id='year' class='fg-color-darken' style='width:4em;margin-right:1em;'>";
						
						for (var i=0;i<50;i++){
							dewormPetHtml += "<option value='"+ (i+2000) +"'style='width:2em;margin-right:1em;' class='fg-color-darken'>"+ (i+2000) +"</option>";
						}
						dewormPetHtml += "</select>";
						
						dewormPetHtml += "</div>Select<div class='input-control select'><select id= 'occurence' class='fg-color-darken'>"+
						"<option value='vac' class='fg-color-darken'>Vaccination</option>"+
						"<option value='deworm' class='fg-color-darken'> Deworming</option></select></div>";
						
						dewormPetHtml += "Description:<div class='input-control text'><input id='description' class='fg-color-darken' type='text' placeholder='description'/><button class='btn-clear'></button></div>";
						
						dewormPetHtml += "<div style='margin-top:2em'><button id='saveDewormDate' class='fg-color-darken'>Save</button>"+"<button id='cancelDewormDate' class='fg-color-darken'>Cancel</button></div>";
						
						$(petDivObj).html(dewormPetHtml);
						
						
						$(petDivObj).removeClass('bg-color-blueDark',classChangeSpeed,classEffect,function(){
							$(petDivObj).addClass('bg-color-green',classChangeSpeed,classEffect,function(){
								$("#periodicals").fadeIn(350);
							});
						});
							

						getDewormDate(petDivObj.id);
						getVaccinationDate(petDivObj.id);
						//put editing of date here!
						
						$("#cancelDewormDate").click(function(){
							$(petDivObj).removeClass("bg-color-green");	
							getPetDetails(petDivObj);
						});
						
						$("#saveDewormDate").click(function(){
							$(petDivObj).removeClass("bg-color-green");
							//if true send notice and reset div;
							day = $("#days").val();	
							month = $("#month").prop("selectedIndex") + 1;
							year = $("#year").val();
							
							//console.log($day);
							//console.log($month);
							//console.log($year);
							
							///COme here back really soon
							
							//optional : you can do date validation here or server side;
							//let say validation has been done!
							
							petId = $(petDivObj).attr('id');
							var occurence = $("#occurence").val();
							var description = $("#description").val();
							saveDewormDate(petId,occurence,day,month,year,description);
							
							getPetDetails(petDivObj);
						});
						
					});
					
					$("#edit_pet").click(function(){
						
						var editPetHtml = "<div id='editHTML' style='margin-top:1.5em;display:none;'>";
						
						editPetHtml += "<table style='border:none;'><tr><td style='border:none;'>Pet Name:</td><td style='border:none;'><input id ='p_name' type='text' class='fg-color-darken' value='"+replyJSON.petDetail[0].pet_name+"'></td></tr>"+
						
						"<tr><td style='border:none;'>Animal:</td><td style='border:none;'><input type='text' id='animal' class='fg-color-darken' value='"+replyJSON.petDetail[0].pet_anim+"'/></td></tr>"+
						
						//value='"+replyJSON.petDetail[0].pet_anim+"'
						
						"<tr><td style='border:none;'>Breed:</td><td style='border:none;'><input id='breed' type='text' class='fg-color-darken' value='"+replyJSON.petDetail[0].pet_breed+"'></td></tr>"+
						
						"<tr><td style='border:none;'>Origin:</td><td style='border:none;'><input id='origin' type='text' class='fg-color-darken' value='"+replyJSON.petDetail[0].pet_origin+"'></td></tr>"+
						
						"<tr><td style='border:none;'>Breeder:</td><td style='border:none;'><input id='breeder' type='text' class='fg-color-darken' value='"+replyJSON.petDetail[0].pet_breeder+"'></td></tr>"+
						
						"<tr><td style='border:none;'>Sex:</td><td style='border:none;'><label class='input-control radio ' onclick=''><input id='male' type='radio' name='sex' value='male' checked=''>"+
                                    "<span class='helper'>male</span>"+
									"</label>"+
									"<label class='input-control radio' onclick=''>"+
                                    "<input id='female' type='radio' name='sex' value='female' checked=''>"+
                                    "<span class='helper'>female</span>"+
									"</label></td></tr>"+
						
						"<tr><td style='border:none;'>Microchip No:</td><td style='border:none;'><input id='pet_microChp' type='text' class='fg-color-darken' value='"+replyJSON.petDetail[0].pet_microChp+"'></td></tr>"+
						
						"<tr><td style='border:none;'>pet_ownerName:</td><td style='border:none;'><input id='pet_ownerName' type='text' class='fg-color-darken' value='"+replyJSON.petDetail[0].pet_ownerName+"'></td></tr>"+
						
						"</table></div>"+
						
						"<div id='savebuttons' style='display:none;'><span id = 'newOwner' class='fg-color-red'>New Owner:</span><div class='place-right'>"+
						"<button id='savePetUpdate' class='fg-color-darken'>Save</button><button id='cancelPetUpdate' class='fg-color-darken'>Cancel</button></div></div>";
						
						$(petDivObj).html(editPetHtml);
						
						if (replyJSON.petDetail[0].pet_sex == 'male'){
							//$("input[name='sex'][value='female']").prop('checked',false);
							$("input[name='sex'][value='male']").prop('checked',true);
						}
						
						$(petDivObj).removeClass("bg-color-blueDark",classChangeSpeed,classEffect,function(){
							$(petDivObj).addClass("bg-color-orange",classChangeSpeed,classEffect,function(){
								$("#editHTML").fadeIn(350,function(){
									$("#savebuttons").fadeIn(200);
								});
							});
						});
						
						
						//$("#animal").autocomplete('functional/searchAnimal.php');
						//$("#animal").autocomplete({source:['albert','berty','computer']});
						
						$("#savePetUpdate").click(function(){
							
							if (($("#animal").val() != "")&&($("#p_name").val() != "")&&($("#pet_ownerName").val() != "")){								
								var petName = $("#p_name").val();
								var pet_animal = $("#animal").val();
								var breed = $("#breed").val();
								var origin = $("#origin").val();
								var breeder = $("#breeder").val();
								var petSex = $("input:radio[name='sex']:checked").val();	
								var microchp = $("#pet_microChp").val();
							
								var ownerId = "";
							
								$.get("functional/getClientId.php",
									{clientName:$("#pet_ownerName").val()},
								   function (responseText){
										ownerId = responseText;
										var url = 'functional/updatePet.php';
										$.post(url,{petId:petDivObj.id,
											petName:petName,
											pet_animal:pet_animal,
											breed:breed,
											origin:origin,
											breeder:breeder,
											petSex:petSex,
											microchp:microchp,
											ownerId:ownerId},
											function(responseText){							
												getPetDetails(petDivObj);
											});
								   }
								);
							//get owner id from the database: reverse lookup for id
							
								
							}else{
								displayError("Pet Details Update Error","The pet name, Animal, and owner fields are required to be filled. ");
							}
							
						});
						
						$("#cancelPetUpdate").click(function(){
							getPetDetails(petDivObj);
						});

					});
					
					$("#del_pet").click(function(){
						var delPetHtml = "<div id='delPetHtml' style='display:none'><i class='icon-help' style='font-size:60px;color:white;margin-left:40%;margin-top:20px;'></i><h2 class='padding20'>Are You Sure You Want to DELETE Pet <strong>"+replyJSON.petDetail[0].pet_name+"</strong> Belonging to <strong>"+replyJSON.petDetail[0].pet_ownerName+"</strong></h2>"+
						"<div class='place-right'>"+
						"<button id='yes' class='shortcut fg-color-darken' style='width:64px; height:32px;'>YES</button><button id='no' class='shortcut fg-color-darken' style='width:64px; height:32px;'>NO</button>"+
						"</div></div>";
						
						
						$(petDivObj).html(delPetHtml);
						$(petDivObj).removeClass("bg-color-blueDark",classChangeSpeed,classEffect,function(){
							$(petDivObj).addClass("bg-color-red",classChangeSpeed,classEffect,function(){
								$("#delPetHtml").fadeIn(350);
							});
						});
						
						$("#yes").click(function(){
							delete_pet(replyJSON.petDetail[0].petID,replyJSON.petDetail[0].pet_name,replyJSON.petDetail[0].pet_ownerName,petDivObj);
						});
						
						$("#no").click(function(){
							getPetDetails(petDivObj);
						});
						
						
					})
				});							
				
				if (previousPetDiv != petDivObj)
					$(previousPetDiv).removeClass(classRemove,classChangeSpeed,classEffect);
				
				$(previousPetDiv).removeClass("bg-color-green");
				$(previousPetDiv).removeClass("bg-color-orange");
				$(previousPetDiv).removeClass("bg-color-red");
				
				$(previousPetDiv).addClass("bg-color-blueDark");
				$(petDivObj).addClass(classAdd,classChangeSpeed,classEffect);
				
				$(previousPetDiv).html(previousPetHTML);
				previousPetHTML = $(petDivObj).html();
				
				var loading = "<img src=\"images/preloader-w8-cycle-black.gif\">";
				$(petDivObj).html(loading);
				
		}catch(e){
			//console.log("hello  "+e);
			$(petDivObj).removeClass(classRemove,classChangeSpeed,classEffect);
			$(petDivObj).addClass(classAdd,classChangeSpeed,classEffect);
		}finally{
			previousPetDiv = petDivObj;
		}
		
	}
	
	function getDewormDate(petid){
		var url = "functional/getDewormDate.php";
		$.get(url,
			 {petid:petid},
			 function (responseText){
				$("#dewormDate").html(responseText);
			 });
	}
	
	function getVaccinationDate(petid){
		var url = "functional/getVaccinationDate.php";
		$.get(url,
			 {petid:petid},
			 function (responseText){
				$("#vaccinationDate").html(responseText);
			 });
	}
	
	function displayPetDetails(petDivObj,buffPetDetail){
		//you can do some fade stuff here before u display detail
		$(petDivObj).html(buffPetDetail);
	}
	
	function saveDewormDate(petId,occurence,day,month,year,description){
		
		var url = 'functional/setVaccination.php';
		$.post(url,
			  {petId:petId,
			   occurence:occurence,
			   day:day,
			   month:month,
			   year:year,
			   description:description},
			   function (responseText){
					var reply = responseText;
					//console.log(reply);
					try{
						var replyJSON = JSON.parse(reply);
						if (replyJSON.status == 'success'){
							displayNotice("Date is set of pet's Vaccination/Deworming is "+day+"-"+month+"-"+year+".","Vaccination/Deworming Date saved successfully");
						}else{
							displayError("Date for pet's vaccination/deworming could not be saved. <br />The problem might be with the date","Date could not be saved");
						}
					}catch(e){
						//console.log("pepe");
					}
			   });
	}
	
	function delete_pet(petId,petName,clientName,petDivObj){
		var url = 'functional/deletePet.php';
		$.post(url,
			  {petId:petId,
			   petName:petName,
			   clientName:clientName},
			   function(responseText){
				 var reply = responseText;
				 displayNotice(reply,'Pet Deleted Successfully');
				 $(petDivObj).hide();
			   });
	}
	
</script>
