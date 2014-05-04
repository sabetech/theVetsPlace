
							<div style='width:40%'>
								<h2>Add New Client</h2>
								
								<div class="padding5 offset1">
									<label>Client Name:</label>
									<span class="fg-color-red" id="cliName_error"></span>
									<div class="input-control text">
										<input type="text" id="txtClientName" placeholder="Type in Client's Name"/>
										<button class="btn-clear"></button>
									</div>
									
									<label>Client's main Phone:</label>
									<div class="input-control text">
										<input type="phone" id="txtClientPhone" placeholder="Phone"/>
										<button class="btn-clear"></button>
									</div>
									
									<label>Other Phone numbers:</label>
									<div class="input-control text">
										<input type="phone" id="txtOtherPhone" placeholder="Phone"/>
										<button class="btn-clear"></button>
									</div>
									
									<label>Email:</label>
									<div class="input-control text">
										<input type="email" id="txtEmail" placeholder="Type in Client's Email"/>
										<button class="btn-clear"></button>
									</div>
									
									<label>Address:</label>
									<div class="input-control textarea">
										<textarea id= "txtAreaAddress" placeholder="Client's Address"></textarea>	
									</div>
									
									<label>Folder Number:</label>
									<span class="fg-color-red" id="fldSpan_Err"></span>
									<div class="input-control text">
										<input type="text" id="txtFolderNum" placeholder="Type in Client's folder Number here"/>
										<button class="btn-clear"></button>
									</div>
									
									<label>Upload Picture:</label>
									<div class="input-control text">
										<input type="file" id="uploadClientPic" placeholder="Type in Client's folder Number here"/>
									</div>
									
									<button id="takeWebCamPic" class="border-color-darken">Use Your WebCam To Take A picture</button>
									
									<button id="addClientPet" class="bg-color-green fg-color-white place-right">Click Here to add client's pets >></button>
								
								</div>
							</div>
							
<script type="text/javascript">
	$(document).ready(function(){
		$("#addClientPet").click(function(){
			var cliName_errMsg = "", fld_errMsg = "";
			
			if ($("#txtClientName").val() == ""){
				cliName_errMsg = "<strong>Type in Client Name!</strong>";
			}else if ($("#txtFolderNum").val() == ""){
				fld_errMsg = "<strong>Type in Folder Number!</strong>";
			}else{
				
				var cliName = $("#txtClientName").val();
				var cliPhone = $("#txtClientPhone").val();
				var cliOthrPhn = $("#txtOtherPhone").val();
				var cliEmail = $("#txtEmail").val();
				var cliAddress= $("#txtAreaAddress").val();
				var cliFolderNum = $("#txtFolderNum").val();
				
				saveClient(cliName,cliPhone,cliOthrPhn,cliEmail,cliAddress,cliFolderNum);
				
			};
			$("#cliName_error").html(cliName_errMsg);
			$("#fldSpan_Err").html(fld_errMsg);
		});
		
		function saveClient(cliName,cliPhone,cliOthrPhn,cliEmail,cliAddress,cliFolderNum){
			addUrl = "functional/saveClient.php";
			
			if (isNaN(cliFolderNum)){
				displayError("Folder Number has some invalid characters.It has to be integers.","Invalid Folder Number");
			}else if ((cliPhone.length > 0)||(cliOthrPhn.length > 0)){
				if ((isNaN(cliPhone))||(isNaN(cliOthrPhn))){
					displayError("Phone Number should not contain other characters apart from numbers","Invalid Phone Number");
				}else{
					ajaxCall(addUrl,cliName,cliPhone,cliOthrPhn,cliEmail,cliAddress,cliFolderNum);
				}
			}else{
				ajaxCall(addUrl,cliName,cliPhone,cliOthrPhn,cliEmail,cliAddress,cliFolderNum);
			}
		}
		
		function ajaxCall(addUrl,cliName,cliPhone,cliOthrPhn,cliEmail,cliAddress,cliFolderNum){
			$.post(addUrl,
			{cliName:cliName,
			cliPhone:cliPhone,
			cliEmail:cliEmail,
			cliAddress:cliAddress,
			cliFolderNum:cliFolderNum},
			function(responseText){
				displayNotice("Client "+cliName+" Has been ADDED successfully to the database.","Client Added");
				
				$(currentDiv).fadeOut(200,function(){
					$("#divAddClientPet").fadeIn(500,function(){
						
					});
					currentDiv="#divAddClientPet";
					
					loadAnimals();
					
					var url = "functional/getNewClient.php";
					$.get(url,
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
				});
			});
		}
	});
	
	function loadAnimals(){
		$.get("functional/getAnimals.php",
						function(responseText){
							var reply = responseText;
							try{
								$("#animals").html("");
								var replyJSON = JSON.parse(reply);
								for (var i=0;i<replyJSON.length;i++){
									$("#animals").append(new Option(replyJSON[i].animal,replyJSON[i].animal),function(){
										
									});
								}
								$("#animals").append(new Option("Other","other"));
								$("#petAnimal").css('display','none');
								$('#animals').change(function() {
									if ($(this).find('option:selected').val() == 'other'){
										$("#petAnimal").fadeIn();
									}else{
										$("#petAnimal").fadeOut();
									}
								});
							}catch(e){
								console.log(e);
							}
						});	
	}
</script>
						