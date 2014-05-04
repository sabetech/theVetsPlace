<div  style="width:100%">
	<h2>Take Stock</h2>
	
		<!--<form method="GET"-->
			<div class="span4">
			<label>Select Date</label>
			
			<div style="width:200px;" class="input-control text datepicker" data-role="datepicker">
			<input id="dateOfStock" type="text" />
				<button class="btn-date"></button>
			</div>
			</div>
			
			<button id='useInOutBal' class='bg-color-purple fg-color-white'>Click to Enable In-Out-Balance</button>
			
			<div id="stockItems" class="offset1" style="width:100%; height:450px; top:100px; position:absolute; overflow:scroll;">
			</div>
			
			<div class='place-right'><button id='SaveStockInfo' class='bg-color-green fg-color-white'>Save</button><button id='Cancel' class='bg-color-green fg-color-white'>Cancel</button>
			</div>
				
										<!--</form>-->
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var loading = "<img src=\"images/preloader-w8-cycle-black.gif\">";
		
		$("#btnTakeStock").click(function(){
			var url = "functional/getCurrentItems.php";
			$("#stockItems").html(loading);
			loadStockItemsAsync(url);
			
		});
		
	$("#useInOutBal").click(function(){
		if ($(".takeStockText").is(':disabled')){
			$(".takeStockText").prop('disabled',false);
			$(".InVal").prop('disabled',true);
			$(".OutVal").prop('disabled',true);
		}else{
			$(".OutVal").prop('disabled',false);
			$(".InVal").prop('disabled',false);
			$(".takeStockText").prop('disabled',true);
		}
		
		//$.(".theNewInputBoxes")
	});
	
	$("#SaveStockInfo").click(function(){
		//check If Stock Taken
			$.get("functional/checkIfStockTaken.php",
				 {date:$("#dateOfStock").val()},
				 function(responseText){
					if (responseText == 'true'){
						displayError("Sorry, Stock has already been taken for the date specified. If you want to change some the values to the date specified, go to the \"show stock\" page and edit the values corresponding to the date chosen!","Stock Taken Already For This Date!");
						//trackUser("took stock of inventory");
					}else{//validate before you take
					
						$(".lblBalc").each(function(){
							
							var url = "functional/saveStock.php";
						
							$.post(url,{itemId:this.id,
										quantity:$(this).text(),
										date:$("#dateOfStock").val()},
										function(responseText){
											
										}
							)
				
						});
						var successMsg = "Stock for the date "+$("#dateOfStock").val()+" was taken successfully";
						displayNotice(successMsg,"Stock Taken");
						trackUser("took stock of inventory");
					}
			   });
				 
			
			$(currentDiv).hide(500,function(){
					$("#divInventory").show(500);
					currentDiv = "#divInventory";
			});	
		
		});
		
		function trackUser(action){
		
			$.post("functional/trackUser.php",
				  {action:action});
		}
	
});

function loadStockItemsAsync(url){
			$.get(url,
			function(responseText){
				var reply = responseText;
				var count = 0;
				try{
					var replyJSON = JSON.parse(reply);
					
					bufferStockItems="";
						var newArr = replyJSON.currentItems;
						bufferStockItems+="<div id='stkList'>";
						//this is part I suffered the most!! ...precious code:D
						for (var prop in newArr){
							var value = newArr[prop];//currentItems
							for (var val in value){
								var s = value[val];
								bufferStockItems+="<div style='width:100%;'><h4 class='padding10 bg-color-green fg-color-white'><strong>"+val+"</strong></h4>"; //item category
												
								for(var ss in s){
									try{
										if (typeof s[ss].itemQty != 'undefined'){
											bufferStockItems += "<div style='width:500px;' class='padding10'>"+
											"<label class='span2'>"+s[ss].itemName+"</label>"+
											"<input type='text'  placeholder='Qty' value='"+s[ss].itemQty+"' class='takeStockText' style='width:50px;margin-right:2em;'/>In:<input type='text' disabled value='0' class='InVal' style='margin-right:1em;width:50px;'/>Out:<input type='text' disabled value='0' class='OutVal' style='margin-right:1em;width:50px;'/><span>bal:<label id='"+s[ss].itemId+"'  class='class='lblBalc bg-color-blue fg-color-white padding5'>0</label></span></div>";
										}else{									
											bufferStockItems += "<div style='width:600px;' class='padding10'>"+
											"<p class='span2'>"+s[ss].itemName+"</p>"+
											"<input id='"+s[ss].itemId+"' type='text' placeholder='Qty' class='takeStockText' value='0' style='width:50px;margin-right:2em;'/>In:<input type='text' disabled value='0' class='InVal' style='margin-right:1em;width:50px;'/>Out:<input type='text' disabled value='0' class='OutVal' style='margin-right:1em;width:50px;'/><span>bal:<label id='"+s[ss].itemId+"' class='lblBalc bg-color-blue fg-color-white padding5'>0</label></span></div>";
										}
									}catch(e){
										console.log(e);
									}
								}
							}
							bufferStockItems += "</div>";
						}
	
						bufferStockItems += "</div>";
						$("#stockItems").html(bufferStockItems);
				}catch(e){
					console.log("error"+e);
				}
				
				$("input[class=takeStockText]").change(function(){
					if ($(this).val() != ""){
						$(this).css({'background-color':'#b2f7b2'});
						$(this).parent().find('label').text($(this).val());
					}else{
						$(this).css({'background-color':'#ff0000'});
					}
					
					//console.log($(this).parent().find('label').text());
					
				});
				
				$("input[class=InVal]").change(function(){
					
					var inVal = $(this).val();
					var outVal = $(this).next().val();
					
					var inoutBal = (inVal - outVal);
					var prevVal = $(this).prev().val();
					
					if (!(isNaN(Number(inoutBal)))){
						prevVal = Number(prevVal);
						var newBal = (prevVal + inoutBal);
						$(this).parent().find('label').text(newBal);
					}else{
						$(this).css({'background-color':'#FFA500'});
					}
				});
				
				$("input[class=OutVal]").change(function(){
				
					var outVal = $(this).val();
					var inVal = $(this).prev().val();
					
					var inoutBal = (inVal - outVal);
					var prevVal = $(this).prev().prev().val();
					
					if (!(isNaN(Number(inoutBal)))){
						prevVal = Number(prevVal);
						var newBal = (prevVal + inoutBal);
						$(this).parent().find('label').text(newBal);
					}else{
						$(this).css({'background-color':'#FFA500'});
					}
					
				});
			});	
	}

</script>