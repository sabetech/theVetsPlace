<!--HomePage(Overview)-->
							<div class="span4 "><!--Recent Activities-->
						<h2>Recent Activities</h2>
						<div class='input-control text'>
							<input id='searchActivity' type='text' placeholder='Search by Username or Date(YY-MM-DD)'/>
							<button class='btn-clear'></button>
						</div>
						<ul id='recentActivities' class="listview" style='position:absolute;height:408px;overflow-x:hidden;overflow-y:scroll;'>
								
						</ul>
					</div>
					
						<div class="span4" style='margin-left:1em;'>
								<h2>Notices</h2>
							<ul id='periodicNotices' class="listview" style='position:absolute;height:450px;overflow-x:hidden;overflow-y:scroll;'>
								
								
								<!--li class="bg-color-orange fg-color-white">
									<div class="icon">
										<img src="images/5.jpg">
									</div>
									<div class="data">
										<h4 class='fg-color-white'>Vaccination Due</h4>
										<p>
											Pet: Flaxin <br />
											Date:  <br />
											Owner: <br />
										</p>
									</div>
								</li-->
							</ul>
						</div>
						
					<div class="span4" style='margin-left:1em;'><!--Summary-->
							<h2>Summary</h2>
							
							<ul class="listview fluid">
								
								<li class="bg-color-white">
									<div class="icon">
										<img src="images/5.jpg">
									</div>
									<div class="data">
										<h4>General Information</h4>
										<p id='summaryInfo'>
											<!--Clients : 1890
											Pet Population: 2349 <br />
											Dogs: 1269 <br />
											Cats: 1080 <br />
											Horses: 3 <br />
											Parrots: 12 <br /-->
										</p>
									</div>
								</li>
								
							</ul>
					</div>

<script type='text/javascript'>
$(document).ready(function(){
	
	initializeOverviewInfo();
	$("#overview").click(function(){
		initializeOverviewInfo();
	});
	
	function initializeOverviewInfo(){
		$.get("functional/getOverviewInfo.php",
			function(responseText){
				var reply = responseText;
				try{
					var replyJSON = JSON.parse(reply);
					var bufferSummary = "Clients : "+replyJSON.SUMMARY[0].clientNo+"<br />"+
										"Dogs : "+replyJSON.SUMMARY[0].Dogs+"<br />"+
										"Cats : "+replyJSON.SUMMARY[0].Cats+"<br />"+
										"Others : "+replyJSON.SUMMARY[0].others+"<br />";
										
										var totalPets = parseInt(replyJSON.SUMMARY[0].Dogs)+parseInt(replyJSON.SUMMARY[0].Cats)+parseInt(replyJSON.SUMMARY[0].others);
										
						bufferSummary += "Total Pets :"+totalPets;
										
					$("#summaryInfo").html(bufferSummary);
				}catch(e){
					$("#summaryInfo").html("Error while loading data!");
				}
			});
			
		initVaccinationNotice();
		//initDewormingNotice();
		//checkStockTaken();
		
		//get recent activities
		 var today = new Date();
		 getActivities(today.getUTCFullYear()+"-"+(today.getMonth()+1)+"-"+today.getDate());
		 
		 $("#searchActivity").keyup(function(){
			getActivities($(this).val());
		 });
	}
	
	
	
	function getActivities(whichOnes){
		$.get("functional/getRecentActivities.php",
			 {searchActvs:whichOnes},
			  function(responseText){
				var reply = responseText;
				var bufferActivitiesHTML = "";
				try{
					var replyJSON = JSON.parse(reply);
					
					for (var i=0;i<replyJSON.Activities.length;i++){
						bufferActivitiesHTML += "<li class = 'bg-color-blueLight'>"+
													"<div class='icon'>"+
													"<i class='icon-history'></i>"+
													"</div>"+
													"<div class='data'>"+
													"<h4>"+replyJSON.Activities[i].date+"</h4>"+
													"<p>"+replyJSON.Activities[i].user+" "+replyJSON.Activities[i].details+"</p>"+
													"</div>"+
													"</li>";
					}
					$("#recentActivities").html(bufferActivitiesHTML);
				}catch(e){
					bufferActivitiesHTML += "<li class = 'bg-color-red'>"+
													"<div class='icon'>"+
													"<i class='icon-history'></i>"+
													"</div>"+
													"<div class='data'>"+
													"<h4 class='fg-color-yellow'>No Activity Today!</h4>"+
													"<p class='fg-color-yellow'>There are no activities recorded for today!</p>"+
													"</div>"+
													"</li>";
					$("#recentActivities").html(bufferActivitiesHTML);
				}
			  });
	}
});

function initVaccinationNotice(){
		var dueDate = new Date();
		
		var theDueDate = dueDate.getFullYear()+"-"+(dueDate.getMonth()+1)+"-"+dueDate.getDate();
			$("#periodicNotices").html("");
			//get vaccination notice
			$.get("functional/getVaccinationNotice.php",
				 {dueDate:theDueDate},
				 function (responseText){
					var reply = responseText;
					try{
						var replyJSON = JSON.parse(reply);
						var bufferVaccinationDueHTML= "";
						for (var i=0;i<replyJSON.Notices.length;i++){
							var petName = replyJSON.Notices[i].petName;
							var clientName = replyJSON.Notices[i].clientName;
							var description = replyJSON.Notices[i].description;
							var vacId = replyJSON.Notices[i].vacId;
							var clientPhone = replyJSON.Notices[i].clientPhone;
							
							bufferVaccinationDueHTML += "<li class='notifyClient bg-color-red fg-color-white' style='display:none;' onClick='vaccNoticeClicked(\""+petName+"\",\""+clientName+"\",\""+description+"\",\""+vacId+"\",\""+clientPhone+"\",this)'>"+
							
														"<div class='icon'>"+
														"<i class='icon-warning'></i>"+
														"</div>"+
														"<div class='data'>"+
														"<h4 class='fg-color-white'>Vaccination Due</h4>"+
														"<p>"+
														"Pet:"+replyJSON.Notices[i].petName+"<br />"+
														"Descrition:"+replyJSON.Notices[i].description+"<br />"+
														"Owner:"+replyJSON.Notices[i].clientName+"<br />"+
														"</p>"+
														"</div>"+
														"</li>";
							
						}
						
						$("#periodicNotices").append(bufferVaccinationDueHTML);
						$(".notifyClient").fadeIn(250);
							/*$(".notifyClient").click(function(){*/
							
					}catch(e){
						var replyJSON = JSON.parse(reply);
						bufferVaccinationDueHTML += "<li class='bg-color-green fg-color-white'>"+
														"<div class='icon'>"+
														"<i class='icon-thumbs-up'></i>"+
														"</div>"+
														"<div class='data'>"+
														"<h4 class='fg-color-white'>"+replyJSON.Message+"</h4>"+
														"<p>"+"There are no vaccinations due today for any of the pets"+
														"</p>"+
														"</div>"+
														"</li>";
														
						$("#periodicNotices").append(bufferVaccinationDueHTML);
						
					}
				 });
			
	
			 //get Deworming Notice
			$.get("functional/getDewormingNotice.php",
			 {dueDate:theDueDate},
			 function (responseText){
				var reply = responseText;
				try{
					var replyJSON = JSON.parse(reply);
					var bufferDewormingDueHTML= "";
					for (var i=0;i<replyJSON.Notices.length;i++){
						var petName = replyJSON.Notices[i].petName;
						var clientName = replyJSON.Notices[i].clientName;
						var description = replyJSON.Notices[i].description;
						var vacId = replyJSON.Notices[i].vacId;
						var clientPhone = replyJSON.Notices[i].clientPhone;
						
						bufferDewormingDueHTML += "<li class='notifyDeworm bg-color-red fg-color-white' style='display:none;' onClick='dewormNoticeClicked(\""+petName+"\",\""+clientName+"\",\""+description+"\",\""+vacId+"\",\""+clientPhone+"\",this)'>"+
													"<div class='icon' >"+
													"<i class='icon-warning'></i>"+
													"</div>"+
													"<div class='data'>"+
													"<h4 class='fg-color-white'>Deworming Due</h4>"+
													"<p>"+
													"Pet:"+replyJSON.Notices[i].petName+"<br />"+
													"Description:"+replyJSON.Notices[i].description+"<br />"+
													"Owner:"+replyJSON.Notices[i].clientName+"<br />"+
													"</p>"+
													"</div>"+
													"</li>";
						
					}
					
					$("#periodicNotices").append(bufferDewormingDueHTML);
					$(".notifyDeworm").fadeIn(250);
				}catch(e){
					var replyJSON = JSON.parse(reply);
					bufferDewormingDueHTML += "<li class='bg-color-green fg-color-white'>"+
													"<div class='icon'>"+
													"<i class='icon-thumbs-up'></i>"+
													"</div>"+
													"<div class='data'>"+
													"<h4 class='fg-color-white'>"+replyJSON.Message+"</h4>"+
													"<p>"+"There are no vaccinations due today for any of the pets"+
													"</p>"+
													"</div>"+
													"</li>";
					$("#periodicNotices").append(bufferDewormingDueHTML);
				}
			 });
		
		//check if stock has been taken
		
			$.get("functional/checkIfStockTaken.php",
				{date:theDueDate},
				function (responseText){
					var bufferStockTakenHTML = "";
					if (responseText == 'true'){
						bufferStockTakenHTML += "<li class='stockNotTaken bg-color-green fg-color-white'>"+
														"<div class='icon'>"+
														"<i class='icon-thumbs-up'></i>"+
														"</div>"+
														"<div class='data'>"+
														"<h4 class='fg-color-white'>Stock Taken Today</h4>"+
														"<p>"+"Stock Has been Taken Today.Quantities of Items are up to date."+
														"</p>"+
														"</div>"+
														"</li>";	
					}else{
						bufferStockTakenHTML += "<li id='noteTakeStock' class='stockNotTaken bg-color-red fg-color-white'>"+
														"<div class='icon'>"+
														"<i class='icon-warning'></i>"+
														"</div>"+
														"<div class='data'>"+
														"<h4 class='fg-color-white'>Stock Not Taken Today!</h4>"+
														"<p>"+"Hi, remember to take stock of the items in the inventory today!"+
														"</p>"+"<p></p>"+
														"<p>"+"CLICK HERE to TAKE STOCK Now!"+
														"</p>"+
														"</div>"+
														"</li>";	
					}
					
					$("#periodicNotices").append(bufferStockTakenHTML);
					$(".stockNotTaken").fadeIn(250);
					
					$("#noteTakeStock").click(function(){
						$("#globalHeader").html("Inventory<small>.</small>");
						$(currentLi).removeClass(stickerClass);
						$("#li_Inventory").addClass(stickerClass);
						currentLi = "#li_Inventory";
						
						$(currentDiv).fadeOut(200,function(){
							$("#divTakeStock").fadeIn(500);
							currentDiv = "#divTakeStock";
							var url = "functional/getCurrentItems.php";
							$("#stockItems").html(loading);
							loadStockItemsAsync(url);
						});
					});
				});
		}

function vaccNoticeClicked(petName,clientName,description,vacId,clientPhone,listObj){
	
	var tempHTML = $(listObj).html();
	
	var msgHTML = "To: "+clientName+"<div class='input-control textarea'> <textArea id='notifyMsg'>Hi, you pet "+petName+" is due for "+description+" Vaccination !</textArea></div><div class='place-right'><button id='sendNotify'>Send</button><button id='cancelNotify'>Cancel</button></div>";
	
	$(listObj).html(msgHTML);
	
	$("#cancelNotify").click(function(){
		initVaccinationNotice();
	});
	
	$("#sendNotify").click(function(){
		if ($("#notifyMsg").val() == ""){
			displayError('There is message to send. Make sure to type in something<br />to send to the intended client!','No Message');
		}else{
			url = "functional/sendMsg.php";
			$.post(url,{msg:$("#notifyMsg").val(),
						recipient:clientPhone},
						function(responseText){
							var reply = responseText;
							if (reply == 'success'){
								displayNotice("Message has been sent to the intended client","Message sent successfully");
							}
							initVaccinationNotice();
						}
				   );
		}
			
	});	
	
}

function dewormNoticeClicked(petName,clientName,description,vacId,clientPhone,listObj){
	var tempHTML = $(listObj).html();
	
	var msgHTML = "To: "+clientName+"<div class='input-control textarea'><textArea id='notifyMsg'>Hi, you pet "+petName+" is due for "+description+" Deworming  !</textArea></div><div class='place-right'><button id='sendNotifyDworm'>Send</button><button id='cancelNotifyDworm'>Cancel</button></div>";
	
	$(listObj).html(msgHTML);
	
	$("#cancelNotifyDworm").click(function(){
		initVaccinationNotice();
	})
	
	$("#sendNotifyDworm").click(function(){
		if ($("#notifyMsg").val() == ""){
			displayError('There is message to send. Make sure to type in something<br />to send to the intended client!','No Message');
		}else{
			url = "functional/sendMsg.php";
			$.post(url,{msg:$("#notifyMsg").val(),
						recipient:clientPhone},
						function(responseText){
							var reply = responseText;
							if (reply == 'success'){
								displayNotice("Message has been sent to the intended client","Message sent successfully");
							}
							initVaccinationNotice();
						}
				   );
		}
	});
}
</script>