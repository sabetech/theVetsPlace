<div class="span4">
							<h2>SMS to client(s)</h2>
								<label>Recipients goes here!</label>
								<!--div class="input-control text">
										<input type="text" placeholder="Recipients Goes here!"/>
										<button class="btn-clear"></button>
								</div-->
								
								<!--recipients in accordion re enabled is here!!-->
								<div id="divRecipients" style="max-height:10em;overflow-x:hidden;overflow-y:scroll;">
									<ul id="recipientList">
										
									</ul>
								</div>
								
								<label>Message:</label>
								<div class="input-control textarea">
									<textarea id="msgText" placeholder="Write your SMS message here!"></textarea>	
								</div>
								<button id='sendMsg' class="bg-color-green fg-color-white place-right">Send</button>
						</div>
						
						<div class="span4" >
							<h2>Select Recipients</h2>
								<div class="input-control text ">
									<input id="smsSearchClient" type="text" placeholder="Search Clients" />
									<button class="btn-search"></button>
								</div>
								<button id='sendToAll' class="bg-color-blue fg-color-white">Send To All Clients</button>
							<ul id="clients_list" class="listview" style="height:25em;overflow-x:hidden;overflow-y:scroll;">
								<!--li >
									<!--div class="icon">
										<!--img src="images/clientImgs/defaultPic.jpg" width="30" height="30">
									</div>
									<div class="data">
										<h4>Search Results</h4>
										
									</div>
								</li-->
							</ul>
						</div>
						
<script type="text/javascript">
	var allMsgClientsSelected=false;
	var oneMsgClientSelected=false;
	var selectCount = 0;
	$(document).ready(function(){
		var loading = "<img src=\"images/preloader-w8-cycle-black.gif\">";
		
		var url = "functional/searchClient.php";
		$("#smsSearchClient").keyup(function(){
			$("#clients_list").html(loading);
			getRecipientsAsync(url);
		});
		
		function getRecipientsAsync(url){
			$.post(url,
			{cliName:$("#smsSearchClient").val()},
			function(responseText){
				var reply = responseText;
				try{
					var replyJSON = JSON.parse(reply);
					var bufferSearchResult = "";
					for (var i=0;i<replyJSON.clients.length;i++){
						bufferSearchResult += "<li id='"+replyJSON.clients[i].phone+"' class='bg-color-blueLight' style='width:95%;' onclick='recipientClicked(this)'>"+
											"<div class='icon'>"+
											"<img src='images/clientImgs/defaultPic.jpg' width='30';height='30';>"+
											"</div>"+
											"<div class='data'>"+
												"<h4>"+replyJSON.clients[i].name+"</h4>"+
												"<p>Phone: "+replyJSON.clients[i].phone+"</p>"+
												"<p>FolderNo: "+replyJSON.clients[i].folderNo+"</p>"+
												"</div>"+
												"</li>";
					}
					$("#clients_list").html(bufferSearchResult);
					
				}catch(e){
					
				}
			});
		}
		
		$("#sendMsg").click(function(){
			if ($("#msgText").val().length > 0){
				var url = "functional/sendMsg.php";
				if ((oneMsgClientSelected)||(allMsgClientsSelected)){
					var count = 0;
					//console.log("one of them must be true "+oneMsgClientSelected+"<=oneMsg "+allMsgClientsSelected+"<=");
					//console.log(selectCount+" select Count");
					if (allMsgClientsSelected){
						$.post(url,
							  {recipient:'all',
							   msg:$("#msgText").val()},
							   function(responseText){
								var reply = responseText;
								console.log("feededed");
							   });
					}else{
						$(".liNo").each(function(){
							$.post(url,
									{recipient:$(this).find('div').attr('id'),
									msg:$("#msgText").val()},
									function (responseText){
										var reply = responseText;
										console.log("youyou");
										if (reply == 'success'){
											count++;
										}
									}		
								);
							}	
						);
					}
					displayNotice("Message was sent to "+count+" clients","Message Sent Successfully!");
				}else{
					displayError("There are no recipients selected for the message to send to.","Message Recipient Error!");
				}
			}else{
				displayError("There is no message written down to be sent to the selected clients. Please Write some text in the text area.","Message Error!");
			}
		});
		
		$(".liNo").click(function(){
			//console.log("sdfghjfea");
			$(this).remove();
		});	
		
		$("#sendToAll").click(function(){
			$("#recipientList").append("<li id='sendMsgToAll' class='liNo bg-color-green fg-color-white' style='width:90%;margin-top:5px:display:none;cursor:pointer;' onclick='removeRecipient(this)'>SEND TO EVERYBODY</li>");
			
			$("sendMsgToAll").show(100);
			allMsgClientsSelected=true;
			oneMsgClientSelected = false;
		});
	});
	
	function recipientClicked(clickedRecipient){
		//console.log(clickedRecipient.id);
		var recipientHTML = "<li class='liNo' style='width:90%;margin-top:5px;display:none;cursor:pointer;' onclick='removeRecipient(this)'><div id='"+clickedRecipient.id+"' class='bg-color-green fg-color-white'>"+$(clickedRecipient).find('h4').html();+"</div></li>";
		
		selectCount++;
		
		$("#recipientList").append(recipientHTML);
		
		oneMsgClientSelected = true;
		$("#divRecipients").show(500);
		$(".liNo").show(100);
		
	}
	
	function removeRecipient(recipient){
		$(recipient).hide(250,function(){
			$(recipient).remove();
			if (allMsgClientsSelected){
				allMsgClientsSelected=false;
			}else{
				selectCount--;
			}	
			if (selectCount == 0)
				oneMsgClientSelected = false;
			
		});
	
		
	}
	
	
</script>