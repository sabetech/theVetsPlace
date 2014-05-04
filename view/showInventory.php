<div class="span8"><!--The inventory table is here-->
	<h2><i class="icon-cabinet"></i>Show Items</h2>
	
		
	<div class="span3">
		<label>Show From:</label>
	</div>
	
	<div class="span3">
		<label>To:</label>
	</div>
	
	
		
	<div class="span3">
		<div style="width:200px" class="input-control text datepicker" data-role="datepicker">
		<input id="fromDate" type="text" />
			<button class="btn-date"></button>
		</div>
	</div>
	
	
	
	<div class="span3">
		<div style="width:200px" class="input-control text datepicker" data-role="datepicker" >
			<input id="toDate" type="text" />
			<button class="btn-date"></button>
		</div>
	</div>			
	
	
	<div class="span1">
		<button id="refreshInv" class="image-button fg-color-white bg-color-green "> 
			Show
			<i class="icon-loop"></i>
		</button>
	</div>
	
	<div id='stockInfo' class="span9" style='display:none;'>
		
	</div>
		
</div>
							
							
						
<script type="text/javascript">
	$(document).ready(function(){
					//window.unload(function(){})
		$("#refreshInv").click(function(){
			//console.log($("#fromDate").val());
			$.get("functional/getStockRecords.php",
			
			{fromdate:$("#fromDate").val(),
			 todate: $("#toDate").val()},
			 
			 function(responseText){
				var reply = responseText;
				var currentDate = "";
				var previousDate = "";
				
				
				try{
					var replyJSON = JSON.parse(reply);
					var bufferStockHtml="";
					
					$("#stockInfo").html("");
					
					for (var i=0;i<replyJSON.invtory.length;i++){
						currentDate = replyJSON.invtory[i].itemDate;
						
						if (currentDate != previousDate){
							if (i>0){
								bufferStockHtml += "</tbody></table></div>";
							}
							
							bufferStockHtml += "<div id='"+replyJSON.invtory[i].itemDate+"' class='stockInfo bg-color-green fg-color-white padding10'style='margin-top:1em;cursor:pointer;'>Stock As At:>"+replyJSON.invtory[i].itemDate+"</div>";
							
							bufferStockHtml += "<div class='stockTable "+replyJSON.invtory[i].itemDate+"' style='display:none;height:400px;overflow:scroll;'><table class='hovered striped'>"+
									"<thead>"+
										"<tr>"+
											"<th>Item</th>"+
											"<th>Quantity</th>"+
											"<th>Item Type</th>"+
										"</tr>"+
									"</thead><tbody>";
						}
						bufferStockHtml += "<tr><td>"+replyJSON.invtory[i].itemName+"</td><td id='"+replyJSON.invtory[i].inv_log_id+"' class='cellChange' style='cursor:pointer;'>"+replyJSON.invtory[i].itemQty+"</td><td>"+replyJSON.invtory[i].itemType+"</td></tr>";
						
						previousDate = currentDate;
							
					}
					
					$("#stockInfo").html(bufferStockHtml);
					$("#stockInfo").fadeIn(350);
					
					var previousId = "";
					$(".stockInfo").click(function(){
						$(".stockTable").hide(500);
						
						if (previousId != this.id)
							$("."+this.id).show(500);
							
						previousId = this.id;
						
					});
					
					//from here onwards shows the power of the God in programming. Thank You God For seeing me through
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
									$.post("functional/editStockVal.php",
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
				
					$(".stockInfo").hover(function(){
						//console.log("ioaojfie");
					});
				
				}catch(e){
					var noData = "<div id='goBack' class='tile double bg-color-red'>"+
					"<div class='tile-content'><h3>No data to Display...</h3><p class='fg-color-yellow'>Change the date. It is possible that no stock was taken between these dates...</p></div></div>";
					$("#stockInfo").html(noData);
					displayError("No Data to display. Check the date range","No Data Error!");
				}
			 }
			);
			
		});
	});
</script>