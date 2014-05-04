<div class="span8"><!-- settin div size to change-->
	<h2>Show All Clients</h2>
	<div class="input-control text span4">
		<input id="txtSearchClient" type="text" placeholder="Search by Folder No. or Client Name"/>
		<button class="btn-search"></button>
	</div>
</div>

<!--you can write to script here to change the height at least
<script>
	divheight = getWindowHeight();
</script>
-->

<div id="divClientList" class="offset1" style="width:90%; height:100%; top:100px; position:absolute;">
	<div id="ul_AllClients" class="fluid">
		
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	var loading = "<img src=\"images/preloader-w8-cycle-black.gif\">";
	
	$("#btnShowAllClients").click(function(){
		$("#ul_AllClients").html(loading);
		$(currentDiv).fadeOut(200,function(){
			$("#divShowAllClients").fadeIn(500,function(){
				getClientsAsync("functional/getClients.php",0,17);
			});
			currentDiv = "#divShowAllClients";
			//showAllClients();
		});
	});
	
	$("#txtSearchClient").keyup(function (){
		$("#ul_AllClients").html(loading);
		getClientsAsync("functional/searchClient.php",0,17);
	});
	
	function getClientsAsync(url,start,end){
		if (start > 0){
			$("#ul_AllClients").html(loading);
		}
		$.post(url,
		{cliName:$("#txtSearchClient").val(),
		 start:start,
		 end:end},
		function(responseText){
			var reply = responseText;
			try{
				var replyJSON = JSON.parse(reply);
				//console.log(replyJSON.clients[0]);
				var bufferClients ="";
				if (start > 0){
					bufferClients += "<div id='previousClientList' class='tile bg-color-blueDark'>"+
					"<div class='tile-content'><h2><< PREVIOUS CLIENTS</h2></div></div>";
				}
				
				for (var i=0;i<replyJSON.clients.length;i++){
									//show clients here
					bufferClients += "<div id = '"+replyJSON.clients[i].clientId+"' class='clientsdiv tile bg-color-pinkDark' style='display:none;' onClick = 'ClientClicked(this)'>"+
					"<div class='tile-content'>"+
					"<img src='images/clientImgs/defaultPic.jpg' style='width:60px; height:60px'>"+
					"<p>"+replyJSON.clients[i].name+"</p>"+
					"</div>"+
					"<div class='brand'>"+
						"<div class='name'>"+replyJSON.clients[i].folderNo+"</div>"+
					"</div>"+
					"</div>";
				}
				
				if ($("#txtSearchClient").val().length == 0){
					bufferClients += "<div id='nextClientList' class='tile bg-color-blueDark'>"+
					"<div class='tile-content'><h2>MORE CLIENTS >></h2></div></div>";
				}
				
			}catch(e){
				bufferClients = "<div id='goBack' class='tile double bg-color-red'>"+
					"<div class='tile-content'><h3>No Clients found!</h3><p class='fg-color-yellow'>Click here to Show All clients</p></div></div>";
			}
			$("#ul_AllClients").html(bufferClients);
			
			$(".clientsdiv").each(function(){
				try{
					$(this).show(500);
				}catch(e){
				}
			});
			
			try{
				$("#nextClientList").click(function(){
					getClientsAsync("functional/getClients.php",end,(end+17));
				});
				$("#previousClientList").click(function(){
					getClientsAsync("functional/getClients.php",(start-17),(end-17));
				});
				
				$("#goBack").click(function(){
					getClientsAsync("functional/getClients.php",0,17);
				});
			}catch(e){
			}
		});
	}
	
	
});

	var previousClientDiv;
	var previousHTML;
	function ClientClicked(clientDivObj){
		
		try{
			if (previousClientDiv == clientDivObj){
				
			}else{
				getDetails(clientDivObj);
			}
		}catch(e){
			getDetails(clientDivObj);
		}
	}
	
	function getDetails(clientDivObj){
		try{
				
			$.get("functional/getClientDetail.php",
			{cliId:$(clientDivObj).attr('id')},
			function(responseText){
				var reply = responseText;
				var replyJSON = JSON.parse(reply);
				
				var buffClientDetail = "";
			
				buffClientDetail += "<div class='tile-content'>"+
									"<div class='tool-bar place-right' data-role='button-set'>"+
									"<button id='msg_client' class='tool-button shortcut' style='width:32px;height:32px;'><i class='icon-mail' style='color:#202020;'></i></button>"+
									"<button id='edit_client' class='tool-button shortcut' style='width:32px;height:32px;'><i class='icon-pencil' style='color:#202020;'></i></button>"+
									"<button id='del_client' class='tool-button shortcut' style='width:32px;height:32px;'><i class='icon-remove' style='color:#202020;'></i></button>"+
									"</div>"+
									"<img src='images/clientImgs/defaultPic.jpg' style='width:80px; height:80px'>"+
									"<p>Name: "+replyJSON.c_detail[0].c_name+"</p>"+//you can do shortcut if statement here!
									"<p>Address: "+replyJSON.c_detail[0].address+"</p>"+
									"<p>Phone: "+replyJSON.c_detail[0].phone+"</p>"+
									"<p>otherPhone: "+replyJSON.c_detail[0].otherPhone+"</p>"+
									"<p>email: "+replyJSON.c_detail[0].email+"</p>"+
									"<div class='place-right'>"+
									"<h3><u><a id='"+replyJSON.c_detail[0].client_id+"' class='petLists'>Pets</a></u></h3>";
									
									if (replyJSON.c_detail[0].petName != ""){//client has no pet...
										for(var i=0;i<replyJSON.c_detail.length;i++){
											buffClientDetail += "<p><a id='"+replyJSON.c_detail[i].pet_id+"' class='petLink'>Pet Name: "+replyJSON.c_detail[i].petName+" ("+	replyJSON.c_detail[i].animal+")</a></p>";
										}
									}
									
									buffClientDetail += "<a id='"+replyJSON.c_detail[0].client_id+"'class='addMorePets'>Click here to add more Pets</a>"
									buffClientDetail += "</div>"+
									"</div>"+
									"<div class='brand'>"+
									"<div class='name'>Folder Number: "+replyJSON.c_detail[0].folderNo+"</div>"+
									"</div>";
					
				
				
				displayDetails(clientDivObj,buffClientDetail);
				
				$("a[class=petLink]").click(function(){
					var url = "functional/getPetById.php";
					changeCurrentDiv(url,this.id);
				});	
				
				$("a[class=petLists]").click(function(){
					var url = "functional/getPetsByClientId.php"
					changeCurrentDiv(url,this.id);
				});
				
				$("a[class=addMorePets]").click(function(){
					var url = "functional/getClientById.php";
					changeCurrentDiv(url,this.id);
				});
				
				
				$("#msg_client").click(function(){
					
					var sendClientMsg = "<div id='msgDiv' style='display:none;'><h3 class='padding20'><i class='icon-mail' style='font-size:40px'></i>Message(SMS) To: "+replyJSON.c_detail[0].c_name+"</h3>"+
					"<div class='input-control textarea'>"+
					"<textArea id='msgContent' class='fg-color-darken' style='width:300px;height:150px;left:60px' placeholder='Message Goes Here'></textArea>"+
					"</div>"+
					"<div class='place-right'><button id='send' class='bg-color-pinkDark'>Send</button><button id='cancel' class='bg-color-pinkDark'>Cancel</button></div></div>";
					
					$(clientDivObj).html(sendClientMsg);
					$(clientDivObj).removeClass('bg-color-pinkDark',function(){
						$(clientDivObj).addClass('bg-color-blue',200,'easeInOutQuad',function(){
							$("#msgDiv").fadeIn(350);
						});
					});
					
					
					$("#send").click(function(){
						if (replyJSON.c_detail[0].phone == ''){
							displayError('Client Has no phone number to send to.','No phone Number');
						}else{
							if ($("#msgContent").val() == ''){
								displayError('There is message to send. Make sure to type in something<br />to send to the intended client!','No Message');
							}else{
								url = "functional/sendMsg.php";
								$.post(url,{msg:$("#msgContent").val(),
											recipient:replyJSON.c_detail[0].phone},
											function(responseText){
												var reply = responseText;
												console.log(reply);
												if (reply == 'success'){
													displayNotice("Message has been sent to the intended client","Message sent successfully");
												}
												getDetails(clientDivObj);
											});
							}
						}
					});//do the sending here!
					
					$("#cancel").click(function(){
						getDetails(clientDivObj);
					});
				});
				
				$("#edit_client").click(function(){
					
					var editClientPage="<div id='editClientDiv' style='display:none'><div class='place-right'><img src='images/clientImgs/defaultPic.jpg' style='width:60px; height:60px;'></div><div id='err'></div>"+
					//upload button was here som
					
					"<table style='border:none;'><tr><td style='border:none;'>Name:</td><td style='border:none;'><input id ='c_name' type='text' class='fg-color-darken' value='"+replyJSON.c_detail[0].c_name+"'></td></tr>"+
					
					"<tr><td style='border:none;'>Address:</td><td style='border:none;'><input id='address' type='text' class='fg-color-darken' value='"+replyJSON.c_detail[0].address+"'></td></tr>"+
					
					"<tr><td style='border:none;'>Phone:</td><td style='border:none;'><input id='phone' type='text' class='fg-color-darken' value='"+replyJSON.c_detail[0].phone+"'></td></tr>"+
					
					"<tr><td style='border:none;'>Otherphone:</td><td style='border:none;'><input id='otherPhone' type='text' class='fg-color-darken' value='"+replyJSON.c_detail[0].otherPhone+"'></td></tr>"+
					
					"<tr><td style='border:none;'>Email:</td><td style='border:none;'><input id='email' type='text' class='fg-color-darken' value='"+replyJSON.c_detail[0].email+"'></td></tr>"+
					
					"<tr><td style='border:none;'>folderNo:</td><td style='border:none;'><input id='folderNo' type='text' class='fg-color-darken' value='"+replyJSON.c_detail[0].folderNo+"'></td></tr>"+
					"</table>"+
					"<div class='place-right'>"+
					"<button id='saveClientUpdate' class= 'bg-color-pinkDark'>Save</button><button id='cancelClientUpdate' class='bg-color-pinkDark'>Cancel</button></div></div>";
										
					$(clientDivObj).html(editClientPage);
					$(clientDivObj).removeClass('bg-color-pinkDark',function(){
						$(clientDivObj).addClass('bg-color-purple',200,'easeInOutQuad',function(){
							$("#editClientDiv").fadeIn(350);
						});
					});
					
					
					$("#saveClientUpdate").click(function(){
						var clientName = $("#c_name").val();
						var clientAddress = $("#address").val();
						var phone = $("#phone").val();
						var otherPhone = $("#otherPhone").val();
						var email = $("#email").val();
						var folderNo = $("#folderNo").val();
						
						if ((folderNo == "")||(clientName == "")){
							$("#err").html("Name or folderNo cannot be empty");
						}else{
							var url = "functional/updateClient.php";
							$.post(url,{clientId:replyJSON.c_detail[0].client_id,
										name:clientName,
										address:clientAddress,
										phone:phone,
										otherPhone:otherPhone,
										email:email,
										folderNo:folderNo},
										
										function(responseText){
											var reply = responseText;
											displayNotice(reply,'confirmation');
											
											$(clientDivObj).removeClass('bg-color-purple');
											$(clientDivObj).addClass('bg-color-pinkDark',300,'easeInOutQuad');
						
											getDetails(clientDivObj);
										});
						}
					});
					
					$("#cancelClientUpdate").click(function(){
						getDetails(clientDivObj);
					});
					
				});
				
				$("#del_client").click(function(){
					
					var confirmDelete = "<div id='del_clientDiv' style='display:none;'><i class='icon-help' style='font-size:60px;color:white;margin-left:40%;margin-top:20px;'></i><h2 class='padding20'>Are You Sure You Want to DELETE Client <strong>"+replyJSON.c_detail[0].c_name+"</strong> along with his/her pets.</h2>"+
					"<div class='place-right'>"+
					"<button id='yes' class='shortcut fg-color-darken' style='width:64px; height:32px;'>YES</button><button id='no' class='shortcut fg-color-darken' style='width:64px; height:32px;'>NO</button>"+
					"</div></div>";
					
					$(clientDivObj).html(confirmDelete);
					$(clientDivObj).removeClass('bg-color-pinkDark',function(){
						$(clientDivObj).addClass('bg-color-red',200,'easeInOutQuad',function(){
							$("#del_clientDiv").fadeIn(350);
						});
					});
					
					
					$("#yes").click(function(){
						deleteClient(replyJSON.c_detail[0].client_id,replyJSON.c_detail[0].c_name,clientDivObj);
					});
					$("#no").click(function(){
						getDetails(clientDivObj);
					});
					
				});
			});
			
			if (previousClientDiv != clientDivObj)
				$(previousClientDiv).removeClass('triple double-vertical',200,'easeInOutQuad');
			
			$(previousClientDiv).removeClass('bg-color-red');
			$(previousClientDiv).removeClass('bg-color-purple');
			$(previousClientDiv).removeClass('bg-color-blue');
			$(previousClientDiv).addClass('bg-color-pinkDark');
			
			$(previousClientDiv).html(previousHTML);
			
			$(clientDivObj).addClass('bg-color-pinkDark triple double-vertical',200,'easeInOutQuad');
			
			previousHTML = $(clientDivObj).html();
			
			var loading = "<img src=\"images/preloader-w8-cycle-black.gif\">";
			$(clientDivObj).html(loading);
				
		}catch(e){
			//$(clientDivObj).removeClass('bg-color-pinkDark');
			$(clientDivObj).addClass('triple double-vertical',200,'easeInOutQuad');
						
		}finally{
			previousClientDiv = clientDivObj;	
		}
		
	}
	
	function changeToClientDiv(url,id){
		var loading = "<img src=\"images/preloader-w8-cycle-black.gif\">";
		$(currentDiv).fadeOut(200,function(){
			$("#ul_AllClients").html(loading);
			$("#divShowAllClients").fadeIn(500,function(){
				currentDiv = "#divShowAllClients";
				findClient(url,id);
			});
		});
	}
	
	function findClient(url,id){
		$.get(url,
			 {id:id},
			 function(responseText){
				//console.log(url+" "+id);
				var reply = responseText;
				var bufferClient = "";
				try{
					var replyJSON = JSON.parse(reply);
					
					bufferClient += "<div id = '"+replyJSON.cliId+"' class='clientsdiv tile bg-color-pinkDark' style='display:none;' onClick = 'ClientClicked(this)'>"+
					"<div class='tile-content'>"+
					"<img src='images/clientImgs/defaultPic.jpg' style='width:60px; height:60px'>"+
					"<p>"+replyJSON.cliName+"</p>"+
					"</div>"+
					"<div class='brand'>"+
						"<div class='name'>"+replyJSON.folderNum+"</div>"+
					"</div>"+
					"</div>";
					
					$("#ul_AllClients").html(bufferClient);
					$(".clientsdiv").show(500);
					
				}catch(e){
					console.log("uhhu");
				}
			 })
	}
	
	function displayDetails(clientDivObj,buffClientDetail){
		$(clientDivObj).html(buffClientDetail);
	}
	
	function deleteClient(client_id,cli_name,clientDivObj){
		//console.log(client_id);
		var url = "functional/deleteClient.php";
		$.post(url,
		{cliId:client_id,
		 c_name:cli_name},
		function(responseText){
			var reply = responseText;
			displayNotice(reply,'Client Delete Successful');
			$(clientDivObj).hide();
		});
	}
	
</script>