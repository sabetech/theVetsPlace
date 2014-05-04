<div ><!--put the dynamic size here-->
	<h2>Show New Items</h2>
	<div class="span3">
		<div class="input-control text datepicker" data-role="datepicker">
			<input id="newItemDate" type="text" />
			<button class="btn-date"></button>
		</div>
	</div>
	<div class="span2">
		<button id="refreshNewItems" class="image-button fg-color-white bg-color-green "> 
			Refresh
			<i class="icon-loop"></i>
		</button>
	</div>
	
	<!--div id="tableDiv" style='width:500px;height:350px;'><!--another dynamic size here.. remember the window.resize() jquery-->
		<table id="newItemsTable" class="hovered striped" style="left:100px;top:110px;position:absolute;overflow:scroll;">
			<thead>
				<tr>
					<th>Item</th>
					<th>Quantity</th>
					<th>Item Type</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Choose date to fetch New<br />Items corresponding to that date</td><td></td></td>
				</tr>
			</tbody>
		</table>
	<!--/div-->
	
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#refreshNewItems").click(function(){
		var url = "functional/getNewItems.php";
		$.get(url,
		{newItemDate:$("#newItemDate").val()},
		
		function(responseText){
			var reply = responseText;
				try{
					//console.log(reply);
					var replyJSON = JSON.parse(reply);
					var newItemsHtml = "";
					
					$("#newItemsTable tbody").html("");
					
					for(var i=0;i<replyJSON.newItems.length;i++){
						newItemsHtml += "<tr><td>"+replyJSON.newItems[i].itemName+"</td><td id='"+replyJSON.newItems[i].newItemId+"' class='cellChange' style='cursor:pointer;'>"+replyJSON.newItems[i].itemQty+"</td><td>"+replyJSON.newItems[i].itemType+"</td></tr>";
					}
					
					$("#newItemsTable tbody").html(newItemsHtml);
					
					var pencilIcon = "<i class='icon-pencil place-right'></i>";
					var theVal="";
					$(".cellChange").hover(function(){
						theVal = $(this).text();
						$(this).append(pencilIcon);
						
						$(this).click(function(){
							$(this).html("<input class='"+this.id+"' type='text' value='"+theVal+"' style='width:3em;'/>");
								$(this).off("click");
								$("."+this.id).change(function(){
									if ($(this).val() != ""){
										$(this).parent().off("hover");
										$(this).css({'background-color':'#b2f7b2'});
										$.post("functional/editNwItemVal.php",
											  {id:$(this).parent().attr("id"),
											   val:$(this).val()},
											   function (responseText){
													if (responseText != "FAILURE"){
														$(this).parent().html(responseText);
														theVal = responseText;
													}
													 $(this).parent().html("<img src = \"images/preloader-w8-cycle-black.gif\" width=\"25\" height=\"25\"/>");
											   });
									}else{
										$(this).css({'background-color':'#ff0000'});
									}
								});
						});
					},function(){
						$(this).html(theVal);
					});
					
				}catch(e){ 
					$("#newItemsTable tbody").html("No data to display");
					console.log(e);
				}
		})
	});
	
	
});	
</script>