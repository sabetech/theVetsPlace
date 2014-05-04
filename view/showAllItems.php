<div class="span8">
	<h2>Show All Items</h2>
	<div class="input-control text span3">
        <input id="txtSearchItem" type="text" placeholder="Search For Item(s)" /><!--text changed event handler here-->
        <button class="btn-search"></button>
    </div>
	
	<div style="width:70%;height:450px; overflow:scroll;">
		<ul id = "ul_allItems" class="listview fluid">
			
		</ul>
	</div>
	
</div>
<script type='text/javascript'>
$(document).ready(function(){
	var allItemsShown = false;
	var loading = "<img src=\"images/preloader-w8-cycle-black.gif\">";
	
	$("#txtSearchItem").keyup(function (){
		$("#ul_allItems").html(loading);
		loadItemsAsync("functional/searchItemWidQty.php");
	});
	
	$("#btnShowItems").click(function(){
		var url = "functional/getAllItems.php";
		loadItemsAsync(url);
	});
	
	function loadItemsAsync(url){
		$.get(url,
		{itemName:$("#txtSearchItem").val()},
		function (responseText)	{
			var reply = responseText;
			//try{
				var replyJSON = JSON.parse(reply);
			
				bufferedList_str = "";
				
				if (typeof replyJSON.items[0].itemQty != 'undefined'){
					for(var i=0;i<replyJSON.items.length;i++){
						bufferedList_str += "<li class=\"bg-color-pinkDark fg-color-white\"><h4 class=\"fg-color-white\">"+replyJSON.items[i].itemName+"</h4> <small> "+replyJSON.items[i].itemType+"</small><p class=\"place-right\">"+replyJSON.items[i].itemQty+"</p></li>";
					}
					$("#ul_allItems").html(bufferedList_str); 
				}else{
					for(var i=0;i<replyJSON.items.length;i++){
						bufferedList_str += "<li class=\"bg-color-pinkDark fg-color-white\"><h4 class=\"fg-color-white\">"+replyJSON.items[i].itemName+"</h4> <small> "+replyJSON.items[i].itemType+"</small><p class=\"place-right\"></p></li>";
					}
					$("#ul_allItems").html(bufferedList_str); 
				}
				//if ()
				
			//}catch(e){
				//console.log(e);
				//$("#ul_allItems").html("<li class=\"bg-color-red fg-color-yellow\"><h4 class=\"fg-color-yellow\">Item Not Found</h4> <small>Item Could Not be found in the database</small><p class=\"place-right\">Error</p></li>"); 
			//}	
		});
	}
	
	
});
</script>