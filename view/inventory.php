<div class="span8">
	<h2>Inventory</h2>
	<div class="hero-unit span8">
		<div class="span7 bg-color-purple fg-color-white">
			<h3 class="fg-color-white padding5">Description<img src="images/simple.png" class="place-right" style="margin:10px"/></h3>
			<p class="padding20">This is shows quantity of items that are in stock at the Vets Place at specified dates. It also shows when new items were brought in.</p>
		</div>
		
		<div class="tile bg-color-pinkDark fg-color-white" id="btnShowItems" style="margin-top:1em;">
			<div class="tile-content">
				<i class='icon-grid' style='font-size:50px;margin-left:0.7em;margin-top:0.6em;'></i>
			</div>
			<div class="brand">
				<span class="name">Show All Items</span> 
			</div>
		</div>
		
		<div class="tile bg-color-pinkDark fg-color-white" id="btnShowStockItems" style="margin-top:1em;">
			<div class="tile-content">
				<!--img src="images/inventoryIcons/showStock.png" style="width:60px;height:60px;margin-left:2em;margin-top:2em;"-->
				<i class='icon-clipboard-2' style='font-size:50px;margin-left:0.7em;margin-top:0.6em;'></i>
			</div>
			<div class="brand">
				<span class="name">Show Stock</span> 
			</div>
		</div>
		
		<div class="tile bg-color-pinkDark fg-color-white" id="btnShowNewItems" style="margin-top:1em;">
			<div class="tile-content">
				<!--img src="images/armor.png" style="vertical-align:middle;"-->
				<i class='icon-clipboard' style='font-size:50px;margin-left:0.7em;margin-top:0.6em;'></i>
			</div>
			<div class="brand">
				<span class="name">Show New Items</span> 
			</div>
		</div>
		
		<div class="tile bg-color-pinkDark fg-color-white" id="btnAddItems" style="margin-top:1em;">
			<div class="tile-content">
				<!--img src="images/armor.png" style="vertical-align:middle;"-->
				<i class='icon-download-2' style='font-size:50px;margin-left:0.7em;margin-top:0.6em;'></i>
			</div>
			<div class="brand">
				<span class="name">Add New Items</span> 
			</div>
		</div>
		
		<div class="tile bg-color-pinkDark fg-color-white" id="btnTakeStock" style="margin-top:1em;">
			<div class="tile-content">
				<img src="images/simple.png" style="height:60px;margin-left:2em;margin-top:2em;">
			</div>
			<div class="brand">
				<span class="name">Take Stock</span> 
			</div>
		</div>
		
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#btnShowItems").click(function(){
			$(currentDiv).fadeOut(200,function(){
				$("#divShowAllItems").fadeIn(500);
				currentDiv = "#divShowAllItems";
			});
		});
		
		$("#btnShowStockItems").click(function(){
			$(currentDiv).fadeOut(200,function(){
				$("#divShowItems").fadeIn(500);
				currentDiv = "#divShowItems";
			});
		});
		
		$("#btnShowNewItems").click(function(){
			$(currentDiv).fadeOut(200,function(){
				$("#divShowNewItems").fadeIn(500);
				currentDiv = "#divShowNewItems";
			})
		});
		
		$("#btnAddItems").click(function(){
			$(currentDiv).fadeOut(200,function(){
				$("#divAddNewItem").fadeIn(500);
				currentDiv = "#divAddNewItem";
			});
		});
		
		$("#btnTakeStock").click(function(){
			$(currentDiv).fadeOut(200,function(){
				$("#divTakeStock").fadeIn(500);
				currentDiv = "#divTakeStock";
			});
		});
	});
</script>