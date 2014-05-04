<!--put the dynamic size here-->
	<h2>Add New Items</h2>
	<div class="span5">
		<div class="span3">
			<div class="input-control text datepicker" data-role="datepicker">
				<input id="newItemDate" type="text" />
				<button class="btn-date"></button>
			</div>
		</div>
	
		<div class='span5'>
			<div class="padding20 hero-unit">
				<label>New Item Name:</label><label id="lblItemName"></label>
				<div class="input-control text">
					<input id="newItemName" type="text"/>
					<button class="btn-clear"></button>
				</div>
				
				<label>Item Type:</label><!--This place should be an option box loadlist from DB-->
				<div class="input-control text">
					<input id="newItemType" type="text"/>
					<button class="btn-clear"></button>
				</div>
				
				
				<div class="input-control text" style="width:70px;">
					<label>Qty:</label>
					<input id="newItemQty" type="text" placeholder="0"/>
				</div>
				<label id ='newItemErr' class='fg-color-red'></label>
				<input id="saveNewItem" class="place-right bg-color-green" type='submit' value='Save' />
				
			</div>
		</div>
	</div>	
	<div class=" offset1 span4">
		<h2>Added New Items</h2>
		<div id='newItemsList' class='tile-group' style='max-width: 322px;'>
			
		</div>
	</div>

<script type="text/javascript">	
$(document).ready(function(){
	var currentItem;
	$("#newItemName").keyup(function (){
		var url = 'functional/searchItem.php';
		$.get(url,
		{itemName:$("#newItemName").val()},
		function(responseText){
			var reply = responseText;
			try{
				
				var replyJSON = JSON.parse(reply);
				currentItem = replyJSON.item[0];
				var itemname = replyJSON.item[0].itemName;
				var itemtype = replyJSON.item[0].itemType;
				
				var itemNameTxtInput = $("input[id=newItemName]");
				var itemTypeTxtInput = $("input[id=newItemType]");
				
				$("#lblItemName").html(itemname);
				itemTypeTxtInput.val(itemtype);
				
			}catch(e){
				//console.log(e);
			}
		});
	});
	
	$("#newItemName").blur(function (){
		$("input[id=newItemName]").val($("#lblItemName").html());
	});
	
	$("#saveNewItem").click(function (){
		//validate here!
		//console.log(currentItem.itemId);
		
		var itemDate = $("#newItemDate").val();
		var itemQty = $("#newItemQty").val();
		var itemName = $("#newItemName").val();
		var itemType = $("input[id=newItemType]").val();
		
		if ((itemQty == '')||(itemType == '')||(itemName == '')){
			$("#newItemErr").text("Please make sure none of the fields are empty.");
		}else{
			$("#newItemErr").text("");
			var url = "functional/saveNewItem.php";
			
			$.post(url,
				{itemId:currentItem.itemId,
				itemQty:itemQty,
				itemDate:itemDate,
				itemName:itemName},
				function(responseText){
					//push it to the side
					var reply = responseText;
					try{
						var replyJSON = JSON.parse(reply);
						//console.log(replyJSON);
						//$("#newItemsList").html("");
						
						var bufferNewItem = "<div id='"+replyJSON.newItemId+"' class=\"tile double bg-color-green\" style='height:100px;display:none;'>"+"<div class=\"tile-content\">"+
												"<div class='icon'>"+
													"<i class='icon-tag place-left' style='font-size:32px;width:50px;height:50px'></i>"+
												"</div>"
												+"<div><h3 style=\"margin-botton:5px;\">"+replyJSON.itemName+"</h3>"+"<p>Quantity: "+replyJSON.qty+"</p><p>Date: "+replyJSON.date+"</p></div>"+
												"</div>";
												
						$("#newItemsList").append(bufferNewItem);
						$("#"+replyJSON.newItemId).show('slow');
						
					}catch(e){
						console.log("new item exception??"+e);
					}
				});
		}
	});
	
	
});
</script>