<div>
	<div class="bg-color-orange offset1" style="padding:5px">
		<h3 class="fg-color-white">Items In the Database</h3>
		
	</div>
	
	<div class="span3" style="margin-top:2em;">
		<div id='addNewDBItem' class="mgt bg-color-red fg-color-white padding10" style="margin-top:1em; cursor:pointer;">Add New Item</div>
		
		<div id='delDBItem' class="mgt bg-color-red fg-color-yellow padding10" style="margin-top:1em;cursor:pointer;">Delete An Item</div>
		
		<div id='changeDBItemName' class="mgt bg-color-red fg-color-yellow padding10" style="margin-top:1em;cursor:pointer;">Change An Item Name</div>
	</div>
		
		
	<!-- ADD ITEM TO DB -->
	<div id="addDbNewItem" class="span4" style="margin-top:2em;display:block;">
		<div class="bg-color-red fg-color-white padding10" style="margin-top:1em;">Add New Item</div>
		<div class="hero-unit">
			<div style="margin-top:1em;"><label>New Item Name:</label></div>
			<div class="input-control text">
				<input id="newDBItemName" type="text"/>
				<button class="btn-clear"></button>
			</div>
					
			<label>Item Type:</label><!--This place should be an option box loadlist from DB-->
				<div class="input-control select">
					<select id='itemTypes'>
						
					</select>
				</div>
			<button id='mgtSaveDBNewItem' class="bg-color-orange fg-color-white place-right">Save</button>
		</div>
	</div>
		
	<!-- DELETE ITEM FROM DB -->
	<div id="deleteDbItem" class="span4" style="margin-top:2em;display:none">
		<div class="bg-color-red fg-color-white padding10" style="margin-top:1em;">Delete An Item</div>
		
		<div id ='mgtDelDiv' class="hero-unit">
			<div style="margin-top:1em;"><label>Search For Item:</label><label id="lblDelItemName"></label></div>
			<div class="input-control text">
				<input id="searchDelItemtext" type="text"/>
				<button class="btn-clear"></button>
			</div>
						
			<button id="mgtdeleteDBItem" class="bg-color-orange fg-color-white place-right">Delete</button>
		</div>
	</div>
	
	<!-- UPDATE ITEM TO DB -->
	<div id="updateDbItem" class="span4" style="margin-top:2em;display:none">
		<div class="bg-color-red fg-color-white padding10" style="margin-top:1em;">Change An Item Name</div>
		
		<div id ='uptDiv' class="hero-unit">
			<div style="margin-top:1em;"><label>Search For Item:</label><label id="lblUptItemName"></label></div>
			<div class="input-control text">
				<input id="searchUptItemtext" type="text"/>
				<button class="btn-clear"></button>
			</div>
					
			<label>New Name:</label><!--This place should be an option box loadlist from DB-->
				<div class="input-control text">
					<input id="newItemtext" type="text"/>
					<button class="btn-clear"></button>
				</div>
				
			<button id="btnUptSave" class="bg-color-orange fg-color-white place-right">Save</button>
		</div>
	</div>
		
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var currentMgtDiv = "#addDbNewItem";
		var currentDelItem;
		var currentUptItem;
		
		loadItemTypeAsync();
		
		function loadItemTypeAsync(){
			$.get("functional/getItemCategories.php",
				function(responseText){
					$("#itemTypes").html("");
					var reply = responseText;
					try{
						var replyJSON = JSON.parse(reply);
						
						for (var i=0;i<replyJSON.itemCategories.length;i++){
							$("#itemTypes").append(new Option(replyJSON.itemCategories[i].itemType,replyJSON.itemCategories[i].itemType));
						}
					}catch(e){
						
					}
				}
			)
		}
		
		$("#addNewDBItem").click(function(){
			$(".mgt").removeClass("fg-color-white");
			$(".mgt").addClass("fg-color-yellow");
			$(this).removeClass("fg-color-yellow");
			$(this).addClass("fg-color-white");
			
			loadItemTypeAsync();
			
			$(currentMgtDiv).fadeOut(150,function(){
				$("#addDbNewItem").fadeIn(200);
				currentMgtDiv = "#addDbNewItem";
				
				
			});	
			
		});
		
		$("#mgtSaveDBNewItem").click(function(){
			if ($("#newDBItemName").val().length == 0){
				displayError("The name of the field is Empty! Make sure you type<br />the name of the new item In there!","Empty Field Error!");
			}else{
				$.post("functional/saveDBNewItem.php",
					  {itemName:($("#newDBItemName").val()),
					   itemType:($("#itemTypes")).val()},
					  function(responseText){
						var reply = responseText;
						try{
							var replyJSON = JSON.parse(reply);
							if (replyJSON.reply == 'SUCCESS'){
								displayNotice("The new Item "+$("#newDBItemName").val()+" has been added to the database.<br /> Now you will see this item as part of the items to take stock for!","New item Added successfully");
							}else{
								displayError("New item could not be saved because, the item<br />you are trying to save might already exist in the database!","New Item could not be added");
							}
							
						}catch(e){
						
						}
					  });
			}	
		});
	
		$("#delDBItem").click(function(){
			$(".mgt").removeClass("fg-color-white");
			$(".mgt").addClass("fg-color-yellow");
			
			$(this).removeClass("fg-color-yellow");
			$(this).addClass("fg-color-white");
			
			$(currentMgtDiv).fadeOut(150,function(){
				$("#deleteDbItem").fadeIn(200);
				currentMgtDiv = "#deleteDbItem";
				delInit();
			});
		});
		
		function delInit(){
			$("#lblDelItemName").html("");
			$("#searchDelItemtext").keyup(function(){
				var replyJSON;
				$.get("functional/searchItem.php",
					 {itemName:$("#searchDelItemtext").val()},
					 function(responseText){
						var reply = responseText;
						try{
							replyJSON = JSON.parse(reply);
							currentDelItem = replyJSON.item[0];
							$("#lblDelItemName").html(currentDelItem.itemName);
							if ($("#searchDelItemtext").val().length == 0){
								$("#lblDelItemName").html("");
							}
							//console.log(reply);	
						}catch(e){
						}
					 });
			});
			
			$("#mgtdeleteDBItem").click(function(){
				if ($("#searchDelItemtext").val().length == 0){
					displayError("The name of the field is Empty! Make sure you type<br />the name of the new item In there!","Empty Field Error!");
				}
			});
			
			
			$("#mgtdeleteDBItem").click(function(){
							
				if ($("#searchDelItemtext").val().length == 0){
					displayError("The name of the field is Empty! Make sure you type<br />the name of the new item In there!","Empty Field Error!");
				}else{	
					var tempHtml = $("#mgtDelDiv").html();
					
					var confirmHTML = "<p class='fg-color-red'>Are You sure You want to delete "+currentDelItem.itemName+"</p>";
					confirmHTML += "<button id='nodelete'>NO</button><button id='yesdelete'>YES</button>";
					
					$("#mgtDelDiv").html(confirmHTML);
					
					$("#nodelete").click(function(){
						$("#mgtDelDiv").html(tempHtml);
						delInit();
					});
					
					$("#yesdelete").click(function(){
						$.post("functional/delDBItem.php",
						  {itemId:replyJSON.item[0].itemId},
						  function(responseText){
							try{
								var JSONresponse = JSON.parse(responseText);
								if (JSONresponse.reply == 'SUCCESS'){
									displayNotice("The Item "+replyJSON.item[0].itemName+" was deleted successfully","item Deleted successfully");
								}else{
									displayError("Couldn't delete successfully.<br />Try restarting the sql server and the apache server from the services window","An unexpected error occured!");
								}
							}catch(e){
							
							}
							$("#mgtDelDiv").html(tempHtml);
							 delInit();
						  });
					});
				}
			});
		}
		

		$("#changeDBItemName").click(function(){
			$(".mgt").removeClass("fg-color-white");
			$(".mgt").addClass("fg-color-yellow");
			
			$(this).removeClass("fg-color-yellow");
			$(this).addClass("fg-color-white");
			
			$(currentMgtDiv).fadeOut(150,function(){
				$("#updateDbItem").fadeIn(200)
				currentMgtDiv = "#updateDbItem";
			});
			uptInit();
		});
		
		function uptInit(){
			$("#lblUptItemName").html("");
			$("#searchUptItemtext").keyup(function(){
				var replyJSON;
				$.get("functional/searchItem.php",
					 {itemName:$("#searchUptItemtext").val()},
					 function(responseText){
						var reply = responseText;
						try{
							replyJSON = JSON.parse(reply);
							currentUptItem = replyJSON.item[0];
							$("#lblUptItemName").html(currentUptItem.itemName);
							if ($("#searchUptItemtext").val().length == 0){
								$("#lblUptItemName").html("");
							}
							
						}catch(e){
							
						}
					 });
			});
			
			$("#btnUptSave").click(function(){
				if (($("#searchUptItemtext").val().length == 0)||($("#newItemtext").val().length == 0)){
					displayError("The content of the fields are is empty!","Empty Field Error");
				}else{
					var tempUptHtml = $("#uptDiv").html();
					
					var confirmHTML = "<p class='fg-color-red'>Are You sure You want to change the name "+currentUptItem.itemName+" to "+$("#newItemtext").val()+"?</p>";
					confirmHTML += "<button id='noUpt'>NO</button><button id='yesUpt'>YES</button>";
					
					var newItem = $("#newItemtext").val();
					
					$("#uptDiv").html(confirmHTML);
					
					$("#noUpt").click(function(){
						$("#uptDiv").html(tempUptHtml);
						uptInit();
					});
					
					$("#yesUpt").click(function(){
						console.log(newItem+" what is this");
						$.post("functional/updateItemDetail.php",
							    {itemId:currentUptItem.itemId,
								 itemName:newItem},
								function(responseText){
									var reply = responseText;
									try{
										var replyJSON = JSON.parse(reply);
										if (replyJSON.reply == "SUCCESS"){
											displayNotice("The name of the item "+currentUptItem.itemName+" has been changed to "+newItem+".","Name Changed Successfully");
										}else{
											displayError("Something went wrong with the name change!","Name change Failed");
										}
									}catch(e){
										displayError("An Error occured!","Error");
									}
									$("#lblUptItemName").html("");
									$("#uptDiv").html(tempUptHtml);
									uptInit();
								});	
					});
				}
			});
		}	
	});
</script>